<?php
    function salle_disponible($planning, $id_salle, $creneau) {
        foreach ($planning as $p) {
            if ($p['salle'] == $id_salle && $p['creneau'] == $creneau) {
                return false;
            }
        }
        return true;
    }

    function capacite_suffisante($salles, $id_salle, $effectif) {
        foreach ($salles as $salle) {
            if ($salle['id'] == $id_salle) {
                return $effectif <= $salle['capacite'];
            }
        }
        return false;
    }

    function creneau_libre_groupe($planning, $id_groupe, $creneau) {
        foreach ($planning as $p) {
            if ($p['groupe'] == $id_groupe && $p['creneau'] == $creneau) {
                return false;
            }
        }
        return true;
    }

?>