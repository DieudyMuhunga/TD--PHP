<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - v<?php echo APP_VERSION; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="?page=dashboard">
                <i class="fas fa-calendar-alt"></i> SGA
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="?page=gestion_salles">
                            <i class="fas fa-door-open"></i> Salles
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?page=gestion_promotions">
                            <i class="fas fa-users"></i> Promotions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?page=gestion_cours">
                            <i class="fas fa-book"></i> Cours
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?page=gestion_options">
                            <i class="fas fa-graduation-cap"></i> Options
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"> 
                            <i class="fas fa-list"></i> Planning
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="?page=generer_planning">Générer</a></li>
                            <li><a class="dropdown-item" href="?page=visualiser_planning">Visualiser</a></li>
                            <li><a class="dropdown-item" href="?page=rapports">Rapports</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="auth/logout.php">
                            <i class="fas fa-sign-out-alt"></i> Déconnexion
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container-fluid">
            <!-- Page content -->
        </div>
    </main>
</body>
</html>