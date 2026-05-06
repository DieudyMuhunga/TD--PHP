<?php
$planning = charger_planning();
$salles = charger_salles();

// Calculer les statistiques
$total_creneaux = count(JOURS_SEMAINE) * 3; // 3 crénéaux par jour
$stats_salles = [];

foreach ($salles as $salle) {
    $creneau_occupes = 0;
    foreach ($planning as $entry) {
        if ($entry['id_salle'] === $salle['id']) {
            $creneau_occupes++;
        }
    }
    
    $stats_salles[$salle['id']] = [
        'designation' => $salle['designation'],
        'capacite' => $salle['capacite'],
        'occupes' => $creneau_occupes,
        'libres' => $total_creneaux - $creneau_occupes,
        'taux_occupation' => calculer_pourcentage($creneau_occupes, $total_creneaux)
    ];
}
?>

<div class="row mb-4">
    <div class="col-md-12">
        <h2><i class="fas fa-chart-bar"></i> Rapports et Statistiques</h2>
    </div>
</div>

<div class="row">
    <!-- Résumé -->
    <div class="col-md-12 