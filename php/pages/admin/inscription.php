<?php
session_start();
$racine = $_SERVER['DOCUMENT_ROOT'];
$p1 = $racine."/php/libs/UsersDAO.php";
$p2 = $racine."/php/libs/logFunction.php";
include($p1);
include($p2);


if(isset($_GET['error'])) {
  $code = $_GET['error'];
} else {
  $code = -1;
}

?>

<!DOCTYPE html>
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

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Raleway:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

</head>

<body class="vh-100 gradient-custom">
  <section>
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
          <div class="card bg-dark text-white" style="border-radius: 1rem;">
            <div class="card-body p-5 text-center">

              <?php if($code != 0): ?>
                <!--Titre-->
                <h2 class="fw-bold mb-2 text-uppercase">Inscription</h2>
                <p class="text-white-50 mb-1">L'aventure vous tente ?</p>
                <img src="/assets/img/icon/LogoGlobe.png" width="60px" class="img-fluid mb-4" alt="Responsive image">

                <!-- Formulaire-->
                <form class="mb-md-2 mt-md-2" method="post" action="../auth/inscriptionCheck.php">

                  <div class="form-outline form-white mb-4">
                    <label class="form-label" for="textID">Identifiant</label>
                    <input class="form-control form-control-lg <?php echo ( $code==1 || $code==4)? 'is-invalid' : ''; ?>" type="text" id="textID" name="id" autocomplete="off" required />

                    <div class="invalid-feedback" id="typeIDXError">
                      <?php echo ( $code==1 )? 'Identifiant déjà utilisé' : 'Champs vides'; ?>
                    </div>
                  </div>

                  <div class="form-outline form-white mb-4 ">
                    <label class="form-label" for="textMDP">Mot de passe</label>
                    <input class="form-control form-control-lg <?php echo ( $code>=2 )? 'is-invalid' : ''; ?>" type="password" id="textMDP" name="mdp" autocomplete="off" required />

                    <div class="invalid-feedback" id="typePasswordXError">
                      <?php echo ( $code==2 )? 'Mot de passe différent' : 'Champs vides'; ?> 
                    </div>
                  </div>

                  <div class="form-outline form-white mb-4 ">
                    <label class="form-label" for="textMDP2">Confirmation mot de passe</label>
                    <input class="form-control form-control-lg <?php echo ( $code>=2 )? 'is-invalid' : ''; ?>" type="password" id="textMDP2" name="mdp2" autocomplete="off" required />

                    <div class="invalid-feedback" id="typePasswordConfirmXError">
                      <?php echo ( $code==2 )? 'Mot de passe différent' : 'Champs vides'; ?> 
                    </div>
                  </div>

                  <button class="btn btn-outline-light btn-lg px-5 mt-3" type="submit" name="validationBtn">Valider</button>

                </form>
                <div class="border-top mt-5 d-grid gap-4 col-6 mx-auto">
                  <a href="../auth/connexionCheck.php" class="link-secondary mt-3">
                    Retour
                  </a>
                </div>
              <?php else: ?>
                <div class="d-flex flex-column align-items-center">
                <h2 class="fw-bold mb-2 text-uppercase">Inscription confirmée    <i class="bi-check-circle"></i></h2>
                
                <p class="text-white-50 mb-1">Bienvenue !</p>
                <img src="/assets/img/icon/LogoGlobe.png" width="60px" class="img-fluid mb-4" alt="Responsive image">
                <a href="/php/pages/connexion.php">
                  <button class="btn btn-outline-light btn-lg px-5 mt-3 " name="connexionBtn">Connexion</button>
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