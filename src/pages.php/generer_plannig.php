<?php
$action = isset($_POST['action']) ? sanitize_input($_POST['action']) : '';

if ($action === 'generer') {
    try {
        $planning = generer_planning_automatique();
        
        if (sauvegarder_planning($planning)) {
            $_SESSION['success'] = 'Planning généré et sauvegardé avec succès! ' . count($planning) . ' entrées.';
        } else {
            $_SESSION['error'] = 'Erreur lors de la sauvegarde du planning';
        }
    } catch (Exception $e) {
        $_SESSION['error'] = 'Erreur: ' . $e->getMessage();
    }
    
    header('Location: ?page=generer_planning');
    exit;
}

$salles = charger_salles();
$promotions = charger_promotions();
$cours = charger_cours();
$options = charger_options();
$planning_actuel = charger_planning();
?>

<div class="row mb-4">
    <div class="col-md-12">
        <h2><i class="fas fa-magic"></i> Générer le Planning</h2>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Préparation</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center">
                            <h5><?php echo count($salles); ?></h5>
                            <p class="text-muted">Salles</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h5><?php echo count($promotions); ?></h5>
                            <p class="text-muted">Promotions</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h5><?php echo count($cours); ?></h5>
                            <p class="text-muted">Cours</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h5><?php echo count($options); ?></h5>
                            <p class="text-muted">Options</p>
                        </div>
                    </div>
                </div>

                <?php if (count($salles) === 0 || count($promotions) === 0 || count($cours) === 0): ?>
                    <div class="alert alert-danger mt-3">
                        <i class="fas fa-exclamation-circle"></i> 
                        Vous devez d'abord configurer les salles, promotions et cours avant de générer le planning.
                    </div>
                <?php else: ?>
                    <form method="post" class="mt-3">
                        <input type="hidden" name="action" value="generer">
                        <button type="submit" class="btn btn-primary btn-lg" onclick="return confirm('Générer le planning automatiquement?')">
                            <i class="fas fa-magic"></i> Générer le Planning
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($planning_actuel)): ?>
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-check-circle"></i> Planning Actuel</h5>
                </div>
                <div class="card-body">
                    <p><strong><?php echo count($planning_actuel); ?> entrées</strong> dans le planning</p>
                    <a href="?page=visualiser_planning" class="btn btn-success">
                        <i class="fas fa-eye"></i> Visualiser
                    </a>
                    <a href="?page=rapports" class="btn btn-info">
                        <i class="fas fa-chart-bar"></i> Voir les Rapports
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>