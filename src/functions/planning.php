<?php
// ============================================================================
// FONCTIONS GESTION DU PLANNING
// ============================================================================

/**
 * Génère les crénaux horaires disponibles
 * @return array
 */
function generer_creneaux() {
    $creneaux = [];
    $jours = JOURS_SEMAINE;
    $heures_debut = [8, 12, 16];
    $heures_fin = [12, 16, 17];
    
    foreach ($jours as $idx => $jour) {
        for ($i = 0; $i < count($heures_debut); $i++) {
            $creneau = sprintf("%d-%02d-%02d", $idx, $heures_debut[$i], $heures_fin[$i]);
            $creneaux[] = [
                'id' => $creneau,
                'jour' => $jour,
                'heure_debut' => sprintf("%02d:00", $heures_debut[$i]),
                'heure_fin' => sprintf("%02d:00", $heures_fin[$i]),
                'libelle' => "$jour {$heures_debut[$i]}h-{$heures_fin[$i]}h"
            ];
        }
    }
    
    return $creneaux;
}

/**
 * Charge le planning depuis le fichier JSON
 * @param string $chemin_fichier
 * @return array
 */
function charger_planning($chemin_fichier = FILE_PLANNING) {
    if (!file_exists($chemin_fichier)) {
        return [];
    }
    
    $planning = lire_json($chemin_fichier);
    return $planning ?: [];
}

/**
 * Sauvegarde le planning dans le fichier JSON
 * @param array $planning
 * @param string $chemin_fichier
 * @return bool
 */
function sauvegarder_planning($planning, $chemin_fichier = FILE_PLANNING) {
    return ecrire_json($chemin_fichier, $planning);
}

/**
 * Ajoute une entrée au planning
 * @param array $planning
 * @param string $creneau
 * @param string $id_salle
 * @param string $id_cours
 * @param string $id_groupe
 * @param int $effectif
 * @return array|false
 */
function ajouter_au_planning(&$planning, $creneau, $id_salle, $id_cours, $id_groupe, $effectif) {
    $salles = charger_salles();
    
    // Valider l'affectation
    $validation = valider_affectation($salles, $planning, $id_salle, $id_groupe, $creneau, $effectif);
    
    if (!$validation['valid']) {
        return false;
    }
    
    // Ajouter au planning
    $entree = [
        'id' => uniqid('plan_'),
        'creneau' => $creneau,
        'id_salle' => $id_salle,
        'id_cours' => $id_cours,
        'id_groupe' => $id_groupe,
        'effectif' => $effectif,
        'date_creation' => date('Y-m-d H:i:s')
    ];
    
    $planning[] = $entree;
    return $entree;
}

/**
 * Génère le planning automatiquement
 * @return array
 */
function generer_planning_automatique() {
    $salles = charger_salles();
    $promotions = charger_promotions();
    $cours = charger_cours();
    $options = charger_options();
    $creneaux = generer_creneaux();
    
    $planning = [];
    $creneaux_utilises = [];
    
    // Trier les cours par volume horaire décroissant
    usort($cours, function($a, $b) {
        return $b['volume_horaire'] - $a['volume_horaire'];
    });
    
    // Traiter chaque cours
    foreach ($cours as $c) {
        $volume_horaire = $c['volume_horaire'];
        $creneaux_necessaires = ceil($volume_horaire / 4);
        
        if ($c['type'] === 'tronc_commun') {
            // Cours de tronc commun : affecter toute la promotion
            $promo = obtenir_promotion($c['promotion_ou_option']);
            if (!$promo) continue;
            
            $effectif = $promo['effectif'];
            $id_groupe = 'groupe_' . $promo['id'];
            
            // Trouver des crénaux libres
            $creneaux_affectes = 0;
            foreach ($creneaux as $creneau) {
                if ($creneaux_affectes >= $creneaux_necessaires) break;
                
                // Trouver la meilleure salle
                foreach ($salles as $salle) {
                    if (salle_disponible($planning, $salle['id'], $creneau['id']) &&
                        capacite_suffisante($salles, $salle['id'], $effectif) &&
                        creneau_libre_groupe($planning, $id_groupe, $creneau['id'])) {
                        
                        ajouter_au_planning($planning, $creneau['id'], $salle['id'], 
                                          $c['id'], $id_groupe, $effectif);
                        $creneaux_affectes++;
                        break;
                    }
                }
            }
        } else {
            // Cours d'option
            $option = obtenir_option($c['promotion_ou_option']);
            if (!$option) continue;
            
            $effectif = $option['effectif'];
            $id_groupe = 'groupe_' . $option['id'];
            
            // Trouver des crénaux libres
            $creneaux_affectes = 0;
            foreach ($creneaux as $creneau) {
                if ($creneaux_affectes >= $creneaux_necessaires) break;
                
                // Trouver la meilleure salle
                foreach ($salles as $salle) {
                    if (salle_disponible($planning, $salle['id'], $creneau['id']) &&
                        capacite_suffisante($salles, $salle['id'], $effectif) &&
                        creneau_libre_groupe($planning, $id_groupe, $creneau['id'])) {
                        
                        ajouter_au_planning($planning, $creneau['id'], $salle['id'], 
                                          $c['id'], $id_groupe, $effectif);
                        $creneaux_affectes++;
                        break;
                    }
                }
            }
        }
    }
    
    return $planning;
}

/**
 * Supprime une entrée du planning
 * @param string $id_entree
 * @return bool
 */
function supprimer_du_planning($id_entree) {
    $planning = charger_planning();
    $nouveau_planning = [];
    $trouve = false;
    
    foreach ($planning as $entree) {
        if ($entree['id'] !== $id_entree) {
            $nouveau_planning[] = $entree;
        } else {
            $trouve = true;
        }
    }
    
    if (!$trouve) return false;
    
    return sauvegarder_planning($nouveau_planning);
}

/**
 * Exporte le planning en texte lisible
 * @return string
 */
function exporter_planning_texte() {
    $planning = charger_planning();
    $salles = charger_salles();
    $cours = charger_cours();
    
    $texte = "=== PLANNING HEBDOMADAIRE ===\n";
    $texte .= "Généré le: " . date('d/m/Y H:i:s') . "\n\n";
    
    if (empty($planning)) {
        return $texte . "Aucun planning généré.\n";
    }
    
    // Grouper par créneau
    $par_creneau = [];
    foreach ($planning as $entry) {
        if (!isset($par_creneau[$entry['creneau']])) {
            $par_creneau[$entry['creneau']] = [];
        }
        $par_creneau[$entry['creneau']][] = $entry;
    }
    
    foreach ($par_creneau as $creneau => $entrees) {
        $texte .= "--- Créneau: $creneau ---\n";
        foreach ($entrees as $entry) {
            $texte .= "  Salle: {$entry['id_salle']} | ";
            $texte .= "Cours: {$entry['id_cours']} | ";
            $texte .= "Groupe: {$entry['id_groupe']} | ";
            $texte .= "Effectif: {$entry['effectif']}\n";
        }
        $texte .= "\n";
    }
    
    return $texte;
}

?>