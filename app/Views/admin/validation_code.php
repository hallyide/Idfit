<link rel="stylesheet" href="<?= base_url('template/css/admin_style.css') ?>">
<div class="admin-container">
    <div class="admin-header">
        <h2><i class="ti ti-settings-automation"></i> Validation des Recharges Cash</h2>
        <p class="admin-sub">Consultez et validez les demandes de recharges des utilisateurs.</p>
    </div>

    <div class="card shadow-sm">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Date Demande</th>
                    <th>Code Saisi</th>
                    <th>Montant</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($demandes)): ?>
                    <?php foreach($demandes as $d): ?>
                    <tr>
                        <td>
                            <div class="user-info">
                                <strong><?= esc($d['nom']) ?> <?= esc($d['prenom']) ?></strong>
                                <span class="user-id">ID: #<?= $d['user_id'] ?></span>
                            </div>
                        </td>
                        <td><?= date('d/m/Y H:i', strtotime($d['date_demande'])) ?></td>
                        <td><code class="code-badge"><?= esc($d['code']) ?></code></td>
                        <td><span class="amount"><?= number_format($d['valeur'], 0, ',', ' ') ?> Ar</span></td>
                        <td class="actions">
                            <a href="<?= base_url('admin/valider-recharge/'.$d['id']) ?>" 
                               class="btn-action approve" title="Valider">
                               <i class="ti ti-check"></i>
                            </a>
                            <a href="<?= base_url('admin/refuser-recharge/'.$d['id']) ?>" 
                               class="btn-action reject" title="Refuser">
                               <i class="ti ti-x"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="empty-state">Aucune demande en attente.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>