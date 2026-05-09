<?php
// Inclure le fichier de vérification de session
require_once 'auth/session.php';

// Inclusion des configurations et fonctions
require_once 'config/constants.php';
require_once 'config/config.php';

// Inclusion des fonctions métier
require_once 'src/functions/utils.php';
require_once 'src/functions/validation.php';
require_once 'src/functions/salles.php';
require_once 'src/functions/promotions.php';
require_once 'src/functions/cours.php';
require_once 'src/functions/options.php';
require_once 'src/functions/planning.php';

// Déterminer la page à afficher
$page = isset($_GET['page']) ? sanitize_input($_GET['page']) : 'dashboard';

// Messages de session
$success = isset($_SESSION['success']) ? $_SESSION['success'] : '';
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['success']);
unset($_SESSION['error']);

// Routes disponibles
$pages_disponibles = [
    'dashboard' => 'src/pages.php/dashboard.php',
    'gestion_salles' => 'src/pages.php/gestion_salles.php',
    'gestion_promotions' => 'src/pages.php/gestion_promotions.php',
    'gestion_cours' => 'src/pages.php/gestion.cours.php',
    'gestion_options' => 'src/pages.php/gestion_option.php',
    'generer_planning' => 'src/pages.php/generer_plannig.php',
    'visualiser_planning' => 'src/pages.php/visualiser_planning.php',
    'rapports' => 'src/pages.php/rapports.php',
];

// Vérifier que la page existe
if (!array_key_exists($page, $pages_disponibles)) {
    $page = 'dashboard';
}

// Inclure le header
require_once 'src/includes/header.php';

// Afficher les messages
if ($success) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
    echo htmlspecialchars($success);
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
}

if ($error) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
    echo htmlspecialchars($error);
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
}

// Inclure la page demandée
require_once $pages_disponibles[$page];

// Inclure le footer
require_once 'src/footer.php';
?>