<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>IdFit — Inscription Étape 1</title>
    <link rel="stylesheet" href="css/idfit_inscription_identite.css">
    <link rel="stylesheet" href="css/inline.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    
    <script src="js/idfit_app.js"></script>
    <script>
      const validators = {
        prenom: value => value.trim().length >= 2 ? '' : 'Le prénom doit contenir au moins 2 lettres.',
        nom: value => value.trim().length >= 2 ? '' : 'Le nom doit contenir au moins 2 lettres.',
        email: value => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value.trim()) ? '' : 'Adresse email invalide.',
        password: value => value.length >= 8 ? '' : 'Le mot de passe doit contenir au moins 8 caractères.',
        'confirm-password': value => value === document.getElementById('password').value ? '' : 'Les mots de passe ne correspondent pas.'
      };

      function setFieldError(id, message) {
        const input = document.getElementById(id);
        const error = document.getElementById(`${id}-error`) || document.getElementById('email-status');
        if (!input || !error) return;

        input.classList.toggle('invalid', Boolean(message));
        input.classList.toggle('valid', !message && input.value.trim() !== '');
        error.style.display = message ? 'flex' : 'none';
        error.innerHTML = message ? `<i class="ti ti-alert-circle" aria-hidden="true"></i> ${message}` : '';
      }

      async function validateEmailAvailability() {
        const emailInput = document.getElementById('email');
        const email = emailInput.value.trim();
        const localError = validators.email(email);
        if (localError) {
          setFieldError('email', localError);
          return false;
        }

        setFieldError('email', '');
        try {
          const response = await fetch(`/api/check-email?email=${encodeURIComponent(email)}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
          });
          // const result = await response.json();
          // const message = result.data?.message || result.errors?.email || result.error;
          // const available = Boolean(result.success && result.data?.available);

          // setFieldError('email', available ? '' : (message || 'Cette adresse email est déjà utilisée.'));

          const result = await response.json();

          console.log(result);

          const message = result.data?.message || result.errors?.email || result.error;
          const available = Boolean(result.data?.available);

          setFieldError(
            'email',
            available ? '' : (message || 'Cette adresse email est déjà utilisée.')
          );

          return available;
        } catch (error) {
          setFieldError('email', "Impossible de vérifier l'email pour le moment.");
          return false;
        }
      }

      async function validateIdentityForm() {
        let isValid = true;
        ['prenom', 'nom', 'password', 'confirm-password'].forEach(id => {
          const message = validators[id](document.getElementById(id).value);
          setFieldError(id, message);
          if (message) isValid = false;
        });

        const emailOk = await validateEmailAvailability();
        return isValid && emailOk;
      }

      async function preparerEtape1() {
        if (!await validateIdentityForm()) return;

        const prenom = document.getElementById('prenom').value.trim();
        const nom = document.getElementById('nom').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;

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

        ['prenom', 'nom', 'password', 'confirm-password'].forEach(id => {
          const input = document.getElementById(id);
          input.addEventListener('blur', () => setFieldError(id, validators[id](input.value)));
          input.addEventListener('input', () => {
            if (input.classList.contains('invalid')) setFieldError(id, validators[id](input.value));
            if (id === 'password' && document.getElementById('confirm-password').value) {
              setFieldError('confirm-password', validators['confirm-password'](document.getElementById('confirm-password').value));
            }
          });
        });

        document.getElementById('email').addEventListener('blur', validateEmailAvailability);
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
      <div class="hint-txt">Vos données sont protégées et ne seront jamais partagées avec des tiers.</div>
    </div>

    <div class="row2">
      <div class="field">
        <div class="flabel"><i class="ti ti-user" aria-hidden="true"></i> Prénom</div>
        <input id="prenom" class="inp" placeholder="Ex: Finaritra">
        <div id="prenom-error" class="err-msg"></div>
      </div>
      <div class="field">
        <div class="flabel"><i class="ti ti-user" aria-hidden="true"></i> Nom</div>
        <input id="nom" class="inp" placeholder="Ex: Rakoto">
        <div id="nom-error" class="err-msg"></div>
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
      <div class="flabel"><i class="ti ti-mail" aria-hidden="true"></i> Email</div>
      <input id="email" class="inp" type="email" placeholder="votre@email.com">
      <div id="email-status" class="err-msg"></div>
    </div>

    <div class="row2">
      <div class="field">
        <div class="flabel"><i class="ti ti-lock" aria-hidden="true"></i> Mot de passe</div>
        <input id="password" class="inp" type="password" placeholder="••••••••">
        <div id="password-error" class="err-msg"></div>
        <div class="pw-bar">
          <div class="ps"></div><div class="ps"></div><div class="ps"></div><div class="ps"></div>
        </div>
      </div>
      <div class="field">
        <div class="flabel"><i class="ti ti-lock-check" aria-hidden="true"></i> Confirmer mot de passe</div>
        <input id="confirm-password" class="inp" type="password" placeholder="••••••••">
        <div id="confirm-password-error" class="err-msg"></div>
      </div>
    </div>

    <div class="btn-row">
      <a href="idfit_connexion.php" class="btn-sec">Annuler</a>
      <button class="btn-main" onclick="preparerEtape1()">
        Continuer <i class="ti ti-arrow-right" aria-hidden="true"></i>
      </button>
    </div>
  </div>
</div>

</body>
</html>
