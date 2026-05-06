<?php
// ============================================================================
// FONCTIONS DE VALIDATION DES CONTRAINTES MÉTIER
// ============================================================================

/**
 * Vérifie si une salle est disponible à un créneau
 * @param array $planning
 * @param string $id_salle
 * @param string $creneau (format: "jour-heure_debut-heure_fin")
 * @return bool
 */
function salle_disponible($planning, $id_salle, $creneau) {
    foreach ($planning as $cours_planifie) {
        if ($cours_planifie['id_salle'] === $id_salle && $cours_planifie['creneau'] === $creneau) {
            return false;
        }
    }
    return true;
}

/**
 * Vérifie si la capacité d'une salle est suffisante
 * @param array $salles
 * @param string $id_salle
 * @param int $effectif
 * @return bool
 */
function capacite_suffisante($salles, $id_salle, $effectif) {
    foreach ($salles as $salle) {
        if ($salle['id'] === $id_salle) {
            return $salle['capacite'] >= $effectif;
        }
    }
    return false;
}

/**
 * Vérifie si un groupe est libre à un créneau
 * @param array $planning
 * @param string $id_groupe
 * @param string $creneau
 * @return bool
 */
function creneau_libre_groupe($planning, $id_groupe, $creneau) {
    foreach ($planning as $cours_planifie) {
        if ($cours_planifie['id_groupe'] === $id_groupe && $cours_planifie['creneau'] === $creneau) {
            return false;
        }
    }
    return true;
}

/**
 * Valide une affectation de cours
 * @param array $salles
 * @param array $planning
 * @param string $id_salle
 * @param string $id_groupe
 * @param string $creneau
 * @param int $effectif
 * @return array ['valid' => bool, 'message' => string]
 */
function valider_affectation($salles, $planning, $id_salle, $id_groupe, $creneau, $effectif) {
    // Vérifier la capacité
    if (!capacite_suffisante($salles, $id_salle, $effectif)) {
        return [
            'valid' => false,
            'message' => 'La capacité de la salle est insuffisante pour le groupe'
        ];
    }
    
    // Vérifier la disponibilité de la salle
    if (!salle_disponible($planning, $id_salle, $creneau)) {
        return [
            'valid' => false,
            'message' => 'La salle est déjà occupée à ce créneau'
        ];
    }
    
    // Vérifier que le groupe est libre
    if (!creneau_libre_groupe($planning, $id_groupe, $creneau)) {
        return [
            'valid' => false,
            'message' => 'Le groupe a déjà un cours à ce créneau'
        ];
    }
    
    return ['valid' => true, 'message' => 'Affectation valide'];
}

/**
 * Détecte les conflits dans le planning
 * @param array $planning
 * @return array
 */
function detecter_conflits($planning) {
    $conflits = [];
    
    // Conflits de salles
    $salles_par_creneau = [];
    foreach ($planning as $entry) {
        $creneau = $entry['creneau'];
        if (!isset($salles_par_creneau[$creneau])) {
            $salles_par_creneau[$creneau] = [];
        }
        
        if (isset($salles_par_creneau[$creneau][$entry['id_salle']])) {
            $conflits[] = [
                'type' => 'Conflit de salle',
                'detail' => "Salle {$entry['id_salle']} occupée plusieurs fois à {$creneau}"
            ];
        }
        $salles_par_creneau[$creneau][$entry['id_salle']] = true;
    }
    
    // Conflits de groupes
    $groupes_par_creneau = [];
    foreach ($planning as $entry) {
        $creneau = $entry['creneau'];
        if (!isset($groupes_par_creneau[$creneau])) {
            $groupes_par_creneau[$creneau] = [];
        }
        
        if (isset($groupes_par_creneau[$creneau][$entry['id_groupe']])) {
            $conflits[] = [
                'type' => 'Conflit de groupe',
                'detail' => "Groupe {$entry['id_groupe']} assigné plusieurs fois à {$creneau}"
            ];
        }
        $groupes_par_creneau[$creneau][$entry['id_groupe']] = true;
    }
    
    return $conflits;
}

?>