<?php
$action = isset($_POST['action']) ? sanitize_input($_POST['action']) : '';
$salles = charger_salles();

// Traiter les actions
if ($action === 'ajouter') {
    $id = sanitize_input($_POST['id'] ?? '');
    $designation = sanitize_input($_POST['designation'] ?? '');
    $capacite = intval($_POST['capacite'] ?? 0);
    
    if (empty($id) || empty($designation) || $capacite <= 0) {
        $_SESSION['error'] = 'Tous les champs sont requis et la capacité doit être positive';
    } else {
        if (ajouter_salle($id, $designation, $capacite)) {
            $_SESSION['success'] = 'Salle ajoutée avec succès';
            header('Location: ?page=gestion_salles');
            exit;
        } else {
            $_SESSION['error'] = 'Erreur lors de l\'ajout de la salle (ID déjà existant?)';
        }
    }
} elseif ($action === 'supprimer') {
    $id = sanitize_input($_POST['id'] ?? '');
    if (supprimer_salle($id)) {
        $_SESSION['success'] = 'Salle supprimée avec succès';
        header('Location: ?page=gestion_salles');
        exit;
    } else {
        $_SESSION['error'] = 'Erreur lors de la suppression';
    }
} elseif ($action === 'modifier') {
    $id = sanitize_input($_POST['id'] ?? '');
    $designation = sanitize_input($_POST['designation'] ?? '');
    $capacite = intval($_POST['capacite'] ?? 0);
    
    if (mettre_a_jour_salle($id, $designation, $capacite)) {
        $_SESSION['success'] = 'Salle modifiée avec succès';
        header('Location: ?page=gestion_salles');
        exit;
    } else {
        $_SESSION['error'] = 'Erreur lors de la modification';
    }
}

// Reload après traitement
$salles = charger_salles();
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2><i class="fas fa-door-open"></i> Gestion des Salles</h2>
    </div>
    <div class="col-md-4 text-end">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAjouterSalle">
            <i class="fas fa-plus"></i> Nouvelle Salle
        </button>
    </div>
</div>

<div class="card shadow">
    <div class="card-body">
        <?php if (empty($salles)): ?>
            <div class="alert alert-warning text-center">
                <i class="fas fa-exclamation-triangle"></i> Aucune salle enregistrée
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Désignation</th>
                            <th>Capacité</th>
                            <th>Date Création</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($salles as $salle): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($salle['id']); ?></strong></td>
                                <td><?php echo htmlspecialchars($salle['designation']); ?></td>
                                <td>
                                    <span class="badge bg-info"><?php echo $salle['capacite']; ?> places</span>
                                </td>
                                <td><?php echo $salle['date_creation']; ?></td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" 
                                            data-bs-target="#modalModifierSalle" 
                                            onclick="chargerModificationSalle('<?php echo htmlspecialchars($salle['id']); ?>', '<?php echo htmlspecialchars($salle['designation']); ?>', <?php echo $salle['capacite']; ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="action" value="supprimer">
                                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($salle['id']); ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                onclick="return confirm('Confirmer la suppression?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Ajouter -->
<div class="modal fade" id="modalAjouterSalle" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Nouvelle Salle</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <input type="hidden" name="action" value="ajouter">
                    <div class="mb-3">
                        <label for="id" class="form-label">ID Salle</label>
                        <input type="text" class="form-control" name="id" required placeholder="ex: AUD-L1">
                    </div>
                    <div class="mb-3">
                        <label for="designation" class="form-label">Désignation</label>
                        <input type="text" class="form-control" name="designation" required placeholder="ex: Auditoire principal">
                    </div>
                    <div class="mb-3">
                        <label for="capacite" class="form-label">Capacité</label>
                        <input type="number" class="form-control" name="capacite" required min="1" placeholder="Nombre de places">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Modifier -->
<div class="modal fade" id="modalModifierSalle" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Modifier Salle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <input type="hidden" name="action" value="modifier">
                    <input type="hidden" name="id" id="mod_id">
                    <div class="mb-3">
                        <label for="designation" class="form-label">Désignation</label>
                        <input type="text" class="form-control" name="designation" id="mod_designation" required>
                    </div>
                    <div class="mb-3">
                        <label for="capacite" class="form-label">Capacité</label>
                        <input type="number" class="form-control" name="capacite" id="mod_capacite" required min="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Modifier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function chargerModificationSalle(id, designation, capacite) {
    document.getElementById('mod_id').value = id;
    document.getElementById('mod_designation').value = designation;
    document.getElementById('mod_capacite').value = capacite;
}
</script>