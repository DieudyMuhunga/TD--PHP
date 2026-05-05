<?php
    require_once "fonctions/chargement.php";
    require_once "fonctions/verification.php";
    require_once "fonctions/planning.php";
    require_once "fonctions/sauvegarde.php";

    // chargement des données
    $option = charger_options("data/options.json");
    $promotion = charger_promotions("data/promotions.json");
    $salles = charger_salles("data/salles.json");
    $cours = charger_cours("data/cours.json");


    $creneaux = [
        "Lundi-8h", "Lundi-12h",
        "Mardi-8h", "Mardi-12h"
    ];

    // Gerer planning
    $planning = gerer_planning($salles, $promotion, $cours, $creneaux);

    // Sauvegarde
    sauvegarde_planning($planning, "data/planning.json");

    // affichage
    echo "<pre>";
    print_r($option);
    print_r($promotion);
    print_r($salles);
    print_r($cours);
    echo "</pre>";

    echo "<h2>Plqnning gérer : </h2>";
    echo "<pre>";
    print_r($planning);
    echo "</pre>";

?>