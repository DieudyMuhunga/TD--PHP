<?php
// FONCTIONS GESTION DES COURS

/**
 * Charge les cours depuis le fichier JSON
 * @param string $chemin_fichier
 * @return array
 */
function charger_cours($chemin_fichier = FILE_COURS) {
    if (!file_exists($chemin_fichier)) {
        return [];
    }
    
    $cours = lire_json($chemin_fichier);
    return $cours ?: [];
}

/**
 * Sauvegarde les cours
 * @param array $cours
 * @param string $chemin_fichier
 * @return bool
 */
function sauvegarder_cours($cours, $chemin_fichier = FILE_COURS) {
    return ecrire_json($chemin_fichier, $cours);
}

/**
 * Ajoute un nouveau cours
 * @param string $id
 * @param string $intitule
 * @param int $volume_horaire
 * @param string $type (tronc_commun ou option)
 * @param string $promotion_ou_option
 * @return bool
 */
function ajouter_cours($id, $intitule, $volume_horaire, $type, $promotion_ou_option) {
    $cours = charger_cours();
    
    // Vérifier l'unicité
    foreach ($cours as $c) {
        if ($c['id'] === $id) {
            return false;
        }
    }
    
    $nouveau_cours = [
        'id' => sanitize_input($id),
        'intitule' => sanitize_input($intitule),
        'volume_horaire' => intval($volume_horaire),
        'type' => sanitize_input($type),
        'promotion_ou_option' => sanitize_input($promotion_ou_option),
        'date_creation' => date('Y-m-d H:i:s')
    ];
    
    $cours[] = $nouveau_cours;
    return sauvegarder_cours($cours);
}

/**
 * Supprime un cours
 * @param string $id_cours
 * @return bool
 */
function supprimer_cours($id_cours) {
    $cours = charger_cours();
    $nouveaux_cours = [];
    $trouve = false;
    
    foreach ($cours as $c) {
        if ($c['id'] !== $id_cours) {
            $nouveaux_cours[] = $c;
        } else {
            $trouve = true;
        }
    }
    
    return $trouve ? sauvegarder_cours($nouveaux_cours) : false;
}

/**
 * Met à jour un cours
 * @param string $id
 * @param string $intitule
 * @param int $volume_horaire
 * @param string $type
 * @param string $promotion_ou_option
 * @return bool
 */
function mettre_a_jour_cours($id, $intitule, $volume_horaire, $type, $promotion_ou_option) {
    $cours = charger_cours();
    $trouve = false;
    
    foreach ($cours as &$c) {
        if ($c['id'] === $id) {
            $c['intitule'] = sanitize_input($intitule);
            $c['volume_horaire'] = intval($volume_horaire);
            $c['type'] = sanitize_input($type);
            $c['promotion_ou_option'] = sanitize_input($promotion_ou_option);
            $c['date_modification'] = date('Y-m-d H:i:s');
            $trouve = true;
            break;
        }
    }
    
    return $trouve ? sauvegarder_cours($cours) : false;
}

/**
 * Obtient un cours par ID
 * @param string $id_cours
 * @return array|null
 */
function obtenir_cours($id_cours) {
    $cours = charger_cours();
    
    foreach ($cours as $c) {
        if ($c['id'] === $id_cours) {
            return $c;
        }
    }
    
    return null;
}

/**
 * Obtient les cours d'une promotion
 * @param string $id_promotion
 * @return array
 */
function obtenir_cours_promotion($id_promotion) {
    $cours = charger_cours();
    $resultats = [];
    
    foreach ($cours as $c) {
        if ($c['type'] === 'tronc_commun' && $c['promotion_ou_option'] === $id_promotion) {
            $resultats[] = $c;
        }
    }
    
    return $resultats;
}

/**
 * Obtient le nombre total de cours
 * @return int
 */
function compter_cours() {
    return count(charger_cours());
}

?>