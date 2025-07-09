<?php
// Utilities/Mailer.php

namespace App\Modules\ForgotPassword\Utilities;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer {
    public function sendResetLink(string $email, string $token): void {
        $mail = new PHPMailer(true);
        $resetLink = "http://localhost:5173/reset-password?token=$token";

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'demodev768@gmail.com';
            $mail->Password = 'doqs qowp frba evrw';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('demodev768@gmail.com', 'Clockwise App');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Reset Your Password';

            ob_start();
            include __DIR__ . '/Templates/reset_email_template.php';
            $mail->Body = ob_get_clean();

            $mail->send();
        } catch (Exception $e) {
            error_log('Mailer Error: ' . $mail->ErrorInfo);
        }
    }
}