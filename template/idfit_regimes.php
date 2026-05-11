<link rel="stylesheet" href="<?= base_url('css/idfit_regimes.css') ?>">
<meta charset="UTF-8">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<link rel="stylesheet" href="css/header.css?v=1778498828">
<link rel="stylesheet" href="css/idfit_regimes.css?v=1778498828">
<link rel="stylesheet" href="css/inline.css?v=1778498828">
<script src="js/idfit_app.js"></script>

<header class="user-header-wrap">
  <div class="user-header">
    <a class="uh-brand" href="idfit_dashboard_user.php" aria-label="Accueil IdFit">
      <span class="uh-logo-mark">
        <img src="image/logo.png" alt="IdFit">
      </span>
      <span class="uh-logo-text">
        <?php if ($isGold ?? false): ?>
          <span class="uh-status"><i class="ti ti-star-filled" aria-hidden="true"></i> Gold actif</span>
        <?php else: ?>
          <span class="uh-status" style="background: #e2e8f0; color: #475569;"><i class="ti ti-user" aria-hidden="true"></i> Standard</span>
        <?php endif; ?>
      </span>
    </a>

    <nav class="uh-nav" aria-label="Navigation utilisateur">
      <a class="uh-link" href="idfit_dashboard_user.php">
        <i class="ti ti-home" aria-hidden="true"></i>
        Dashboard
      </a>
      <a class="uh-link is-active" href="idfit_regimes.php">
        <i class="ti ti-salad" aria-hidden="true"></i>
        R&eacute;gimes
      </a>
      <a class="uh-link" href="idfit_finance.php">
        <i class="ti ti-wallet" aria-hidden="true"></i>
        Portefeuille
      </a>
      <a class="uh-link" href="#">
        <i class="ti ti-chart-line" aria-hidden="true"></i>
        Graphique
      </a>
    </nav>

    <div class="uh-actions">
      <a class="uh-wallet" href="idfit_finance.php" title="Solde disponible">
        <i class="ti ti-coins" aria-hidden="true"></i>
        <span data-wallet-balance><?= number_format($walletBalance ?? 0, 0, ',', ' ') ?> Ar</span>
      </a>
      <a class="uh-wallet uh-gold" href="idfit_finance.php" title="Option Gold">
        <i class="ti ti-crown" aria-hidden="true"></i>
        <span>Gold</span>
      </a>
      <div class="uh-profile" aria-label="Profil utilisateur">
        <div class="uh-profile-copy">
          <div class="uh-profile-name"><?= esc($userFirstName ?? 'User') ?> <?= esc(substr($userLastName ?? '', 0, 1)) ?>.</div>
          <div class="uh-profile-role"><?= ($isGold ?? false) ? 'Membre Gold' : 'Membre' ?></div>
        </div>
        <a href="/api/logout" class="uh-avatar" title="Se déconnecter" style="text-decoration: none;">
          <?= strtoupper(substr($userFirstName ?? 'U', 0, 1) . substr($userLastName ?? '', 0, 1)) ?>
        </a>
      </div>
    </div>
  </div>
</header>

<div class="layout">
  <div class="sidebar">
    <div class="sb-logo">IdFit <?php if ($isGold ?? false): ?><span class="gold-badge">GOLD</span><?php endif; ?></div>
    <div class="sb-section">Principal</div>
    <div class="sb-item" onclick="location.href='idfit_dashboard_user.php'"><i class="ti ti-home" aria-hidden="true"></i> Dashboard</div>
    <div class="sb-item on"><i class="ti ti-salad" aria-hidden="true"></i> Régimes</div>
    <div class="sb-item" onclick="location.href='#'"><i class="ti ti-chart-line" aria-hidden="true"></i> Graphique</div>
    <div class="sb-section">Finance</div>
    <div class="sb-item" onclick="location.href='idfit_finance.php'"><i class="ti ti-wallet" aria-hidden="true"></i> Portefeuille</div>
    <div class="sb-item" onclick="location.href='idfit_finance.php'"><i class="ti ti-star" aria-hidden="true"></i> Option Gold</div>
    <div class="sb-bottom">
      <div class="sb-user">
        <div class="sb-av"><?= strtoupper(substr($userFirstName ?? 'U', 0, 1) . substr($userLastName ?? '', 0, 1)) ?></div>
        <div><div class="sb-name"><?= esc($userFirstName ?? 'User') ?></div><div class="sb-role"><?= ($isGold ?? false) ? 'Membre Gold' : 'Membre' ?></div></div>
      </div>
    </div>
  </div>

  <div class="main">
    <div class="page-header">
        <div class="page-title">Programmes alimentaires</div>
        <div class="page-sub">Trouvez le régime qui correspond à votre métabolisme</div>
    </div>

<div class="cards">
    <?php foreach ($regimes as $r): ?>
        <?php 
            // Utilisation du prix de base ou 0 si non défini
            $prixOriginal = (float)($r['prix_base'] ?? 0); 
            $prixAffiche = ($isGold ?? false) ? ($prixOriginal * (1 - ($goldDiscount ?? 0.15))) : $prixOriginal;
        ?>
        
        <div class="rcard <?= $r['est_actif'] ? 'active-plan' : '' ?>">
            <?php if ($r['est_actif'] ?? false): ?>
                <div class="active-tag">Mon régime actif</div>
            <?php endif; ?>

            <div class="rc-head">
                <div class="rc-ico u-style-16">
                    <i class="ti <?= $r['objectif'] == 'perte' ? 'ti-fish' : ($r['objectif'] == 'prise' ? 'ti-meat' : 'ti-heart') ?>" aria-hidden="true"></i>
                </div>
                <div>
                    <div class="rc-name"><?= esc($r['nom'] ?? 'Régime sans nom') ?></div>
                    <div class="rc-obj">
                        <span class="obj-badge">
                            <?= $r['objectif'] == 'perte' ? 'Réduire le poids' : ($r['objectif'] == 'prise' ? 'Augmenter le poids' : 'IMC idéal') ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="macros">
                <div class="mac"><div class="mac-v"><?= (int)$r['pct_viande'] ?>%</div><div class="mac-l">Viande</div></div>
                <div class="mac"><div class="mac-v"><?= (int)$r['pct_poisson'] ?>%</div><div class="mac-l">Poisson</div></div>
                <div class="mac"><div class="mac-v"><?= (int)$r['pct_volaille'] ?>%</div><div class="mac-l">Volaille</div></div>
            </div>

            <div class="sports">
                <?php if(!empty($r['sports'])): ?>
                    <?php foreach ($r['sports'] as $s): ?>
                        <span class="sport-tag">
                            <i class="ti ti-run" aria-hidden="true"></i> <?= esc($s['nom']) ?> <?= $s['frequence_semaine'] ?>×/sem
                        </span>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="u-style-41">
                <?= esc($r['calories_jour']) ?> kcal/jour · Durée <?= esc($r['duree_moyenne'] ?? '30') ?> jours
            </div>

            <div class="price-row">
                <div>
                    <div class="price-block">
                        <?php if ($isGold ?? false): ?>
                            <span class="price-orig"><?= number_format($prixOriginal, 0, ',', ' ') ?> Ar</span>
                            <span class="price-final"><?= number_format($prixAffiche, 0, ',', ' ') ?></span>
                        <?php else: ?>
                            <span class="price-final"><?= number_format($prixOriginal, 0, ',', ' ') ?></span>
                        <?php endif; ?>
                        <span class="price-dur">Ar/mois</span>
                    </div>
                    <?php if ($isGold ?? false): ?><span class="discount">−<?= ($goldDiscountPct ?? 15) ?>% Gold</span><?php endif; ?>
                </div>
                
                <div class="btn-row">
                    <?php if ($r['est_actif']): ?>
                        <button class="btn-sub" style="background: #94a3b8; cursor: default;"><i class="ti ti-check"></i> Souscrit</button>
                        <button class="btn-sub" data-action="downloadPDF" style="background: #10b981; margin-top: 5px;">
                            <i class="ti ti-file-download"></i> Mon Programme PDF
                        </button>
                    <?php else: ?>
                        <button class="btn-sub" data-action="subscribeToRegime" data-regime-id="<?= $r['id'] ?>" data-duree="1">
                            <i class="ti ti-shopping-cart"></i> Souscrire
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
  </div>
</div>