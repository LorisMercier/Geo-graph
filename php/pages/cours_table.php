<?php
session_start();
$racine = $_SERVER['DOCUMENT_ROOT'];
$p0 = "/php/libs/dataMetier/CountriesDAO.php";
require_once($racine.$p0);

// require_once($racine.$p0);
// CreateQuizFromTemplate("CapitalesEurope");

if(isset($_GET['nameQuiz'])){
  $nameQuiz = $_GET['nameQuiz'];
}

$json = file_get_contents($racine."/database/world/countriesApi.json");
$data = json_decode($json, true);

function comparerPays($a, $b) {
  setlocale(LC_COLLATE, 'fr_FR.utf8'); // Définit la locale appropriée (ici, français UTF-8)
  return strcoll($a['nom'], $b['nom']);
}

// Trie les pays par ordre alphabétique
usort($data, 'comparerPays');

$tableHTML = '<table id="countryTable" class="table">';
$tableHTML .= '<thead><tr><th>Drapeau</th><th>Nom</th><th>Capitale</th><th>Continent</th><th>Population</th><th>Langue(s)</th><th>Devise(s)</th></tr></thead>';
$tableHTML .= '<tbody>';

foreach ($data as $country) {
    $tableHTML .= '<tr>';
    $tableHTML .= '<td><img src="' . $country['drapeau'] . '" alt="Drapeau de ' . $country['nom'] . '" height="30"></td>';
    $tableHTML .= '<td>' . $country['nom'] . '</td>';
    $tableHTML .= '<td>' . $country['capitale'] . '</td>';
    $tableHTML .= '<td>' . $country['continent'] . '</td>';
    $tableHTML .= '<td>' . number_format($country['population'], 0, '', ' ') . '</td>';
    $tableHTML .= '<td>' . implode('<br/>', $country['langue']) . '</td>';
    $tableHTML .= '<td>' . implode(', ', $country['devise']) . '</td>';
    $tableHTML .= '</tr>';
}

$tableHTML .= '</tbody></table>';


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

  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css">

</head>

<body id="Etape 1">


  <?php include($racine . "/php/include/header.php"); ?>

  <!-- ======= Breadcrumbs ======= -->
  <div class="breadcrumbs">
    <div class="page-header d-flex align-items-center">
      <div class="container position-relative">
        <div class="row d-flex justify-content-center">
          <div class="col-lg-10 text-center">
            <h2 id="titreQuiz">Le cours en tableau !</h2>   
          </div>
        </div>
      </div>
    </div>
  </div><!-- End Breadcrumbs -->

  <main class="mainQuiz">  

    <section id="sectionMap">
      <div class="container">
        <?php echo $tableHTML; ?>         
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

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json"></script>
  <script>
      $(document).ready(function() {
          $('#countryTable').DataTable({
              language: {
                  url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json'
              },
              lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "Tout"] ]
          });
      });
  </script>

</body>

</html>