<?php
    // foction generale de lecture des fichiers

    function lire_json($chemin_fichier){
        if(!file_exists($chemin_fichier)){
            die("Erreur : fichier introuvable - " . $chemin_fichier);
        }

        $contenu = file_get_contents($chemin_fichier);

        $donnees = json_decode($contenu, true);

        if($donnees === null){
            die("Erreur : format JSON invalide - " . $chemin_fichier);
        }

        return $donnees;
    }

    // fonction 
    
    function charger_salles($chemin_fichier){
        return lire_json($chemin_fichier);
    }

    function charger_promotions($chemin_fichier){
        return lire_json($chemin_fichier);
    }

    function charger_cours($chemin_fichier){
        return lire_json($chemin_fichier);
    }

    function charger_options($chemin_fichier){
        return lire_json($chemin_fichier);
    }
?>