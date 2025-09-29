<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require_once __DIR__ . '/../vendor/autoload.php';

class MailService
{
    private PHPMailer $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);

        // Debug en dev : va dans error_log d'Apache
        $this->mailer->SMTPDebug = SMTP::DEBUG_OFF; // passe à DEBUG_SERVER si besoin
        $this->mailer->Debugoutput = 'error_log';

        $this->mailer->isSMTP();
        $this->mailer->Host       = $_ENV['MAIL_HOST'] ?? 'mailpit';
        $this->mailer->Port       = (int)($_ENV['MAIL_PORT'] ?? 1025);
        $this->mailer->SMTPAuth   = !empty($_ENV['MAIL_USER']);
        if ($this->mailer->SMTPAuth) {
            $this->mailer->Username = $_ENV['MAIL_USER'];
            $this->mailer->Password = $_ENV['MAIL_PASS'] ?? '';
        }
        $secure = $_ENV['MAIL_SECURE'] ?? '';
        if ($secure === 'tls') $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        if ($secure === 'ssl') $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

        $from = $_ENV['MAIL_FROM'] ?? 'no-reply@ecoride.local';
        $fromName = $_ENV['MAIL_FROM_NAME'] ?? 'EcoRide';
        $this->mailer->setFrom($from, $fromName);

        $this->mailer->CharSet = 'UTF-8';
        $this->mailer->isHTML(true);
    }

    public function send(string $to, string $subject, string $htmlBody, ?string $replyToEmail = null, ?string $replyToName = null): bool
    {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->clearReplyTos();
            $this->mailer->addAddress($to);
            if ($replyToEmail) $this->mailer->addReplyTo($replyToEmail, $replyToName ?? $replyToEmail);

            $this->mailer->Subject = $subject;
            $this->mailer->Body    = $htmlBody;
            $this->mailer->AltBody = strip_tags($htmlBody);

            return $this->mailer->send();
        } catch (\Throwable $e) {
            error_log('Mail send failed: ' . $e->getMessage());
            return false;
        }
    }

    public function sendContact(string $nameVisitor, string $emailVisitor, string $messageVisitor): bool
    {
        $subject = "Formulaire de contact complété par {$nameVisitor}";
        $html = nl2br($messageVisitor);
        return $this->send('info@ecoride.fr', $subject, $html, $emailVisitor, $nameVisitor);
    }
}
