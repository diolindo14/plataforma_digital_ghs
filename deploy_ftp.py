import ftplib
import os
import io
import time

FTP_HOST = 'ftpupload.net'
FTP_USER = 'if0_41574650'
FTP_PASS = '0svEjAnMHnX'
LOCAL_DIR = r'c:\xampp\htdocs\green'
REMOTE_DIR = 'htdocs'

def connect_ftp():
    ftp = ftplib.FTP(FTP_HOST)
    ftp.login(FTP_USER, FTP_PASS)
    return ftp

def ensure_dir(ftp, dir_path):
    parts = dir_path.strip('/').split('/')
    current = '/'
    ftp.cwd('/')
    for part in parts:
        if not part: continue
        current += part + '/'
        try:
            ftp.cwd(current)
        except ftplib.error_perm:
            ftp.mkd(current)
            ftp.cwd(current)

def upload_files(ftp, base_local_dir):
    total = 0
    for root, dirs, files in os.walk(base_local_dir):
        dirs[:] = [d for d in dirs if d not in ['.git', 'tmp', 'manutencao', 'database']]
        
        for name in files:
            if name in ['deploy_ghs.zip', 'deploy_ghs.sql', 'deploy_ftp.py', 'tmp_git_log.txt', 'tmp_diff_stat.txt', 'tmp_diff_stat2.txt', 'tmp_old_git_log.txt', 'tmp_git_status.txt']:
                continue

            local_path = os.path.join(root, name)
            rel_path = os.path.relpath(local_path, base_local_dir).replace('\\', '/')
            remote_path = f"/{REMOTE_DIR}/{rel_path}"
            
            remote_dir = os.path.dirname(remote_path)
            try:
                ftp.cwd(remote_dir)
            except ftplib.error_perm:
                ensure_dir(ftp, remote_dir)
                ftp.cwd(remote_dir)
            
            print(f"Uploading {rel_path}...", end='')
            try:
                if name == '.htaccess' and rel_path == '.htaccess':
                    with open(local_path, 'r', encoding='utf-8') as f:
                        content = f.read()
                    content = content.replace('RewriteBase /green/', 'RewriteBase /')
                    ftp.storbinary(f'STOR {name}', io.BytesIO(content.encode('utf-8')))
                elif name == 'config.php' and rel_path == 'core/config.php':
                    with open(local_path, 'r', encoding='utf-8') as f:
                        content = f.read()
                    content = content.replace("define('URL_ROOT', '/green');", "define('URL_ROOT', '');")
                    ftp.storbinary(f'STOR {name}', io.BytesIO(content.encode('utf-8')))
                else:
                    with open(local_path, 'rb') as f:
                        ftp.storbinary(f'STOR {name}', f)
                print(" OK")
                total += 1
            except Exception as e:
                print(f" FAILED: {e}")
                time.sleep(2)
                try:
                    ftp = connect_ftp()
                    ftp.cwd(remote_dir)
                    with open(local_path, 'rb') as f:
                        ftp.storbinary(f'STOR {name}', f)
                    print(" OK (Retry)")
                    total += 1
                except Exception as retry_e:
                    print(f" FAILED AGAIN: {retry_e}")
                    
    print(f"\nCompleted! Uploaded {total} files.")

if __name__ == '__main__':
    ftp = connect_ftp()
    upload_files(ftp, LOCAL_DIR)
    ftp.quit()
