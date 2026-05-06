<?php
$action = isset($_POST['action']) ? sanitize_input($_POST['action']) : '';
$options_list = charger_options();
$promotions = charger_promotions();

if ($action === 'ajouter') {
    $id = sanitize_input($_POST['id'] ?? '');
    $libelle = sanitize_input($_POST['libelle'] ?? '');
    $id_promotion = sanitize_input($_POST['id_promotion'] ?? '');
    $effectif = intval($_POST['effectif'] ?? 0);
    
    if (ajouter_option($id, $libelle, $id_promotion, $effectif)) {
        $_SESSION['success'] = 'Option ajoutée';
        header('Location: ?page=gestion_options');
        exit;
    }
} elseif ($action === 'supprimer') {
    if (supprimer_option(sanitize_input($_POST['id'] ?? ''))) {
        $_SESSION['success'] = 'Option supprimée';
        header('Location: ?page=gestion_options');
        exit;
    }
} elseif ($action === 'modifier') {
    $id = sanitize_input($_POST['id'] ?? '');
    $libelle = sanitize_input($_POST['libelle'] ?? '');
    $effectif = intval($_POST['effectif'] ?? 0);
    
    if (mettre_a_jour_option($id, $libelle, $effectif)) {
        $_SESSION['success'] = 'Option modifiée';
        header('Location: ?page=gestion_options');
        exit;
    }
}

$options_list = charger_options();
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2><i class="fas fa-graduation-cap"></i> Gestion des Options</h2>
    </div>
    <div class="col-md-4 text-end">
        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalAjouterOption">
            <i class="fas fa-plus"></i> Nouvelle Option
        </button>
    </div>
</div>

<div class="card shadow">
    <div class="card-body">
        <?php if (empty($options_list)): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> Aucune option
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Libellé</th>
                            <th>Promotion</th>
                            <th>Effectif</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($options_list as $o): ?>
                            <tr>
                                <td><strong><?php echo $o['id']; ?></strong></td>
                                <td><?php echo $o['libelle']; ?></td>
                                <td><?php echo $o['promotion_parente']; ?></td>
                                <td><span class="badge bg-warning" style="color: black;"><?php echo $o['effectif']; ?></span></td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" 
                                            data-bs-target="#modalModifierOption"
                                            onclick="chargerOption('<?php echo $o['id']; ?>', '<?php echo htmlspecialchars($o['libelle']); ?>', <?php echo $o['effectif']; ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="action" value="supprimer">
                                        <input type="hidden" name="id" value="<?php echo $o['id']; ?>">
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
<div class="modal fade" id="modalAjouterOption" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Nouvelle Option</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <input type="hidden" name="action" value="ajouter">
                    <div class="mb-3">
                        <label class="form-label">ID Option</label>
                        <input type="text" class="form-control" name="id" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Libellé</label>
                        <input type="text" class="form-control" name="libelle" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Promotion Parente</label>
                        <select name="id_promotion" class="form-control" required>
                            <option value="">-- Sélectionner --</option>
                            <option value="L3">L3</option>
                            <option value="L4">L4</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Effectif</label>
                        <input type="number" class="form-control" name="effectif" required min="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Modifier -->
<div class="modal fade" id="modalModifierOption" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Modifier Option</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <input type="hidden" name="action" value="modifier">
                    <input type="hidden" name="id" id="mod_id_option">
                    <div class="mb-3">
                        <label class="form-label">Libellé</label>
                        <input type="text" class="form-control" name="libelle" id="mod_libelle_option" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Effectif</label>
                        <input type="number" class="form-control" name="effectif" id="mod_effectif_option" required>
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
function chargerOption(id, libelle, effectif) {
    document.getElementById('mod_id_option').value = id;
    document.getElementById('mod_libelle_option').value = libelle;
    document.getElementById('mod_effectif_option').value = effectif;
}
</script>