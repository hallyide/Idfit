<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport NutriPlan</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; color: #333; }
        h1 { color: #2c3e50; text-align: center; }
        .box { border: 1px solid #ddd; padding: 15px; margin-bottom: 20px; background: #f9f9f9; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border-bottom: 1px solid #ddd; text-align: left; }
    </style>
</head>
<body>
    <h1>Rapport Santé - NutriPlan</h1>
    
    <div class="box">
        <h3>Profil de <?= esc($user['userFirstName'] ?? 'Utilisateur') ?> <?= esc($user['userLastName'] ?? '') ?></h3>
        <p><strong>Taille :</strong> <?= esc($user['taille']) ?> cm</p>
        <p><strong>Poids actuel :</strong> <?= esc($user['poids']) ?> kg</p>
    </div>

    <div class="box">
        <h3>Analyse IMC</h3>
        <p><strong>Votre IMC :</strong> <?= esc($imc) ?></p>
        <p><strong>Catégorie :</strong> <?= ucfirst(esc($categorie)) ?></p>
        <p><strong>Poids idéal estimé :</strong> <?= esc($poids_ideal) ?> kg</p>
    </div>

    <?php if ($regime): ?>
    <div class="box">
        <h3 style="color: #663266;">Votre Programme Actif : <?= esc($regime['nom']) ?></h3>
        <p><?= esc($regime['description']) ?></p>
        <p><strong>Objectif :</strong> <?= esc($regime['objectif']) ?></p>
        <p><strong>Apport énergétique :</strong> <?= esc($regime['calories_jour']) ?> kcal / jour</p>
        
        <h4 style="margin-top: 15px;">Répartition Nutritionnelle</h4>
        <table style="margin-top: 10px; border: 1px solid #eee;">
            <thead>
                <tr>
                    <th style="background: #f8f5fa; border-bottom: 2px solid #663266; color: #663266;">Viande</th>
                    <th style="background: #f8f5fa; border-bottom: 2px solid #663266; color: #663266;">Poisson</th>
                    <th style="background: #f8f5fa; border-bottom: 2px solid #663266; color: #663266;">Volaille</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center; font-weight: bold;"><?= esc($regime['pct_viande']) ?>%</td>
                    <td style="text-align: center; font-weight: bold;"><?= esc($regime['pct_poisson']) ?>%</td>
                    <td style="text-align: center; font-weight: bold;"><?= esc($regime['pct_volaille']) ?>%</td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

</body>
</html>