<?php
require_once __DIR__ . '/../init.php';
require_once __DIR__ . "/../back/MailService.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    try {
        $nameVisitor = $_POST['firstname'] . " " . $_POST['lastname'];
        $emailVisitor = $_POST['email'];
        $phoneVisitor = $_POST['phone'];
        $messageVisitor = $_POST['message'] .
            "\n\nNom: $nameVisitor \nEmail: $emailVisitor \nTéléphone: $phoneVisitor";

        $mailer = new MailService();
        $ok = $mailer->sendContact($nameVisitor, $emailVisitor, nl2br($messageVisitor));

        $_SESSION[$ok ? 'success_message' : 'error_message'] =
            $ok ? "Votre message a été envoyé avec succès" : "Une erreur est survenue lors de l'envoi";
        header('Location: ../index.php');
        exit;
    } catch (Exception $e) {
        error_log($e->getMessage());
        $_SESSION['error_message'] = "Une erreur est survenue";
        header('Location: ../index.php');
        exit;
    }
}
