<?php 
    require_once "verification.php";

    function gerer_planning($salles, $promotion, $cours, $creneaux){
        $planning = [];

        foreach($cours as $c){
            $groupe = $c['promotion'];
            $effectif = 0;

            foreach($promotion as $p){
                if($p['id'] == $groupe){
                    $effectif = $p['effectif'];
                }
            }
        }

        foreach($creneaux as $creneau){
            foreach($salles as $salle){
                if(salle_disponible($planning, $salle['id'], $creneau) && capacite_suffisante($salles, $salle['id'], $effectif) && creneau_libre_groupe($planning, $groupe, $creneaux)){
                    $planning[] = [
                        "creneau" => $creneau,
                        "salle" => $salle['id'],
                        "cours" => $c['id'],
                        "groupe"=> $groupe
                    ];
                    break 2;
                }
            }
        }

        return $planning;
    }
?>