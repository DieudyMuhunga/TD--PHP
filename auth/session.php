<?php
// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté
if(!isset($_SESSION['username'])) { 
    // Rediriger vers login.php si non connecté
    header('Location: auth/login.php');
    exit;
}

?>