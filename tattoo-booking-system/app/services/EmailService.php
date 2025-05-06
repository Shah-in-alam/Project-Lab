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

        // Configure PHPMailer using config values
        $this->mailer->isSMTP();
        $this->mailer->Host = SMTP_HOST;          // Remove backslash
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = SMTP_USERNAME;   // Remove backslash
        $this->mailer->Password = SMTP_PASSWORD;   // Remove backslash
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = SMTP_PORT;          // Remove backslash
    }

    public function sendConfirmationEmail($email, $name, $token)
    {
        try {
            $this->mailer->setFrom(APP_EMAIL, APP_NAME);     // Remove backslash
            $this->mailer->addAddress($email, $name);
            $this->mailer->isHTML(true);

            $confirmationLink = APP_URL . "/verify-email?token=" . $token;  // Remove backslash

            $this->mailer->Subject = 'Confirm Your Email - ' . APP_NAME;    // Remove backslash
            $this->mailer->Body = "
                <h2>Welcome to " . APP_NAME . "!</h2>
                <p>Hi {$name},</p>
                <p>Please click the link below to confirm your email address:</p>
                <p><a href='{$confirmationLink}'>Confirm Email</a></p>
                <p>If you didn't create this account, you can ignore this email.</p>
            ";

            return $this->mailer->send();
        } catch (Exception $e) {
            error_log("Email sending failed: " . $this->mailer->ErrorInfo);
            return false;
        }
    }
}
