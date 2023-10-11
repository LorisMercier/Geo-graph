<?php
session_start();
$racine = $_SERVER['DOCUMENT_ROOT'];
$p0 = "/php/libs/dataMetier/Categories.php";
$p1 = "/php/libs/dataMetier/Templates.php";
require_once($racine.$p0);
require_once($racine.$p1);

if (isset($_SESSION['user_pseudo'])) {
  $nom = $_SESSION['user_pseudo'];
} else {
  $nom = "";
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

  <!-- CDN CSS Files -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />

  <!-- Template Main CSS File -->
  <link href="/assets/css/main.css" rel="stylesheet">
</head>

<body>

  <?php include($racine . "/php/include/header.php"); ?>

  <!-- ======= Hero Section ======= -->
  <section id="hero" class="hero">
    <div class="container position-relative">
      <div class="row gy-5" data-aos="fade-in">
        <div class="col-lg-8 order-2 order-lg-1 d-flex flex-column justify-content-center text-center text-lg-start">
          <h2>Bienvenue <?= $nom ?> !</h2>
          <p>Avec Géo-graph, venez découvrir et apprendre la géographie tout en vous amusant ! </p>
          <div class="d-flex justify-content-center justify-content-lg-start">
            <a href="#about" class="btn-get-started">Informations</a>
          </div>
        </div>  
        <div class="col-lg-4 order-1 order-lg-2">
          <img width=300 src="assets/img/icon/Globe.png" class="img-fluid" alt="" data-aos="zoom-in" data-aos-delay="100">
        </div>
      </div>
    </div>

    <div class="icon-boxes position-relative">
      <div class="container containerBox position-relative">
        <div class="row gy-4 mt-5">

          <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="icon-box">
              <div class="icon"><i class="bi bi-globe-europe-africa"></i></div>
              <h4 class="title"><a href="/php/pages/map.php" class="stretched-link">Apprendre </a></h4>
            </div>
          </div>
          <!--End Icon Box -->

          <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="icon-box">
              <div class="icon"><i class="bi bi-play"></i></div>
              <h4 class="title"><a href="#about" class="stretched-link">Jouer</a></h4>
            </div>
          </div>
          <!--End Icon Box -->

          <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="icon-box">
              <div class="icon"><i class="bi bi-plus-square"></i></div>
              <h4 class="title"><a href="/php/pages/creation_quiz.php" class="stretched-link">Créer un quiz</a></h4>
            </div>
          </div>
          <!--End Icon Box -->

          <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="500">
            <div class="icon-box">
              <div class="icon"><i class="bi bi-plus-square"></i></div>
              <h4 class="title"><a href="/php/pages/creation_categorie.php" class="stretched-link">Créer une catégorie</a></h4>
            </div>
          </div>
          <!--End Icon Box -->

        </div>
      </div>
    </div>
    </div>

  </section>
  <!-- End Hero Section -->

  <main id="main">

    <!-- ======= About Us Section ======= -->
    <section id="about" class="about">
      <div class="container">
        <div class="section-header">
          <h2>Découvrir</h2>
          <p class="w-75 mx-auto">Vous voulez apprendre les capitales d’Europe ? Les drapeaux du G20 ? La langue officielle de tous les pays commençant par la lettre L ?
          Avec GEO-Graph, créez vos propres quiz et défiez vos amis.
          Profitez également de notre carte interactive pour réviser et en apprendre plus sur la géographie qui VOUS intéresse.
          Visualisez votre progression et boostez vos points faibles.
          </p>

        </div>
      </div>
    </section><!-- End About Us Section -->

    <!-- ======= Clients Section ======= -->
    <section id="clients" class="clients">
      <div class="container">
        <div class="section-header">
          <h2>Catégories</h2>
        </div>

        
        <?php
          // AFFICHAGE DES CATEGORIES

          $categories  = getAllCategorieName();
          echo '<div class="accordion" id="accordionExample">';
          foreach ($categories as $index => $category) {
            $templates = getTemplatesAllByCategorie($category);
            echo '<div class="accordion-item">';
            echo '<h2 class="accordion-header" id="heading' . $index . '">';
            echo '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse' . $index . '" aria-expanded="false" aria-controls="collapse' . $index . '">';
            echo $category;
            echo '</button>';
            echo '</h2>';
            echo '<div id="collapse' . $index . '" class="accordion-collapse collapse" aria-labelledby="heading' . $index . '" data-bs-parent="#accordionExample">';
            echo '<div class="accordion-body">';
            // AFFICHAGE DES TEMPLATES
            echo '<ul>';
            foreach (array_keys($templates) as $key) {
              if(key_exists("type", $templates[$key])){
                $name = "[".$templates[$key]['type']."] ".$templates[$key]['nomQuiz'];
              } else {
                $name = $templates[$key]['nomQuiz'];
              }
              echo '<li><a href=/php/pages/server/createQuiz.php?nameQuiz=' . $key . '>' . $name . '</a></li>';
            }
            echo '</ul>';            
            echo '</div>';
            echo '</div>';
            echo '</div>';
          }
          echo '</div>';
        ?>        

      </div>
    </section><!-- End Clients Section -->


  </main><!-- End #main -->
  <?php include($racine . "/php/include/footer.php"); ?>


  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <div id="preloader"></div>

  <!-- CDN JS Files -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  
  <script>
  /**
   * Scroll sur les tableaux
   */
  var tabCollapse = document.querySelector('button[data-bs-toggle="collapse"]');
  console.log(tabCollapse)
  tabCollapse.addEventListener('shown.bs.collapse', function () {
      console.log("HELLO")
      // Faites défiler l'écran jusqu'à l'élément cible
      tabCollapse.scrollIntoView({
          behavior: 'smooth'
      });
  });
    


  </script>

</body>

</html>