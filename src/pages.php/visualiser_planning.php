<?php
$planning = charger_planning();
$salles = charger_salles();
$cours = charger_cours();
$creneaux = generer_creneaux();

// Détecter les conflits
$conflits = detecter_conflits($planning);
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2><i class="fas fa-calendar-check"></i> Visualisation du Planning</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="?page=generer_planning" class="btn btn-primary">
            <i class="fas fa-redo"></i> Régénérer
        </a>
    </div>
</div>

<?php if (!empty($conflits)): ?>
    <div class="alert alert-danger">
        <h5><i class="fas fa-exclamation-triangle"></i> Conflits Détectés</h5>
        <ul class="mb-0">
            <?php foreach ($conflits as $conflit): ?>
                <li><?php echo htmlspecialchars($conflit['detail']); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if (empty($planning)): ?>
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle"></i> Aucun planning généré. 
        <a href="?page=generer_planning">Générer maintenant</a>
    </div>
<?php else: ?>
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Créneau</th>
                            <th>Salle</th>
                            <th>Cours</th>
                            <th>Groupe</th>
                            <th>Effectif</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($planning as $entry): ?>
                            <tr>
                                <td>
                                    <?php 
                                    foreach ($creneaux as $cren) {
                                        if ($cren['id'] === $entry['creneau']) {
                                            echo $cren['libelle'];
                                            break;
                                        }
                                    }
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($entry['id_salle']); ?></td>
                                <td><?php echo htmlspecialchars($entry['id_cours']); ?></td>
                                <td><?php echo htmlspecialchars($entry['id_groupe']); ?></td>
                                <td><span class="badge bg-info"><?php echo $entry['effectif']; ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Vue par jour -->
    <div class="card shadow mt-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-th"></i> Vue Hebdomadaire</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>Créneau</th>
                            <?php foreach (JOURS_SEMAINE as $jour): ?>
                                <th><?php echo $jour; ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $heures = ['08:00-12:00', '12:00-16:00', '16:00-17:00'];
                        foreach ($heures as $heure): ?>
                            <tr>
                                <td><strong><?php echo $heure; ?></strong></td>
                                <?php foreach (range(0, 4) as $jour_idx): ?>
                                    <td>
                                        <?php 
                                        $creneau_id = "$jour_idx-" . substr($heure, 0, 2) . "-" . substr($heure, 6, 2);
                                        foreach ($planning as $entry) {
                                            if ($entry['creneau'] === $creneau_id) {
                                                echo '<small>';
                                                echo htmlspecialchars($entry['id_salle']) . '<br>';
                                                echo htmlspecialchars($entry['id_cours']) . '<br>';
                                                echo 'Eff: ' . $entry['effectif'];
                                                echo '</small>';
                                                break;
                                            }
                                        }
                                        ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>