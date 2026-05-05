<?php
    function sauvegarde_planning($planning, $fichier){
        $json = json_encode($planning, JSON_PRETTY_PRINT);
        file_put_contents($fichier, $json);
    }
?>