<?php
// FONCTIONS GESTION DES SALLES

/**
 * Charge les salles depuis le fichier JSON
 * @param string $chemin_fichier
 * @return array
 */
function charger_salles($chemin_fichier = FILE_SALLES) {
    if (!file_exists($chemin_fichier)) {
        return [];
    }
    
    $salles = lire_json($chemin_fichier);
    return $salles ?: [];
}

/**
 * Sauvegarde les salles dans le fichier JSON
 * @param array $salles
 * @param string $chemin_fichier
 * @return bool
 */
function sauvegarder_salles($salles, $chemin_fichier = FILE_SALLES) {
    return ecrire_json($chemin_fichier, $salles);
}

/**
 * Ajoute une nouvelle salle
 * @param string $id
 * @param string $designation
 * @param int $capacite
 * @return bool
 */
function ajouter_salle($id, $designation, $capacite) {
    $salles = charger_salles();
    
    // Vérifier que l'ID n'existe pas déjà
    foreach ($salles as $salle) {
        if ($salle['id'] === $id) {
            return false; // Salle existe déjà
        }
    }
    
    $nouvelle_salle = [
        'id' => sanitize_input($id),
        'designation' => sanitize_input($designation),
        'capacite' => intval($capacite),
        'date_creation' => date('Y-m-d H:i:s')
    ];
    
    $salles[] = $nouvelle_salle;
    return sauvegarder_salles($salles);
}

/**
 * Supprime une salle
 * @param string $id_salle
 * @return bool
 */
function supprimer_salle($id_salle) {
    $salles = charger_salles();
    $nouvelles_salles = [];
    $trouve = false;
    
    foreach ($salles as $salle) {
        if ($salle['id'] !== $id_salle) {
            $nouvelles_salles[] = $salle;
        } else {
            $trouve = true;
        }
    }
    
    if (!$trouve) return false;
    
    return sauvegarder_salles($nouvelles_salles);
}

/**
 * Met à jour une salle
 * @param string $id
 * @param string $designation
 * @param int $capacite
 * @return bool
 */
function mettre_a_jour_salle($id, $designation, $capacite) {
    $salles = charger_salles();
    $trouve = false;
    
    foreach ($salles as &$salle) {
        if ($salle['id'] === $id) {
            $salle['designation'] = sanitize_input($designation);
            $salle['capacite'] = intval($capacite);
            $salle['date_modification'] = date('Y-m-d H:i:s');
            $trouve = true;
            break;
        }
    }
    
    if (!$trouve) return false;
    
    return sauvegarder_salles($salles);
}

/**
 * Obtient une salle par ID
 * @param string $id_salle
 * @return array|null
 */
function obtenir_salle($id_salle) {
    $salles = charger_salles();
    
    foreach ($salles as $salle) {
        if ($salle['id'] === $id_salle) {
            return $salle;
        }
    }
    
    return null;
}

/**
 * Obtient le nombre total de salles
 * @return int
 */
function compter_salles() {
    return count(charger_salles());
}

?>