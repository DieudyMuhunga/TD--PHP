<?php
// CONSTANTES GLOBALES DU SYSTÈME

// Informations de l'application
define('APP_NAME', 'Système de Gestion des Auditoires (SGA)');
define('APP_VERSION', '1.0.0');
define('APP_AUTHOR', 'Faculté des Sciences Informatiques');

// Chemins des fichiers de données
define('DATA_PATH', __DIR__ . '/../data/');
define('UPLOADS_PATH', __DIR__ . '/../uploads/');

define('FILE_SALLES', DATA_PATH . 'salles.json');
define('FILE_PROMOTIONS', DATA_PATH . 'promotions.json');
define('FILE_COURS', DATA_PATH . 'cours.json');
define('FILE_OPTIONS', DATA_PATH . 'options.json');
define('FILE_PLANNING', DATA_PATH . 'plannig.json');
define('FILE_RAPPORT', UPLOADS_PATH . 'rapport_occupation.txt');

// Paramètres pédagogiques
define('JOURS_SEMAINE', ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi']);
define('HEURE_DEBUT', 8);
define('HEURE_FIN', 17);
define('DUREE_CRENEAU', 4); // en heures
define('HEURES_CRENEAUX', ['08:00-12:00', '12:00-16:00', '16:00-17:00']);

// Promotions
define('PROMOTIONS_VALIDES', ['L1', 'L2', 'L3', 'L4']);
define('PROMOTIONS_OPTIONS', ['L3', 'L4']);

// Erreurs
define('ERROR_FICHIER_NOT_FOUND', 'Le fichier n\'existe pas');
define('ERROR_INVALID_JSON', 'Format JSON invalide');
define('ERROR_CAPACITE_INSUFFISANTE', 'La capacité de la salle est insuffisante');
define('ERROR_SALLE_OCCUPEE', 'La salle est déjà occupée à ce créneau');
define('ERROR_GROUPE_OCCUPE', 'Le groupe a déjà un cours à ce créneau');

?>