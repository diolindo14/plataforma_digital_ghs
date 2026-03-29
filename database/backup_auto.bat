@echo off
SET BACKUP_DIR=C:\xampp\htdocs\green\database\backups
IF NOT EXIST "%BACKUP_DIR%" MKDIR "%BACKUP_DIR%"
C:\xampp\mysql\bin\mysqldump.exe -h localhost -u root ghsespf_db > "%BACKUP_DIR%\ghs_backup_%date:~-4%%date:~4,2%%date:~7,2%.sql"
echo Backup ok.
