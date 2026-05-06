<?php
// ============================================================================
// FONCTIONS UTILITAIRES
// ============================================================================

/**
 * Nettoie et valide une chaîne d'entrée utilisateur
 * @param string $input
 * @return string
 */
function sanitize_input($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Valide une adresse email
 * @param string $email
 * @return bool
 */
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Valide un nombre entier positif
 * @param mixed $value
 * @return bool
 */
function validate_integer($value) {
    return is_numeric($value) && intval($value) > 0 && intval($value) == $value;
}

/**
 * Formate une date au format français
 * @param string $date
 * @return string
 */
function format_date_fr($date) {
    $jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];
    $mois = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
    
    $timestamp = strtotime($date);
    $jour = $jours[date('N', $timestamp) - 1];
    $jour_num = date('d', $timestamp);
    $mois_num = date('n', $timestamp) - 1;
    $annee = date('Y', $timestamp);
    
    return ucfirst($jour) . ' ' . $jour_num . ' ' . $mois[$mois_num] . ' ' . $annee;
}

/**
 * Vérifie si un fichier JSON est valide
 * @param string $chemin_fichier
 * @return bool
 */
function is_valid_json_file($chemin_fichier) {
    if (!file_exists($chemin_fichier)) {
        return false;
    }
    
    $contenu = file_get_contents($chemin_fichier);
    json_decode($contenu);
    return json_last_error() === JSON_ERROR_NONE;
}

/**
 * Lit un fichier JSON
 * @param string $chemin_fichier
 * @return array|false
 */
function lire_json($chemin_fichier) {
    if (!file_exists($chemin_fichier)) {
        return false;
    }
    
    $contenu = file_get_contents($chemin_fichier);
    $donnees = json_decode($contenu, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        return false;
    }
    
    return $donnees;
}

/**
 * Écrit un fichier JSON
 * @param string $chemin_fichier
 * @param array $donnees
 * @return bool
 */
function ecrire_json($chemin_fichier, $donnees) {
    $json = json_encode($donnees, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
    if ($json === false) {
        return false;
    }
    
    return file_put_contents($chemin_fichier, $json) !== false;
}

/**
 * Génère un ID unique
 * @param string $prefixe
 * @return string
 */
function generer_id($prefixe = '') {
    return $prefixe . '-' . uniqid() . '-' . rand(1000, 9999);
}

/**
 * Convertit un texte en slug (URL-friendly)
 * @param string $texte
 * @return string
 */
function texte_en_slug($texte) {
    $texte = strtolower($texte);
    $texte = preg_replace('/[^a-z0-9]+/', '-', $texte);
    return trim($texte, '-');
}

/**
 * Affiche un tableau HTML formaté
 * @param array $donnees
 * @param array $colonnes
 * @return string
 */
function generer_tableau_html($donnees, $colonnes) {
    if (empty($donnees)) {
        return '<p class="text-center text-muted">Aucune donnée disponible</p>';
    }
    
    $html = '<div class="table-responsive"><table class="table table-striped table-hover">';
    
    // En-têtes
    $html .= '<thead class="table-dark"><tr>';
    foreach ($colonnes as $colonne) {
        $html .= '<th>' . htmlspecialchars($colonne) . '</th>';
    }
    $html .= '</tr></thead>';
    
    // Corps
    $html .= '<tbody>';
    foreach ($donnees as $ligne) {
        $html .= '<tr>';
        foreach (array_keys($colonnes) as $cle) {
            $valeur = isset($ligne[$cle]) ? $ligne[$cle] : '-';
            $html .= '<td>' . htmlspecialchars($valeur) . '</td>';
        }
        $html .= '</tr>';
    }
    $html .= '</tbody></table></div>';
    
    return $html;
}

/**
 * Obtient le pourcentage
 * @param int $partie
 * @param int $total
 * @return float
 */
function calculer_pourcentage($partie, $total) {
    if ($total == 0) return 0;
    return round(($partie / $total) * 100, 2);
}

?>