<?php
$total_salles = compter_salles();
$total_promotions = compter_promotions();
$total_cours = compter_cours();
$total_options = compter_options();
?>

<div class="row mb-4">
    <div class="col-md-12">
        <h1 class="mb-4">
            <i class="fas fa-tachometer-alt"></i> Tableau de Bord
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="card bg-primary text-white shadow">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Salles</h6>
                        <h2 class="mb-0"><?php echo $total_salles; ?></h2>
                    </div>
                    <i class="fas fa-door-open fa-3x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer">
                <a href="?page=gestion_salles" class="text-white text-decoration-none">
                    Gérer <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-success text-white shadow">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Promotions</h6>
                        <h2 class="mb-0"><?php echo $total_promotions; ?></h2>
                    </div>
                    <i class="fas fa-users fa-3x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer">
                <a href="?page=gestion_promotions" class="text-white text-decoration-none">
                    Gérer <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-info text-white shadow">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Cours</h6>
                        <h2 class="mb-0"><?php echo $total_cours; ?></h2>
                    </div>
                    <i class="fas fa-book fa-3x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer">
                <a href="?page=gestion_cours" class="text-white text-decoration-none">
                    Gérer <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-warning text-white shadow">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Options</h6>
                        <h2 class="mb-0"><?php echo $total_options; ?></h2>
                    </div>
                    <i class="fas fa-graduation-cap fa-3x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer">
                <a href="?page=gestion_options" class="text-white text-decoration-none">
                    Gérer <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Bienvenue</h5>
            </div>
            <div class="card-body">
                <p>Bienvenue dans le <strong><?php echo APP_NAME; ?></strong>!</p>
                <p>Ce système permet de gérer efficacement les salles, promotions et planning des cours.</p>
                <div class="alert alert-info">
                    <strong>Démarrage rapide:</strong>
                    <ol>
                        <li>Commencez par créer vos salles</li>
                        <li>Puis enregistrez vos promotions</li>
                        <li>Ajoutez vos cours et options</li>
                        <li>Générez le planning automatiquement</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>