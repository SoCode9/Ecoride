<?php

session_start();
$_SESSION = []; 
session_destroy();
echo json_encode(["status" => "success", "message" => "Déconnexion réussie"]);
exit();