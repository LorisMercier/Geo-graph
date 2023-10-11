<?php
session_start();
$racine = $_SERVER['DOCUMENT_ROOT'];
$path = $racine . "/php/libs/UsersDAO.php";
$p0 = "/php/libs/dataMetier/Categories.php";
$p1 = "/php/libs/dataMetier/Templates.php";
require_once($racine . $p0);
require_once($racine . $p1);
require_once($path);


if (!isLogged()) {
  logout();
  header("Location: /index.php");
}

?>


<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Rhône-Alpes Express</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link rel="apple-touch-icon" sizes="180x180" href="/assets/img/favicon3/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/assets/img/favicon3/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/assets/img/favicon3/favicon-16x16.png">
  <link rel="manifest" href="/assets/img/favicon3/site.webmanifest">
  <link rel="mask-icon" href="/assets/img/favicon3/safari-pinned-tab.svg" color="#5bbad5">
  <meta name="msapplication-TileColor" content="#b91d47">
  <meta name="theme-color" content="#ffffff">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Raleway:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

  <!-- CDN CSS Files -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />

  <!-- Template Main CSS File -->
  <link href="/assets/css/main.css" rel="stylesheet">
</head>

<body>
  <div class="tailleMin">


    <?php include($racine . "/php/include/header.php"); ?>

    <!-- ======= Breadcrumbs ======= -->
    <div class="breadcrumbs">
      <div class="page-header d-flex align-items-center">
        <div class="container position-relative">
          <div class="row d-flex justify-content-center">
            <div class="col-lg-6 text-center">
              <h2>Mon profil</h2>
              <p>Retrouver toutes vos statistiques de jeu !</p>
            </div>
          </div>
        </div>
      </div>
    </div><!-- End Breadcrumbs -->

    <main id="main" class="container">
      <div class="TitleDiv mt-3">
        <h2>Mes statistiques</h2>
      </div>

      <?php
      // AFFICHAGE DES CATEGORIES
      $categories  = getAllCategorieName();
      $user = getOneUser($_SESSION['user_pseudo']);

      echo '<div class="accordion" id="accordionExample">';
      foreach ($categories as $index => $category) {
        $templates = getTemplatesAllByCategorie($category);

        // Si moyenne existante pour catégorie
        if (array_key_exists("stats", $user) && array_key_exists($category, $user["stats"])) {
          $barCat = '<div class="progress progressCat">
        <div class="progress-bar barYellow" role="progressbar" aria-label="Example with label" style="width:' . $user["stats"][$category]["mean"] . '%;" aria-valuenow="' . $user["stats"][$category]["mean"] . '" aria-valuemin="0" aria-valuemax="100">' . $user["stats"][$category]["mean"] . '%</div>
      </div>';
        } else {
          $barCat = '<div class="progress progressCat">
          <div class="progress-bar" role="progressbar" aria-label="Basic example" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        ';
        }



        echo '<div class="accordion-item">';
        echo '<h2 class="accordion-header" id="heading' . $index . '">';
        echo '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse' . $index . '" aria-expanded="false" aria-controls="collapse' . $index . '">';
        echo $category . "    " . $barCat;
        echo '</button>';
        echo '</h2>';
        echo '<div id="collapse' . $index . '" class="accordion-collapse collapse" aria-labelledby="heading' . $index . '" ">';
        echo '<div class="accordion-body">';
        // AFFICHAGE DES TEMPLATES
        echo '<ul>';
        foreach (array_keys($templates) as $key) {
          $type = $templates[$key]['type'];
          $nomquiz = $templates[$key]['nomQuiz'];
          if (key_exists("type", $templates[$key])) {
            $name = "[" . $templates[$key]['type'] . "] " . $templates[$key]['nomQuiz'];
          } else {
            $name = $templates[$key]['nomQuiz'];
          }

          if (isset($user["stats"][$category][$nomquiz][$type]["moyenne"])) {
            $barQuiz = '<div class="progress progressCat">
              <div class="progress-bar barGreen" role="progressbar" aria-label="Example with label" style="width:' .
              $user["stats"][$category][$nomquiz][$type]["moyenne"] .
              '%;" aria-valuenow="' . $user["stats"][$category][$nomquiz][$type]["moyenne"] .
              '" aria-valuemin="0" aria-valuemax="100">' . $user["stats"][$category][$nomquiz][$type]["moyenne"] . '%</div>
            </div>';
          } else {
            $barQuiz = '<div class="progress progressCat">
          <div class="progress-bar" role="progressbar" aria-label="Basic example" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        ';
          }


          echo '<li><div class="li-withBar"><a href=/php/pages/server/createQuiz.php?nameQuiz=' . $key . '>' . $name . '</a>' . $barQuiz . '</div></li>';
        }
        echo '</ul>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
      }
      echo '</div>';
      ?>

    </main>
  </div>




  <?php include($racine . "/php/include/footer.php"); ?>



  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- CDN JS Files -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@srexi/purecounterjs/dist/purecounter_vanilla.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
  <script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js"></script>

  <!-- Template Main JS File -->
  <script src="/assets/js/main.js"></script>

</body>

</html>