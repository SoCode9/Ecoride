<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    try {
        $nameVisitor = $_POST['firstname'] . " " . $_POST['lastname'];
        $emailVisitor = $_POST['email'];
        $phoneVisitor = $_POST['phone'];
        $messageVisitor = $_POST['message'] .
            "\n\nNom: $nameVisitor \nEmail: $emailVisitor \nTéléphone: $phoneVisitor";

        mail("info@ecoride.fr", "Formulaire de contact complété par $nameVisitor", $messageVisitor, "From: $emailVisitor");
        header('Location: ../index.php');
        $_SESSION['success_message'] = "Votre message a été envoyé avec succès";
        exit;
    } catch (Exception $e) {
        header('Location: ../index.php');
        $_SESSION['error_message'] = "Une erreur est survenue";
        exit;
    }
}
