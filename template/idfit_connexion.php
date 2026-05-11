<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>IdFit — Connexion</title>
    <link rel="stylesheet" href="css/idfit_inscription_identite.css"> <link rel="stylesheet" href="css/inline.css?v=1778498828">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    
    <script src="js/idfit_app.js"></script>
</head>
<body>

<div class="wrap">
  <div class="prog-header">
    <div class="prog-logo">IdFit</div>
  </div>

  <div class="card">
    <div class="card-title">Bon retour parmi nous</div>
    <div class="card-sub">Connectez-vous pour accéder à votre programme personnalisé</div>

    <form action="#" method="post" id="login-form">
      
      <div class="field">
        <label class="flabel" for="login-email">
          <i class="ti ti-mail" aria-hidden="true"></i> Email
        </label>
        <input id="login-email" name="email" class="inp" type="email" placeholder="votre@email.com" autocomplete="email" required>
      </div>

      <div class="field">
        <label class="flabel" for="login-password">
          <i class="ti ti-lock" aria-hidden="true"></i> Mot de passe
        </label>
        
        <div class="password-wrapper">
          <input id="login-password" name="password" class="inp" type="password" placeholder="••••••••" autocomplete="current-password" required>
          <button type="button" class="btn-toggle-password" aria-label="Afficher le mot de passe" onclick="togglePassword()">
            <i id="eye-icon" class="ti ti-eye" aria-hidden="true"></i>
          </button>
        </div>
      </div>

      <div id="login-message" class="err-msg" style="display:none; margin-bottom: 20px;">
        <i class="ti ti-alert-circle" aria-hidden="true"></i> <span id="error-text"></span>
      </div>

      <div class="btn-row">
        <a href="vitrine.php" class="btn-sec">Accueil</a>
        <button type="submit" class="btn-main" data-action="authenticate">
          Se connecter <i class="ti ti-login" aria-hidden="true"></i>
        </button>
      </div>

    </form>

    <div style="margin-top: 30px; text-align: center; border-top: 1px solid #eee; padding-top: 20px;">
        <p style="color: #666; font-size: 0.9rem;">Pas encore de compte ?</p>
        <a href="idfit_inscription_identite.php" style="color: #7c3aed; font-weight: 600; text-decoration: none;">Créer un compte maintenant</a>
    </div>
  </div>
</div>

<style>
    /* Ajustements existants */
    .err-msg {
        padding: 10px;
        border-radius: 8px;
        background: #fff5f5;
        font-size: 0.9rem;
        border: 1px solid #feb2b2;
        color: #c53030; /* Assure un bon contraste pour la lisibilité */
    }

    /* CSS additionnel pour l'icône du mot de passe (sans casser le design global) */
    .password-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }
    
    .password-wrapper .inp {
        width: 100%;
        padding-right: 40px; /* Libère de l'espace pour l'icône à l'intérieur du champ */
    }
    
    .btn-toggle-password {
        position: absolute;
        right: 10px;
        background: transparent;
        border: none;
        color: #888;
        cursor: pointer;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    .btn-toggle-password:hover {
        color: #333;
    }
</style>

<script>
    // Script simple pour basculer l'affichage du mot de passe
    function togglePassword() {
        const passwordInput = document.getElementById('login-password');
        const eyeIcon = document.getElementById('eye-icon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('ti-eye');
            eyeIcon.classList.add('ti-eye-off'); // Si Tabler Icons utilise ti-eye-off
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('ti-eye-off');
            eyeIcon.classList.add('ti-eye');
        }
    }
</script>

</body>
</html>