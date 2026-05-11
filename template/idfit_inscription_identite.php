<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>IdFit — Inscription Étape 1</title>
    <link rel="stylesheet" href="css/idfit_inscription_identite.css?v=1778498828">
    <link rel="stylesheet" href="css/inline.css?v=1778498828">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    
    <script src="js/idfit_app.js"></script>
    <script>
      function preparerEtape1() {
        // Validation simple avant envoi
        const prenom = document.getElementById('prenom').value;
        const nom = document.getElementById('nom').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const confirm = document.getElementById('confirm-password').value;

        if (!prenom || !nom || !email || !password) {
            alert("Veuillez remplir tous les champs.");
            return;
        }

        if (password !== confirm) {
            alert("Les mots de passe ne correspondent pas.");
            return;
        }

        // On rassemble les données
        const identite = {
            prenom: prenom,
            nom: nom,
            email: email,
            password: password,
            // Récupère la première lettre du bouton qui a la classe 'on' (H, F ou A)
            genre: document.querySelector('.genre-btn.on')?.innerText.trim().charAt(0) || 'H'
        };

        // On envoie l'objet à la fonction de idfit_app.js pour stockage localStorage
        saveTempUser(identite);
      }

      // Gestionnaire pour changer la classe 'on' des boutons de genre
      document.addEventListener('DOMContentLoaded', () => {
        const genreBtns = document.querySelectorAll('.genre-btn');
        genreBtns.forEach(btn => {
          btn.addEventListener('click', () => {
            genreBtns.forEach(b => b.classList.remove('on'));
            btn.classList.add('on');
          });
        });
      });
    </script>
    <style>
      .card-title {
        color: #0f172a !important; /* Couleur ardoise très sombre pour un contraste maximum */
        font-weight: 800;
      }
    </style>
</head>
<body>

<div class="wrap">
  <div class="prog-header">
    <div class="prog-logo">IdFit</div>
    <div class="prog-steps">
      <div class="pstep">
        <div class="pstep-num active">1</div>
        <div class="pstep-label active">Identité</div>
      </div>
      <div class="pstep-line"></div>
      <div class="pstep">
        <div class="pstep-num idle">2</div>
        <div class="pstep-label idle">Santé</div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-title">Créer votre compte</div>
    <div class="card-sub">Étape 1 sur 2 — Informations personnelles</div>

    <div class="hint-box">
      <i class="ti ti-shield-check hint-ico" aria-hidden="true"></i>
      <div class="hint-txt" style="color: white;">Vos données sont protégées et ne seront jamais partagées avec des tiers.</div>
    </div>

    <form id="identity-form" onsubmit="event.preventDefault(); preparerEtape1();">
      <div class="row2">
        <div class="field">
          <label class="flabel" for="prenom"><i class="ti ti-user" aria-hidden="true"></i> Prénom</label>
          <input id="prenom" name="prenom" class="inp" placeholder="Ex: Finaritra" autocomplete="given-name" required>
        </div>
        <div class="field">
          <label class="flabel" for="nom"><i class="ti ti-user" aria-hidden="true"></i> Nom</label>
          <input id="nom" name="nom" class="inp" placeholder="Ex: Rakoto" autocomplete="family-name" required>
        </div>
      </div>

      <div class="field">
        <div class="flabel"><i class="ti ti-users" aria-hidden="true"></i> Genre</div>
        <div class="genre-row">
          <button type="button" class="genre-btn on"><i class="ti ti-gender-male" aria-hidden="true"></i> Homme</button>
          <button type="button" class="genre-btn"><i class="ti ti-gender-female" aria-hidden="true"></i> Femme</button>
          <button type="button" class="genre-btn"><i class="ti ti-gender-bigender" aria-hidden="true"></i> Autre</button>
        </div>
      </div>

      <div class="field">
        <label class="flabel" for="email"><i class="ti ti-mail" aria-hidden="true"></i> Email</label>
        <input id="email" name="email" class="inp" type="email" placeholder="votre@email.com" autocomplete="email" required>
        <div id="email-status" class="err-msg" style="display:none;">
          <i class="ti ti-alert-circle" aria-hidden="true"></i> Adresse email invalide
        </div>
      </div>

      <div class="row2">
        <div class="field">
          <label class="flabel" for="password"><i class="ti ti-lock" aria-hidden="true"></i> Mot de passe</label>
          <div class="password-wrapper" style="position:relative; display:flex; align-items:center;">
            <input id="password" name="password" class="inp" type="password" placeholder="••••••••" autocomplete="new-password" required style="width:100%; padding-right:40px;">
            <button type="button" class="btn-toggle-password" onclick="togglePassword('password', 'eye-icon-pw')" style="position:absolute; right:10px; background:transparent; border:none; color:#888; cursor:pointer;" aria-label="Afficher le mot de passe">
              <i id="eye-icon-pw" class="ti ti-eye" aria-hidden="true"></i>
            </button>
          </div>
          <div class="pw-bar">
            <div class="ps"></div><div class="ps"></div><div class="ps"></div><div class="ps"></div>
          </div>
        </div>
        <div class="field">
          <label class="flabel" for="confirm-password"><i class="ti ti-lock-check" aria-hidden="true"></i> Confirmer mot de passe</label>
          <div class="password-wrapper" style="position:relative; display:flex; align-items:center;">
            <input id="confirm-password" name="confirm-password" class="inp" type="password" placeholder="••••••••" autocomplete="new-password" required style="width:100%; padding-right:40px;">
            <button type="button" class="btn-toggle-password" onclick="togglePassword('confirm-password', 'eye-icon-conf')" style="position:absolute; right:10px; background:transparent; border:none; color:#888; cursor:pointer;" aria-label="Afficher le mot de passe">
              <i id="eye-icon-conf" class="ti ti-eye" aria-hidden="true"></i>
            </button>
          </div>
        </div>
      </div>

      <div class="btn-row">
        <a href="idfit_connexion.php" class="btn-sec">Annuler</a>
        <button type="submit" class="btn-main">
          Continuer <i class="ti ti-arrow-right" aria-hidden="true"></i>
        </button>
      </div>
    </form>
  </div>
</div>

<script>
    function togglePassword(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const eyeIcon = document.getElementById(iconId);
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('ti-eye');
            eyeIcon.classList.add('ti-eye-off');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('ti-eye-off');
            eyeIcon.classList.add('ti-eye');
        }
    }
</script>

</body>
</html>