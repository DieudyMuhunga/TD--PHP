<?php

// Système de vérification de session en PHP procédural

// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    // Rediriger vers login.php si non connecté
    header('Location: login.php');
    exit;
}

?>