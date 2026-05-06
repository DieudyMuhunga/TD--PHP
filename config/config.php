<?php
// CONFIGURATION GLOBALE


// Activer l'affichage des erreurs en développement
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Encodage UTF-8
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');

// Fuseau horaire
date_default_timezone_set('Europe/Paris');

// Créer les répertoires s'ils n'existent pas
if (!is_dir(DATA_PATH)) {
    mkdir(DATA_PATH, 0755, true);
}
if (!is_dir(UPLOADS_PATH)) {
    mkdir(UPLOADS_PATH, 0755, true);
}

?>