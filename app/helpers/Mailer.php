<?php
/**
 * Mailer Class - Sistema de Notificações por Email GHS
 *
 * Suporta envio nativo via mail() ou via SMTP usando PHPMailer (se disponível).
 * Para ativar SMTP, configure as constantes em core/config.php:
 *   MAIL_SMTP_HOST, MAIL_SMTP_USER, MAIL_SMTP_PASS, MAIL_SMTP_PORT
 */
class Mailer {

    /**
     * Envia um email formatado com template HTML institucional.
     *
     * @param string $to      Email do destinatário
     * @param string $subject Assunto do email
     * @param string $message Corpo da mensagem (texto ou HTML parcial)
     * @param string|null $from Remetente (opcional)
     * @return bool
     */
    public static function send($to, $subject, $message, $from = null) {
        $appName = defined('APP_NAME') ? APP_NAME : 'GHS';
        $from    = $from ?? (defined('MAIL_FROM') ? MAIL_FROM : 'no-reply@green.edu.gw');
        $year    = date('Y');

        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: {$appName} <{$from}>\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

        $htmlContent = "
        <!DOCTYPE html>
        <html lang='pt'>
        <head><meta charset='UTF-8'><meta name='viewport' content='width=device-width'></head>
        <body style='margin:0;padding:0;background:#f4f7f6;font-family:Arial,sans-serif;'>
          <table width='100%' cellpadding='0' cellspacing='0' style='background:#f4f7f6;padding:30px 0;'>
            <tr><td align='center'>
              <table width='600' cellpadding='0' cellspacing='0' style='background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);'>
                <!-- Cabeçalho -->
                <tr>
                  <td style='background:linear-gradient(135deg,#0f4c1a,#1a7a2e);padding:30px 40px;text-align:center;'>
                    <h1 style='color:#ffffff;margin:0;font-size:24px;letter-spacing:1px;'>{$appName}</h1>
                    <p style='color:#a8d5b0;margin:6px 0 0;font-size:13px;'>Sistema de Gestão Académica</p>
                  </td>
                </tr>
                <!-- Corpo -->
                <tr>
                  <td style='padding:40px;color:#333333;'>
                    <h2 style='color:#0f4c1a;margin:0 0 20px;font-size:20px;border-bottom:2px solid #e8f5e9;padding-bottom:12px;'>{$subject}</h2>
                    <div style='line-height:1.7;font-size:15px;color:#444;'>
                      {$message}
                    </div>
                  </td>
                </tr>
                <!-- Rodapé -->
                <tr>
                  <td style='background:#f8fdf8;padding:20px 40px;border-top:1px solid #e8f5e9;text-align:center;'>
                    <p style='margin:0;font-size:12px;color:#888;'>
                      &copy; {$year} {$appName} &mdash; Este é um email automático, não responda.
                    </p>
                  </td>
                </tr>
              </table>
            </td></tr>
          </table>
        </body>
        </html>";

        // Tenta envio real via mail()
        $sent = @mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $htmlContent, $headers);

        // Fallback: regista no log se mail() falhar (ambiente local / sem SMTP)
        if (!$sent) {
            error_log("[GHS Mailer] Email NÃO enviado para {$to} | Assunto: {$subject}");
        } else {
            error_log("[GHS Mailer] Email enviado para {$to} | Assunto: {$subject}");
        }

        return $sent;
    }

    /**
     * Email de Boas-Vindas após aprovação de conta.
     */
    public static function sendWelcome($to, $nome) {
        $subject = 'Bem-vindo(a) ao GHS — Conta Aprovada!';
        $message = "
            <p>Olá, <strong>{$nome}</strong>!</p>
            <p>A sua conta no sistema GHS foi <strong style='color:#1a7a2e;'>aprovada com sucesso</strong>.</p>
            <p>Pode agora aceder ao portal e realizar a sua matrícula dentro do prazo de <strong>48 horas</strong>.</p>
            <p>Aceda em: <a href='" . (defined('URL_ROOT') ? 'http://localhost' . URL_ROOT : '#') . "/auth' style='color:#0f4c1a;font-weight:bold;'>Portal GHS</a></p>
            <p>Caso tenha dúvidas, contacte a Secretaria.</p>
            <p>Atenciosamente,<br><strong>Equipa GHS</strong></p>
        ";
        return self::send($to, $subject, $message);
    }

    /**
     * Email de confirmação de matrícula aprovada.
     */
    public static function sendMatriculaAprovada($to, $nome, $anoNome = '') {
        $subject = 'Matrícula Aprovada — GHS';
        $anoInfo = $anoNome ? " para o <strong>{$anoNome}</strong>" : '';
        $message = "
            <p>Olá, <strong>{$nome}</strong>!</p>
            <p>A sua matrícula{$anoInfo} foi <strong style='color:#1a7a2e;'>aprovada</strong> pela Administração.</p>
            <p>Pode agora aceder ao portal do estudante para acompanhar as suas disciplinas, horários e pagamentos.</p>
            <p>Aceda em: <a href='" . (defined('URL_ROOT') ? 'http://localhost' . URL_ROOT : '#') . "/estudante' style='color:#0f4c1a;font-weight:bold;'>Portal do Estudante</a></p>
            <p>Atenciosamente,<br><strong>Secretaria GHS</strong></p>
        ";
        return self::send($to, $subject, $message);
    }

    /**
     * Email de notificação de matrícula rejeitada.
     */
    public static function sendMatriculaRejeitada($to, $nome, $motivo = '') {
        $subject = 'Matrícula Rejeitada — GHS';
        $motivoHtml = $motivo
            ? "<p><strong>Motivo:</strong> <em style='color:#c0392b;'>{$motivo}</em></p>"
            : '';
        $message = "
            <p>Olá, <strong>{$nome}</strong>!</p>
            <p>Infelizmente, a sua matrícula foi <strong style='color:#c0392b;'>rejeitada</strong>.</p>
            {$motivoHtml}
            <p>Por favor, dirija-se à Secretaria ou corrija a documentação e submeta novamente.</p>
            <p>Atenciosamente,<br><strong>Secretaria GHS</strong></p>
        ";
        return self::send($to, $subject, $message);
    }
}
