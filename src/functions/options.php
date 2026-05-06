<?php
// ============================================================================
// FONCTIONS GESTION DES OPTIONS
// ============================================================================

/**
 * Charge les options depuis le fichier JSON
 * @param string $chemin_fichier
 * @return array
 */
function charger_options($chemin_fichier = FILE_OPTIONS) {
    if (!file_exists($chemin_fichier)) {
        return [];
    }
    
    $options = lire_json($chemin_fichier);
    return $options ?: [];
}

/**
 * Sauvegarde les options
 * @param array $options
 * @param string $chemin_fichier
 * @return bool
 */
function sauvegarder_options($options, $chemin_fichier = FILE_OPTIONS) {
    return ecrire_json($chemin_fichier, $options);
}

/**
 * Ajoute une nouvelle option
 * @param string $id
 * @param string $libelle
 * @param string $id_promotion
 * @param int $effectif
 * @return bool
 */
function ajouter_option($id, $libelle, $id_promotion, $effectif) {
    if (!in_array($id_promotion, PROMOTIONS_OPTIONS)) {
        return false;
    }
    
    $options = charger_options();
    
    // Vérifier l'unicité
    foreach ($options as $opt) {
        if ($opt['id'] === $id) {
            return false;
        }
    }
    
    $nouvelle_option = [
        'id' => sanitize_input($id),
        'libelle' => sanitize_input($libelle),
        'promotion_parente' => $id_promotion,
        'effectif' => intval($effectif),
        'date_creation' => date('Y-m-d H:i:s')
    ];
    
    $options[] = $nouvelle_option;
    return sauvegarder_options($options);
}

/**
 * Supprime une option
 * @param string $id_option
 * @return bool
 */
function supprimer_option($id_option) {
    $options = charger_options();
    $nouvelles_options = [];
    $trouve = false;
    
    foreach ($options as $opt) {
        if ($opt['id'] !== $id_option) {
            $nouvelles_options[] = $opt;
        } else {
            $trouve = true;
        }
    }
    
    return $trouve ? sauvegarder_options($nouvelles_options) : false;
}

/**
 * Met à jour une option
 * @param string $id
 * @param string $libelle
 * @param int $effectif
 * @return bool
 */
function mettre_a_jour_option($id, $libelle, $effectif) {
    $options = charger_options();
    $trouve = false;
    
    foreach ($options as &$opt) {
        if ($opt['id'] === $id) {
            $opt['libelle'] = sanitize_input($libelle);
            $opt['effectif'] = intval($effectif);
            $opt['date_modification'] = date('Y-m-d H:i:s');
            $trouve = true;
            break;
        }
    }
    
    return $trouve ? sauvegarder_options($options) : false;
}

/**
 * Obtient une option par ID
 * @param string $id_option
 * @return array|null
 */
function obtenir_option($id_option) {
    $options = charger_options();
    
    foreach ($options as $opt) {
        if ($opt['id'] === $id_option) {
            return $opt;
        }
    }
    
    return null;
}

/**
 * Obtient les options d'une promotion
 * @param string $id_promotion
 * @return array
 */
function obtenir_options_promotion($id_promotion) {
    $options = charger_options();
    $resultats = [];
    
    foreach ($options as $opt) {
        if ($opt['promotion_parente'] === $id_promotion) {
            $resultats[] = $opt;
        }
    }
    
    return $resultats;
}

/**
 * Obtient le nombre total d'options
 * @return int
 */
function compter_options() {
    return count(charger_options());
}

?>