<?php

require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class MailerController {
    private $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);

        // Server settings
        $this->mail->isSMTP();                                              // Send using SMTP
        $this->mail->Host       = $_ENV['SMTP_HOST'];             // Set the SMTP server to send through
        $this->mail->SMTPAuth   = true;                                     // Enable SMTP authentication
        $this->mail->Username   = $_ENV['SMTP_USER'];                 // SMTP username
        $this->mail->Password   = $_ENV['SMTP_PASS'];                               // SMTP password
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            // Enable TLS encryption
        $this->mail->Port       = 25;                                      // TCP port to connect to, use 587 for 'PHPMailer::ENCRYPTION_STARTTLS'.
        $this->mail->CharSet = 'UTF-8';
    }


    public function generateWelcomeEmailBody($username) {
        $body = "<h1>Bem-vindo, " . htmlspecialchars($username) . "!</h1>";
        $body .= "<p>Obrigado por te registrares na Liga-Padel. Esperamos que aproveites o nosso serviço.</p> ";

        $body .= "<p>Na Liga-Padel, podes criar ou juntar-te a outras ligas de Padel, comunicar com outros utilizadores, marcar jogos, acompanhar a tua progressão e muito mais. É a plataforma perfeita para os amantes de Padel que querem encontrar uma forma fácil e divertida de organizar e participar em ligas.</p>";

        $body .= "<p>Se tiveres dúvidas sobre como funciona a Liga-Padel, visita o nosso <a href='https://liga-padel.pt/faq'>FAQ</a>.</p>";

        $body .= "<p>Estamos ansiosos para te ver em campo!</p>";

        return $body;
    }



    public function sendWelcomeEmail($to, $username) {
        $subject = "Bem-vindo à Liga-Padel!";
        $body = $this->generateWelcomeEmailBody($username);
        $this->sendEmail($to, $subject, $body);
    }

    public function sendEmail($to, $subject, $body)
    {
        $mail = $this->mail;

        try {
            //Server settings
            $mail->SMTPDebug = 2; // Enable verbose debug output
            $mail->isSMTP(); // Send using SMTP
            $mail->Host = $this->mail->Host; // Set the SMTP server to send through
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = $this->mail->Username; // SMTP username
            $mail->Password = $this->mail->Password; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption;
            $mail->Port = $this->mail->Port; // TCP port to connect to

            //Recipients
            $mail->setFrom('admin@liga-padel.pt', 'Liga de Padel');
            $mail->addAddress($to); // Add a recipient

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->Timeout = 10; // Timeout in seconds

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }

    public function sendPasswordResetEmail($to, $resetLink) {
        $subject = "Redefinição de Senha - Liga-Padel";
        $body = $this->generateResetEmailBody($resetLink);
        $this->sendEmail($to, $subject, $body);
    }

    private function generateResetEmailBody($resetLink) {
        $body = "<h1>Redefinição de Senha</h1>";
        $body .= "<p>Recebemos um pedido para redefinir a tua senha na Liga-Padel.</p>";
        $body .= "<p>Clica no link abaixo para redefinires a tua senha:</p>";
        $body .= "<a href='" . htmlspecialchars($resetLink) . "'>Redefinir Senha</a>";
        $body .= "<p>Se não solicitaste uma redefinição de senha, por favor ignora este e-mail.</p>";
        return $body;
    }
    public function sendLeagueInvitationEmail($to, $leagueName, $invitationLink) {
        $subject = "Convite para {$leagueName} - Liga-Padel";
        $body = $this->generateLeagueInvitationEmailBody($leagueName, $invitationLink);
        return $this->sendEmail($to, $subject, $body);
    }

    private function generateLeagueInvitationEmailBody($leagueName, $invitationLink) {
        $body = "<h1>Convite</h1>";
        $body .= "<p>Foste convidado para se juntar à liga " . htmlspecialchars($leagueName) . " na Liga-Padel.</p>";
        $body .= "<p>Clica no link abaixo para aceitares o convite:</p>";
        $body .= "<a href='" . htmlspecialchars($invitationLink) . "'>Aceitar Convite</a>";
        $body .= "<p>Se não esperavas este convite, por favor ignora este e-mail.</p>";
        return $body;
    }

}
