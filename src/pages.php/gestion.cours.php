<?php
$action = isset($_POST['action']) ? sanitize_input($_POST['action']) : '';
$cours_list = charger_cours();
$promotions = charger_promotions();
$options = charger_options();

if ($action === 'ajouter') {
    $id = sanitize_input($_POST['id'] ?? '');
    $intitule = sanitize_input($_POST['intitule'] ?? '');
    $volume_horaire = intval($_POST['volume_horaire'] ?? 0);
    $type = sanitize_input($_POST['type'] ?? '');
    $promo_opt = sanitize_input($_POST['promo_opt'] ?? '');
    
    if (ajouter_cours($id, $intitule, $volume_horaire, $type, $promo_opt)) {
        $_SESSION['success'] = 'Cours ajouté';
        header('Location: ?page=gestion_cours');
        exit;
    }
} elseif ($action === 'supprimer') {
    if (supprimer_cours(sanitize_input($_POST['id'] ?? ''))) {
        $_SESSION['success'] = 'Cours supprimé';
        header('Location: ?page=gestion_cours');
        exit;
    }
} elseif ($action === 'modifier') {
    $id = sanitize_input($_POST['id'] ?? '');
    $intitule = sanitize_input($_POST['intitule'] ?? '');
    $volume_horaire = intval($_POST['volume_horaire'] ?? 0);
    $type = sanitize_input($_POST['type'] ?? '');
    $promo_opt = sanitize_input($_POST['promo_opt'] ?? '');
    
    if (mettre_a_jour_cours($id, $intitule, $volume_horaire, $type, $promo_opt)) {
        $_SESSION['success'] = 'Cours modifié';
        header('Location: ?page=gestion_cours');
        exit;
    }
}

$cours_list = charger_cours();
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2><i class="fas fa-book"></i> Gestion des Cours</h2>
    </div>
    <div class="col-md-4 text-end">
        <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalAjouterCours">
            <i class="fas fa-plus"></i> Nouveau Cours
        </button>
    </div>
</div>

<div class="card shadow">
    <div class="card-body">
        <?php if (empty($cours_list)): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> Aucun cours
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Intitulé</th>
                            <th>Volume Horaire</th>
                            <th>Type</th>
                            <th>Promo/Option</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cours_list as $c): ?>
                            <tr>
                                <td><strong><?php echo $c['id']; ?></strong></td>
                                <td><?php echo $c['intitule']; ?></td>
                                <td><span class="badge bg-info"><?php echo $c['volume_horaire']; ?>h</span></td>
                                <td><?php echo ($c['type'] === 'tronc_commun') ? 'Tronc Commun' : 'Option'; ?></td>
                                <td><?php echo $c['promotion_ou_option']; ?></td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" 
                                            data-bs-target="#modalModifierCours"
                                            onclick="chargerCours('<?php echo $c['id']; ?>', '<?php echo htmlspecialchars($c['intitule']); ?>', <?php echo $c['volume_horaire']; ?>, '<?php echo $c['type']; ?>', '<?php echo $c['promotion_ou_option']; ?>')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="action" value="supprimer">
                                        <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
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
<div class="modal fade" id="modalAjouterCours" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Nouveau Cours</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <input type="hidden" name="action" value="ajouter">
                    <div class="mb-3">
                        <label class="form-label">ID Cours</label>
                        <input type="text" class="form-control" name="id" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Intitulé</label>
                        <input type="text" class="form-control" name="intitule" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Volume Horaire (heures/semaine)</label>
                        <input type="number" class="form-control" name="volume_horaire" required min="1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-control" required onchange="afficherOptions()">
                            <option value="">-- Sélectionner --</option>
                            <option value="tronc_commun">Tronc Commun</option>
                            <option value="option">Option</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Promotion/Option</label>
                        <select name="promo_opt" class="form-control" required id="select_promo_opt">
                            <option value="">-- Sélectionner --</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-info"><i class="fas fa-save"></i> Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Modifier -->
<div class="modal fade" id="modalModifierCours" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Modifier Cours</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <input type="hidden" name="action" value="modifier">
                    <input type="hidden" name="id" id="mod_id_cours">
                    <div class="mb-3">
                        <label class="form-label">Intitulé</label>
                        <input type="text" class="form-control" name="intitule" id="mod_intitule" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Volume Horaire</label>
                        <input type="number" class="form-control" name="volume_horaire" id="mod_volume" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-control" id="mod_type" required>
                            <option value="tronc_commun">Tronc Commun</option>
                            <option value="option">Option</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Promotion/Option</label>
                        <select name="promo_opt" class="form-control" id="mod_promo_opt" required></select>
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
function chargerCours(id, intitule, volume, type, promo_opt) {
    document.getElementById('mod_id_cours').value = id;
    document.getElementById('mod_intitule').value = intitule;
    document.getElementById('mod_volume').value = volume;
    document.getElementById('mod_type').value = type;
    document.getElementById('mod_promo_opt').value = promo_opt;
}

function afficherOptions() {
    const type = document.querySelector('select[name="type"]').value;
    const select = document.getElementById('select_promo_opt');
    select.innerHTML = '<option value="">-- Sélectionner --</option>';
    
    if (type === 'tronc_commun') {
        <?php foreach ($promotions as $p): ?>
            select.innerHTML += '<option value="<?php echo $p['id']; ?>"><?php echo $p['libelle']; ?></option>';
        <?php endforeach; ?>
    } else if (type === 'option') {
        <?php foreach ($options as $o): ?>
            select.innerHTML += '<option value="<?php echo $o['id']; ?>"><?php echo $o['libelle']; ?></option>';
        <?php endforeach; ?>
    }
}
</script>