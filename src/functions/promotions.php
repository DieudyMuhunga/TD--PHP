<?php

// FONCTIONS GESTION DES PROMOTIONS

/**
 * Charge les promotions depuis le fichier JSON
 * @param string $chemin_fichier
 * @return array
 */
function charger_promotions($chemin_fichier = FILE_PROMOTIONS) {
    if (!file_exists($chemin_fichier)) {
        return [];
    }
    
    $promotions = lire_json($chemin_fichier);
    return $promotions ?: [];
}

/**
 * Sauvegarde les promotions
 * @param array $promotions
 * @param string $chemin_fichier
 * @return bool
 */
function sauvegarder_promotions($promotions, $chemin_fichier = FILE_PROMOTIONS) {
    return ecrire_json($chemin_fichier, $promotions);
}

/**
 * Ajoute une nouvelle promotion
 * @param string $id
 * @param string $libelle
 * @param int $effectif
 * @return bool
 */
function ajouter_promotion($id, $libelle, $effectif) {
    if (!in_array($id, PROMOTIONS_VALIDES)) {
        return false;
    }
    
    $promotions = charger_promotions();
    
    // Vérifier l'unicité
    foreach ($promotions as $promo) {
        if ($promo['id'] === $id) {
            return false;
        }
    }
    
    $nouvelle_promo = [
        'id' => $id,
        'libelle' => sanitize_input($libelle),
        'effectif' => intval($effectif),
        'date_creation' => date('Y-m-d H:i:s')
    ];
    
    $promotions[] = $nouvelle_promo;
    return sauvegarder_promotions($promotions);
}

/**
 * Supprime une promotion
 * @param string $id_promo
 * @return bool
 */
function supprimer_promotion($id_promo) {
    $promotions = charger_promotions();
    $nouvelles_promos = [];
    $trouve = false;
    
    foreach ($promotions as $promo) {
        if ($promo['id'] !== $id_promo) {
            $nouvelles_promos[] = $promo;
        } else {
            $trouve = true;
        }
    }
    
    return $trouve ? sauvegarder_promotions($nouvelles_promos) : false;
}

/**
 * Met à jour une promotion
 * @param string $id
 * @param string $libelle
 * @param int $effectif
 * @return bool
 */
function mettre_a_jour_promotion($id, $libelle, $effectif) {
    $promotions = charger_promotions();
    $trouve = false;
    
    foreach ($promotions as &$promo) {
        if ($promo['id'] === $id) {
            $promo['libelle'] = sanitize_input($libelle);
            $promo['effectif'] = intval($effectif);
            $promo['date_modification'] = date('Y-m-d H:i:s');
            $trouve = true;
            break;
        }
    }
    
    return $trouve ? sauvegarder_promotions($promotions) : false;
}

/**
 * Obtient une promotion par ID
 * @param string $id_promo
 * @return array|null
 */
function obtenir_promotion($id_promo) {
    $promotions = charger_promotions();
    
    foreach ($promotions as $promo) {
        if ($promo['id'] === $id_promo) {
            return $promo;
        }
    }
    
    return null;
}

/**
 * Obtient le nombre total de promotions
 * @return int
 */
function compter_promotions() {
    return count(charger_promotions());
}

?>