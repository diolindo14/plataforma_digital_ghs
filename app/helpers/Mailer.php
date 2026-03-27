<?php
/**
 * Mailer Class - Sistema de Notificações por Email (Pilar 8)
 * 
 * Atualmente atua como um wrapper para a função mail() do PHP,
 * mas está preparado para ser estendido com PHPMailer ou SwiftMailer.
 */
class Mailer {
    
    /**
     * Envia um email formatado.
     */
    public static function send($to, $subject, $message, $from = null) {
        $appName = APP_NAME;
        $from = $from ?? "no-reply@green.edu.gw";
        
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: $appName <$from>" . "\r\n";

        // Template básico HTML (Wow Factor)
        $htmlContent = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #eee; border-radius: 10px; padding: 20px;'>
            <div style='background: #1a5c20; padding: 10px; border-radius: 5px; text-align: center;'>
                <h1 style='color: white; margin: 0;'>$appName</h1>
            </div>
            <div style='padding: 20px; color: #333;'>
                <h2 style='color: #1a5c20;'>$subject</h2>
                <p>$message</p>
            </div>
            <div style='margin-top: 20px; font-size: 12px; color: #777; border-top: 1px solid #eee; padding-top: 10px; text-align: center;'>
                &copy; " . date('Y') . " $appName. Todos os direitos reservados.
            </div>
        </div>
        ";

        // // Sugestão: Usar PHPMailer para suporte a SMTP real (Gmail, SendGrid, etc).
        // return mail($to, $subject, $htmlContent, $headers);
        
        // Simulação para ambiente local sem servidor SMTP configurado
        error_log("Email simulado para $to: Subject: $subject");
        return true; 
    }
}
