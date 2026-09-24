<?php
/**
 * Mailer Class - Sistema de Notificações por Email GHS
 *
 * Suporta envio nativo via SMTP (Gmail, Outlook, Hostinger, cPanel, Mailtrap, etc.)
 * ou fallback transparente via função mail() do PHP.
 */
class Mailer {

    /**
     * Envia um email formatado com template HTML institucional.
     *
     * @param string $to        Email do destinatário
     * @param string $subject   Assunto do email
     * @param string $message   Corpo da mensagem (texto ou HTML parcial)
     * @param string|null $from Remetente (opcional)
     * @return bool
     */
    public static function send($to, $subject, $message, $from = null) {
        if (!defined('MAIL_ENABLED') || !MAIL_ENABLED) {
            error_log("[GHS Mailer] Envio de email desativado nas configurações (MAIL_ENABLED=false).");
            return false;
        }

        $appName     = defined('APP_NAME') ? APP_NAME : 'GHS - Green Hard & Soft';
        $fromAddress = $from ?? (defined('MAIL_FROM_ADDRESS') ? MAIL_FROM_ADDRESS : 'no-reply@green.edu.gw');
        $fromName    = defined('MAIL_FROM_NAME') ? MAIL_FROM_NAME : $appName;
        $year        = date('Y');

        $htmlContent = "
        <!DOCTYPE html>
        <html lang='pt'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>" . htmlspecialchars($subject) . "</title>
        </head>
        <body style='margin:0;padding:0;background-color:#f1f5f9;font-family:\"Segoe UI\", Tahoma, Geneva, Verdana, sans-serif;color:#334155;'>
          <table width='100%' cellpadding='0' cellspacing='0' style='background-color:#f1f5f9;padding:40px 0;'>
            <tr>
              <td align='center'>
                <table width='620' cellpadding='0' cellspacing='0' style='background-color:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.06);border:1px solid #e2e8f0;'>
                  <!-- Cabeçalho Institucional -->
                  <tr>
                    <td style='background:linear-gradient(135deg, #1a5632 0%, #0d381e 100%);padding:35px 40px;text-align:center;'>
                      <h1 style='color:#ffffff;margin:0;font-size:24px;font-weight:700;letter-spacing:0.5px;'>{$appName}</h1>
                      <p style='color:#86efac;margin:6px 0 0;font-size:13px;font-weight:500;'>Escola Superior de Informática &middot; Gestão Académica</p>
                    </td>
                  </tr>
                  <!-- Corpo da Mensagem -->
                  <tr>
                    <td style='padding:40px;color:#334155;'>
                      <h2 style='color:#0f172a;margin:0 0 20px;font-size:19px;border-bottom:2px solid #f1f5f9;padding-bottom:12px;font-weight:700;'>{$subject}</h2>
                      <div style='line-height:1.7;font-size:15px;color:#475569;'>
                        {$message}
                      </div>
                    </td>
                  </tr>
                  <!-- Rodapé -->
                  <tr>
                    <td style='background-color:#f8fafc;padding:24px 40px;border-top:1px solid #e2e8f0;text-align:center;'>
                      <p style='margin:0;font-size:12px;color:#94a3b8;line-height:1.5;'>
                        &copy; {$year} {$appName}. Todos os direitos reservados.<br>
                        Esta é uma mensagem automática gerada pelo sistema. Por favor, não responda diretamente a este e-mail.
                      </p>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </body>
        </html>";

        // 1. Tentar envio por SMTP se configurado como 'smtp'
        if (defined('MAIL_DRIVER') && MAIL_DRIVER === 'smtp' && defined('MAIL_HOST') && !empty(MAIL_HOST)) {
            $smtpSent = self::sendViaSmtp($to, $subject, $htmlContent, $fromAddress, $fromName);
            if ($smtpSent) {
                return true;
            }
            error_log("[GHS Mailer] Falha no SMTP. Tentando fallback para mail() nativo...");
        }

        // 2. Fallback via função nativa mail() do PHP
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: {$fromName} <{$fromAddress}>\r\n";
        $headers .= "Reply-To: {$fromAddress}\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

        $sent = @mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $htmlContent, $headers);

        if (!$sent) {
            error_log("[GHS Mailer] Email NÃO enviado para {$to} | Assunto: {$subject}");
        } else {
            error_log("[GHS Mailer] Email enviado com sucesso via mail() para {$to} | Assunto: {$subject}");
        }

        return $sent;
    }

    /**
     * Cliente SMTP Socket Puro em PHP (Sem dependências externas).
     */
    protected static function sendViaSmtp($to, $subject, $htmlContent, $fromAddress, $fromName) {
        $host       = MAIL_HOST;
        $port       = defined('MAIL_PORT') ? MAIL_PORT : 587;
        $encryption = defined('MAIL_ENCRYPTION') ? strtolower(MAIL_ENCRYPTION) : 'tls';
        $username   = defined('MAIL_USERNAME') ? MAIL_USERNAME : '';
        $password   = defined('MAIL_PASSWORD') ? MAIL_PASSWORD : '';
        $timeout    = 15;

        $protocol = ($encryption === 'ssl' || $port == 465) ? 'ssl://' : 'tcp://';
        $socket = @fsockopen($protocol . $host, $port, $errno, $errstr, $timeout);

        if (!$socket) {
            error_log("[GHS Mailer SMTP] Erro de conexão socket para {$host}:{$port} ($errno): $errstr");
            return false;
        }

        $read = function() use ($socket) {
            $data = '';
            while ($line = fgets($socket, 512)) {
                $data .= $line;
                if (substr($line, 3, 1) === ' ') break;
            }
            return $data;
        };

        $write = function($cmd) use ($socket) {
            fputs($socket, $cmd . "\r\n");
        };

        try {
            $response = $read();
            if (substr($response, 0, 3) !== '220') throw new Exception("Banner inválido: " . trim($response));

            $write("EHLO " . (gethostname() ?: 'localhost'));
            $response = $read();

            if ($encryption === 'tls' || $port == 587) {
                $write("STARTTLS");
                $response = $read();
                if (substr($response, 0, 3) !== '220') throw new Exception("STARTTLS falhou: " . trim($response));

                $crypto = stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
                if (!$crypto) throw new Exception("Falha ao negociar TLS.");

                $write("EHLO " . (gethostname() ?: 'localhost'));
                $response = $read();
            }

            if (!empty($username) && !empty($password)) {
                $write("AUTH LOGIN");
                $response = $read();
                if (substr($response, 0, 3) !== '334') throw new Exception("AUTH LOGIN rejeitado: " . trim($response));

                $write(base64_encode($username));
                $response = $read();
                if (substr($response, 0, 3) !== '334') throw new Exception("Usuário SMTP rejeitado.");

                $write(base64_encode($password));
                $response = $read();
                if (substr($response, 0, 3) !== '235') throw new Exception("Senha SMTP rejeitada: " . trim($response));
            }

            $write("MAIL FROM: <{$fromAddress}>");
            $response = $read();
            if (substr($response, 0, 3) !== '250') throw new Exception("MAIL FROM falhou: " . trim($response));

            $write("RCPT TO: <{$to}>");
            $response = $read();
            if (substr($response, 0, 3) !== '250' && substr($response, 0, 3) !== '251') {
                throw new Exception("RCPT TO falhou para {$to}: " . trim($response));
            }

            $write("DATA");
            $response = $read();
            if (substr($response, 0, 3) !== '354') throw new Exception("DATA rejeitado: " . trim($response));

            $headers  = "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $headers .= "From: =?UTF-8?B?" . base64_encode($fromName) . "?= <{$fromAddress}>\r\n";
            $headers .= "To: <{$to}>\r\n";
            $headers .= "Subject: =?UTF-8?B?" . base64_encode($subject) . "?=\r\n";
            $headers .= "Date: " . date('r') . "\r\n";
            $headers .= "X-Mailer: GHS SMTP Engine\r\n";

            $write($headers . "\r\n" . $htmlContent . "\r\n.");
            $response = $read();
            if (substr($response, 0, 3) !== '250') throw new Exception("Envio de dados falhou: " . trim($response));

            $write("QUIT");
            fclose($socket);

            error_log("[GHS Mailer SMTP] Email entregue com sucesso via SMTP para {$to} | Assunto: {$subject}");
            return true;

        } catch (Exception $e) {
            error_log("[GHS Mailer SMTP Error] " . $e->getMessage());
            if (is_resource($socket)) {
                fclose($socket);
            }
            return false;
        }
    }

    /**
     * Email de notificação de matrícula rejeitada informando o MOTIVO com destaque.
     */
    public static function sendMatriculaRejeitada($to, $nome, $motivo = '') {
        $subject = 'Aviso Importante: Matrícula Rejeitada — GHS';
        $motivoTexto = !empty($motivo) ? htmlspecialchars($motivo, ENT_QUOTES, 'UTF-8') : 'Documentação irregular ou dados incompletos.';
        $portalUrl = (defined('URL_ROOT') ? URL_ROOT : '') . '/auth';

        $message = "
            <p style='margin-bottom:15px;'>Olá, <strong>" . htmlspecialchars($nome, ENT_QUOTES, 'UTF-8') . "</strong>,</p>
            <p style='margin-bottom:20px;'>Informamos que a análise da sua candidatura de matrícula foi concluída e o seu pedido foi <strong style='color:#dc2626;'>REJEITADO</strong> pela equipa académica.</p>
            
            <div style='background-color:#fef2f2;border-left:4px solid #ef4444;padding:16px 20px;border-radius:6px;margin:25px 0;'>
                <p style='margin:0 0 6px;color:#991b1b;font-weight:700;font-size:14px;text-transform:uppercase;letter-spacing:0.5px;'>Motivo da Rejeição:</p>
                <p style='margin:0;color:#b91c1c;font-size:15px;line-height:1.6;font-style:italic;'>&ldquo;{$motivoTexto}&rdquo;</p>
            </div>

            <p style='margin-bottom:15px;'><strong>Como regularizar a sua situação:</strong></p>
            <ol style='margin-top:0;margin-bottom:25px;padding-left:20px;color:#475569;line-height:1.6;'>
                <option style='margin-bottom:8px;'>Aceda ao seu portal institucional com o seu email e senha.</option>
                <option style='margin-bottom:8px;'>Corrija as informações ou anexe os documentos válidos solicitados acima.</option>
                <option style='margin-bottom:8px;'>Submeta novamente a sua candidatura para reavaliação da Secretaria.</option>
            </ol>

            <div style='text-align:center;margin:30px 0;'>
                <a href='{$portalUrl}' style='display:inline-block;background-color:#1a5632;color:#ffffff;font-weight:700;padding:13px 30px;border-radius:8px;text-decoration:none;font-size:15px;box-shadow:0 4px 12px rgba(26,86,50,0.25);'>Aceder ao Portal para Regularizar</a>
            </div>

            <p style='margin-bottom:0;color:#64748b;font-size:14px;'>Se tiver alguma dúvida, dirija-se à Secretaria da nossa instituição ou entre em contacto pelos canais oficiais de atendimento.</p>
            <p style='margin-top:25px;color:#334155;'>Com os melhores cumprimentos,<br><strong>Secretaria &middot; Green Hard & Soft</strong></p>
        ";

        return self::send($to, $subject, $message);
    }

    /**
     * Email de confirmação de matrícula aprovada.
     */
    public static function sendMatriculaAprovada($to, $nome, $anoNome = '') {
        $subject = 'Parabéns! Matrícula Aprovada — GHS';
        $anoInfo = $anoNome ? " para o <strong>" . htmlspecialchars($anoNome) . "</strong>" : '';
        $portalUrl = (defined('URL_ROOT') ? URL_ROOT : '') . '/estudante';

        $message = "
            <p>Olá, <strong>" . htmlspecialchars($nome) . "</strong>!</p>
            <p>Temos o prazer de informar que a sua matrícula{$anoInfo} foi <strong style='color:#16a34a;'>APROVADA</strong> com sucesso!</p>
            <p>Já pode aceder ao Portal do Estudante para consultar a sua turma, horários de aulas e plano curricular.</p>
            <div style='text-align:center;margin:25px 0;'>
                <a href='{$portalUrl}' style='display:inline-block;background-color:#1a5632;color:#ffffff;font-weight:700;padding:12px 28px;border-radius:8px;text-decoration:none;font-size:14px;'>Aceder ao Portal do Estudante</a>
            </div>
            <p>Desejamos-lhe um excelente ano académico!</p>
            <p>Atenciosamente,<br><strong>Secretaria GHS</strong></p>
        ";
        return self::send($to, $subject, $message);
    }

    /**
     * Email de Boas-Vindas e credenciais de acesso provisórias.
     */
    public static function sendWelcomeCandidate($to, $nome, $senha) {
        $subject = 'Candidatura Recebida — Portal GHS';
        $portalUrl = (defined('URL_ROOT') ? URL_ROOT : '') . '/auth';

        $message = "
            <p>Olá, <strong>" . htmlspecialchars($nome) . "</strong>!</p>
            <p>Recebemos com sucesso o seu pedido de inscrição na <strong>Green Hard & Soft</strong>.</p>
            <p>Foi criada uma conta de acesso para que possa acompanhar o estado da sua candidatura em tempo real.</p>
            
            <div style='background-color:#f8fafc;padding:18px 22px;border-radius:8px;margin:20px 0;border:1px solid #e2e8f0;'>
                <p style='margin:0 0 10px;font-weight:700;color:#0f172a;'>As suas credenciais de acesso:</p>
                <p style='margin:6px 0;'><strong>Utilizador:</strong> " . htmlspecialchars($to) . "</p>
                <p style='margin:6px 0;'><strong>Palavra-passe:</strong> <span style='color:#1a5632;font-family:monospace;font-size:1.15rem;font-weight:700;background:#e2e8f0;padding:2px 8px;border-radius:4px;'>" . htmlspecialchars($senha) . "</span></p>
            </div>

            <p>Pode aceder ao portal aqui: <a href='{$portalUrl}' style='color:#1a5632;font-weight:bold;'>Aceder ao Portal GHS</a></p>
            <p><strong>Nota:</strong> Guarde estes dados com segurança.</p>
            <p>Atenciosamente,<br><strong>Secretaria GHS</strong></p>
        ";
        return self::send($to, $subject, $message);
    }

    public static function sendWelcome($to, $nome) {
        return self::sendMatriculaAprovada($to, $nome);
    }
}

