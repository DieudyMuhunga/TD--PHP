<?php
    require_once "fonctions/chargement.php";

    // chargement des données

    $option = charger_options("data/options.json");
    $promotion = charger_promotions("data/promotions.json");
    $salles = charger_salles("data/salles.json");
    $cours = charger_cours("data/cours.json");

    // test affichage

    echo "<pre>";
    print_r($option);
    print_r($promotion);
    print_r($salles);
    print_r($cours);
    echo "<pre>";
    
?>