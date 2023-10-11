<?php
session_start();
?>

<!doctype html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Geo-Graph</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link rel="apple-touch-icon" sizes="180x180" href="/assets/img/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/assets/img/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/assets/img/favicon/favicon-16x16.png">
  <link rel="manifest" href="/assets/img/favicon/site.webmanifest">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="/assets/css/style.css">

</head>

<body class="vh-100 gradient-custom">
  <section>
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
          <div class="card bg-dark text-white" style="border-radius: 1rem;">
            <div class="card-body p-5 text-center">

              <?php if(isset($_SESSION['user_pseudo'])): ?>
                <div id="formulaire">
                  <h2 class="fw-bold mb-3 text-uppercase">Bonjour <?=$_SESSION['user_pseudo']?>,</h2>
                  <h2 class="fw-bold mb-2 ">Vous êtes déjà connecté...</h2>
                  <p class="text-white-50 mb-1"> Rejoignez vite votre espace !</p>
                  <img src="/assets/img/icon/LogoGlobe.png" class="img-fluid mb-4" alt="Responsive image">

                    <div class="mb-4">
                      <a href="/php/pages/espace_membre.php">
                        <button class="btn btn-outline-light btn-lg px-5 mt-3 " name="monEspaceBtn">Mon espace</button>
                      </a> 
                    </div>

                    <a href="/php/pages/auth/deconnexionCheck.php" class="link-secondary mt-3">
                        Se déconnecter
                      </a>
                </div>
              <?php else: ?>
                <!--Titre-->
                <h2 class="fw-bold mb-2 text-uppercase">Connexion</h2>
                <p class="text-white-50 mb-1">Prêt pour l'aventure ?</p>
                <img src="/assets/img/icon/LogoGlobe.png" width=100 class="img-fluid mb-4" alt="Responsive image">

                <!-- Formulaire-->
                <form class="mb-md-2 mt-md-2" method="post" action="/php/pages/auth/connexionCheck.php">

                  <div class="form-outline form-white mb-4">
                    <label class="form-label" for="textID">Identifiant</label>
                    <input class="form-control form-control-lg <?php echo (isset($_GET['error']))? 'is-invalid' : ''; ?>" type="text" id="textID" name="id" autocomplete="off" required />
                    
                    <div class="invalid-feedback" id="typeIDXError">
                      Identifiant ou mot de passe invalide 
                    </div>
                  </div>

                  <div class="form-outline form-white mb-4 ">
                    <label class="form-label" for="textMDP">Mot de passe</label>
                    <input class="form-control form-control-lg <?php echo (isset($_GET['error']))? 'is-invalid' : ''; ?>" type="password" id="textMDP" name="mdp" autocomplete="off" required />
                    
                    <div class="invalid-feedback" id="typePasswordXError">
                      Identifiant ou mot de passe invalide 
                    </div>
                  </div>

                  <button class="btn btn-outline-light btn-lg px-5 mt-3" type="submit" name="connexionBtn">Se connecter</button>

                </form>
                
                <div class="border-top mt-5 d-grid col-8 mx-auto">
                  <a href="/php/pages/admin/inscription.php" class="link-secondary mt-3">
                    Pas encore inscrit ?
                  </a>
                </div>
              <?php endif; ?>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</body>
</html>