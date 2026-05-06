<?php
$action = isset($_POST['action']) ? sanitize_input($_POST['action']) : '';
$promotions = charger_promotions();

if ($action === 'ajouter') {
    $id = sanitize_input($_POST['id'] ?? '');
    $libelle = sanitize_input($_POST['libelle'] ?? '');
    $effectif = intval($_POST['effectif'] ?? 0);
    
    if (ajouter_promotion($id, $libelle, $effectif)) {
        $_SESSION['success'] = 'Promotion ajoutée';
        header('Location: ?page=gestion_promotions');
        exit;
    }
} elseif ($action === 'supprimer') {
    if (supprimer_promotion(sanitize_input($_POST['id'] ?? ''))) {
        $_SESSION['success'] = 'Promotion supprimée';
        header('Location: ?page=gestion_promotions');
        exit;
    }
} elseif ($action === 'modifier') {
    $id = sanitize_input($_POST['id'] ?? '');
    $libelle = sanitize_input($_POST['libelle'] ?? '');
    $effectif = intval($_POST['effectif'] ?? 0);
    
    if (mettre_a_jour_promotion($id, $libelle, $effectif)) {
        $_SESSION['success'] = 'Promotion modifiée';
        header('Location: ?page=gestion_promotions');
        exit;
    }
}

$promotions = charger_promotions();
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2><i class="fas fa-users"></i> Gestion des Promotions</h2>
    </div>
    <div class="col-md-4 text-end">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAjouterPromo">
            <i class="fas fa-plus"></i> Nouvelle Promotion
        </button>
    </div>
</div>

<div class="card shadow">
    <div class="card-body">
        <?php if (empty($promotions)): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> Aucune promotion
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Libellé</th>
                            <th>Effectif</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($promotions as $p): ?>
                            <tr>
                                <td><strong><?php echo $p['id']; ?></strong></td>
                                <td><?php echo $p['libelle']; ?></td>
                                <td><span class="badge bg-success"><?php echo $p['effectif']; ?></span></td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" 
                                            data-bs-target="#modalModifierPromo"
                                            onclick="chargerPromo('<?php echo $p['id']; ?>', '<?php echo htmlspecialchars($p['libelle']); ?>', <?php echo $p['effectif']; ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="action" value="supprimer">
                                        <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer?')">
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
<div class="modal fade" id="modalAjouterPromo" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Nouvelle Promotion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <input type="hidden" name="action" value="ajouter">
                    <div class="mb-3">
                        <label class="form-label">ID Promotion</label>
                        <select name="id" class="form-control" required>
                            <option value="">-- Sélectionner --</option>
                            <option value="L1">L1</option>
                            <option value="L2">L2</option>
                            <option value="L3">L3</option>
                            <option value="L4">L4</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Libellé</label>
                        <input type="text" class="form-control" name="libelle" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Effectif</label>
                        <input type="number" class="form-control" name="effectif" required min="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Modifier -->
<div class="modal fade" id="modalModifierPromo" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Modifier Promotion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <input type="hidden" name="action" value="modifier">
                    <input type="hidden" name="id" id="mod_id_promo">
                    <div class="mb-3">
                        <label class="form-label">Libellé</label>
                        <input type="text" class="form-control" name="libelle" id="mod_libelle_promo" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Effectif</label>
                        <input type="number" class="form-control" name="effectif" id="mod_effectif_promo" required>
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
function chargerPromo(id, libelle, effectif) {
    document.getElementById('mod_id_promo').value = id;
    document.getElementById('mod_libelle_promo').value = libelle;
    document.getElementById('mod_effectif_promo').value = effectif;
}
</script>