<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    private $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);

        try {
            // Server settings
            $this->mailer->isSMTP();
            $this->mailer->Host = $_ENV['SMTP_HOST'];
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = $_ENV['SMTP_USER'];
            $this->mailer->Password = $_ENV['SMTP_PASS'];
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mailer->Port = $_ENV['SMTP_PORT'];

            // Debug settings (temporarily enable for troubleshooting)
            $this->mailer->SMTPDebug = SMTP::DEBUG_SERVER;
            $this->mailer->Debugoutput = function ($str, $level) {
                error_log("SMTP Debug: $str");
            };
        } catch (Exception $e) {
            error_log("Mailer configuration error: " . $e->getMessage());
            throw $e;
        }
    }

    public function sendConfirmationEmail($email, $name, $token)
    {
        try {
            // Clear any previous recipients
            $this->mailer->clearAddresses();
            $this->mailer->clearReplyTos();

            // Set sender
            $this->mailer->setFrom($_ENV['SMTP_FROM'], $_ENV['SMTP_FROM_NAME']);
            $this->mailer->addAddress($email, $name);
            $this->mailer->isHTML(true);

            $confirmationLink = $_ENV['APP_URL'] . "/verify-email?token=" . $token;

            $this->mailer->Subject = 'Confirm Your Email - ' . $_ENV['APP_NAME'];
            $this->mailer->Body = "
                <h2>Welcome to " . $_ENV['APP_NAME'] . "!</h2>
                <p>Hi {$name},</p>
                <p>Please click the link below to confirm your email address:</p>
                <p><a href='{$confirmationLink}'>Confirm Email</a></p>
                <p>If you didn't create this account, you can ignore this email.</p>
            ";

            return $this->mailer->send();
        } catch (Exception $e) {
            error_log("Email sending failed: " . $this->mailer->ErrorInfo);
            throw $e;
        }
    }
}
