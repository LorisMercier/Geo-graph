<?php
session_start();
$racine = $_SERVER['DOCUMENT_ROOT'];
$p0 = "/php/libs/dataMetier/CountriesDAO.php";

require_once($racine.$p0);
// CreateQuizFromTemplate("CapitalesEurope");

if (isset($_GET['nameQuiz'])) {
  $nameQuiz = $_GET['nameQuiz'];
}

?>


<!DOCTYPE html>
<html lang="fr">

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
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Raleway:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

  <!-- CDN CSS Files -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />

  <!-- Template Main CSS File -->
  <link href="/assets/css/main.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
    integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
    crossorigin=""/>
</head>

<body id="Etape 1">


  <?php include($racine . "/php/include/header.php"); ?>

  <!-- ======= Breadcrumbs ======= -->
  <div class="breadcrumbs">
    <!-- <div class="page-header d-flex align-items-center">
      <div class="container position-relative">
        <div class="row d-flex justify-content-center">
          <div class="col-lg-10 text-center">
            <h2></h2>
          </div>
        </div>
      </div>
    </div> -->
  </div><!-- End Breadcrumbs -->

  <main class="mainQuiz">
    <!-- Section EN BREF : Descriptif de l'étape -->
    <section id="quiz" class="container">

      <div id="quiz-box" class="quiz-box custom-box">
        <!-- <p id="enteteQuestion" class="enteteQuestion"><span id="enteteTxt">Question</span> <span id="entetetxtQuestion" class="hide"><span id="numQuestion">3</span> sur <span class="nbrTotalQuestion">5</span></span></p> -->
        <h2 id="titreQuiz" class="enteteQuestion">TITRE TEST</h2>

        <div id="containerAccueil" class="">
          <p class="enteteQuestion mb-3">Informations </p>
          <p>QCM à réponse unique</p>
          <p>Nombre de question : <span class="nbrTotalQuestion">5</span></p>
          <button id="btnEntrainement" class="btn">Mode entrainement</button>
          <button id="btnEval" class="btn">Mode test</button>
        </div>

        <div id="containerQuestion" class="containerQuestion hide">    
          <div id="ordreQuestion" class="d-flex align-items-center sep-bottom">
            <p id="enteteQuestion" class="enteteQuestion"><span id="enteteTxt">Question</span> <span id="entetetxtQuestion" class="hide"><span id="numQuestion">3</span> sur <span class="nbrTotalQuestion">5</span></span></p>
            <div id="indicateur" class="indicateur hide">
            </div>
          </div>      
          
          <h2 id="titreQuestion"></h2>
          <div id="map"></div>
          <div id="reponsesQuestion" class="row">
            <button class="btn-Reponse"></button>
            <button class="btn-Reponse"></button>
            <button class="btn-Reponse"></button>
            <button class="btn-Reponse"></button>
          </div>
        </div>

        <div id="containerFin" class="containerFin hide">
        <p class="enteteQuestion mb-2">Résultats : </p>
          <table>
            <tr>
              <td>Nombre de question</td>
              <td><span id="total-question">5</td>
            </tr>
            <tr class="tr-train">
              <td>Nombre de tentative</td>
              <td><span id="total-tentative">4</td>
            </tr>
            <tr>
              <td>Réponse correcte</td>
              <td><span id="total-correct">4</td>
            </tr>
            <tr class="tr-train">
              <td>Réponse partielle <i class="bi bi-info-circle" data-toggle="tooltip" data-placement="top" title="Bonne réponse après 1 erreur"></i></td>
              <td><span id="total-partielle">4</td>
            </tr>
            <tr>
              <td>Réponse fausse</td>
              <td><span id="total-faux">4</td>
            </tr>
            <tr>
              <td>Pourcentage réussite</td>
              <td><span id="pourcantageCorrect">4</td>
            </tr>
            <tr class="trTotal">
              <td>Score total</td>
              <td><span id="total-score">4</td>
            </tr>
            <tr>
              <td>Note sur 20</td>
              <td><span id="note">4</td>
            </tr>
          </table>
        </div>

        <div class="apresQuestion">
          <a href="#" id="btnPasse" class="btnPasse hide">Solution<i class="bi bi-skip-end-fill"></i></a>
          <button id="btnSuivant" class="btn hide">Suivant</button>
          <button id="btnRejouer" class="btn hide" data-toggle="tooltip" data-placement="top" title="Rejouer avec les mêmes questions">Rejouer </button>
          <button id="btnHome" class="btn hide">Accueil <i class="bi bi-house-door"></i></button>
          <button id="btnNewQuiz" class="btn hide" data-toggle="tooltip" data-placement="top" title="Rejouer avec de nouvelles questions">Nouveau Quiz </button>
          <p id="scoreEnCours" class="hide">0</p>
        </div>




      </div>

    </section>
    <!-- Fin de section -->

  </main>
  <?php include($racine . "/php/include/footer.php"); ?>

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- CDN JS Files -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

  <!-- Template Main JS File -->
  <script src="/assets/js/main.js"></script>
  <script src="/assets/js/gestionQuiz.js"></script>

  <!-- A IMPORTER QUE SI BESOIN -->
  <script src="/database/worldWithCapital.js"></script>
  <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
  integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
  crossorigin=""></script>
  <?php
  $array = getAllCountries();
  $json = json_encode($array);
  ?>

  <script>
    const countriesArray = <?php echo $json; ?>
  </script>
  <!-- FIN SI BESOIN -->

  <script>
    var tooltips = document.querySelectorAll('[data-toggle="tooltip"]');
    tooltips.forEach(function(tooltip) {
      new bootstrap.Tooltip(tooltip);
    });
  </script>


  <?php
  echo  '<script>';
  echo 'fetch("/database/quiz/' . $nameQuiz . 'Temp.json")';
  echo '.then(response => response.json())';
  echo '.then(jsonData => {
      quiz = jsonData;
      gestionQuiz(quiz); // La variable "data" est accessible ici
    })';
  echo  '</script>';
  ?>


</body>

</html>