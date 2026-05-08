<?php

// Système de déconnexion en PHP procédural

// Démarrer la session
session_start();

// Supprimer toutes les variables de session
$_SESSION = [];

// Détruire la session
session_destroy();

// Rediriger vers login.php
header('Location: login.php');
exit;

?>