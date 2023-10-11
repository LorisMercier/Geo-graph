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
    <div class="page-header d-flex align-items-center">
      <div class="container position-relative">
        <div class="row d-flex justify-content-center">
          <div class="col-lg-10 text-center">
            <h2 id="titreQuiz">Carte Interactive</h2>   
          </div>
        </div>
      </div>
    </div>
  </div><!-- End Breadcrumbs -->

  <main class="mainQuiz">  

    <section id="sectionMap">
        <div class="row gy-4">

          <!-- MAPS -->
          <div class="maps col-md-8">
            <div id="map"></div>
          </div>

          <!-- CARACTERISTIQUES -->
          <div class="caracteristiques col-md-4">
            <div class="flag">
              <img id="flag" src="https://flagcdn.com/fr.svg" width="100%" height="100%"></img>
            </div>
            <div class="txtPays">
              <p>Pays : <span id="paysValue">France</span></p>
              <p>Continent : <span id="continentValue">Europe</span></p>
              <p>Capitale : <span id="capitaleValue">Paris</span></p>
              <p>Population : <span id="populationValue">67 391 582</span></p>
              <p>Langue : <span id="langueValue">Français</span></p>
              <p>Devise : <span id="deviseValue">euro</span></p>
            </div>
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

  <!-- Make sure you put this AFTER Leaflet's CSS -->
  <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
  integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
  crossorigin=""></script>

  <script src="/database/worldWithCapital.js"></script>

  <?php
  $array = getAllCountries();
  $json = json_encode($array);
  ?>

  <script>
    const countriesArray = <?php echo $json; ?>
  </script>

  <script>
    /***********************
     * INIT MAP
     ***********************/
    // Limite de la MAP
    var corner1 = L.latLng(-90, -200),
    corner2 = L.latLng(90, 200),
    bounds = L.latLngBounds(corner1, corner2);

    var map = L.map('map', {
      center: [20, 0],
      zoom: 2,
      maxBounds: bounds,      
    })

    var tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        minZoom: 2,
        maxZoom: 19,
        
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);


    /***********************
     * PARAMETRE
     ***********************/     
    var geojson;  
    const drapeau = document.getElementById("flag");
    const pays = document.getElementById("paysValue");
    const continent = document.getElementById("continentValue");
    const capitale = document.getElementById("capitaleValue");
    const population = document.getElementById("populationValue");
    const langue = document.getElementById("langueValue");
    const devise = document.getElementById("deviseValue");
    var paysOnClick;

    function style(feature) {
        return {
            fillColor: "#000",
            weight: 1,
            opacity: 1,
            color: 'white',
            fillOpacity: 0.3
        };
    }

    function resetLayer() {
      map.eachLayer(function (layer) {
        layer.selected = false

        layer.setStyle({
          fillColor: "#000",
        })
      })
    }

    function highlightFeature(e) {
      var layer = e.target;
      if(!e.target.selected){ 
        layer.setStyle({
              fillColor: "#cccccc",
        });
      }

      info.update(layer.feature.properties.ISO_A3);

    }

    function resetHighlight(e) {
      if(!e.target.selected){
        geojson.resetStyle(e.target);
      }
      info.update();
    }

    function clickMap(e) {
      // Couleur pays selectionné
      var layer = e.target

      map.eachLayer(function (layer) {
          layer.selected=false
          if(paysOnClick){
            geojson.resetStyle(paysOnClick);
          }
      });

      layer.selected = true   
      paysOnClick = e.target     

      layer.setStyle({
            fillColor: "#00CD09"
      });

      //layer.bringToFront();


      let id = e.target.feature.properties.ISO_A3
      if(id in countriesArray){
        pays.innerHTML = countriesArray[id]['nom'] 
        continent.innerHTML = countriesArray[id]['continent']
        capitale.innerHTML = countriesArray[id]['capitale']
        population.innerHTML = countriesArray[id]['population'].toLocaleString()
        langue.innerHTML = countriesArray[id]['langue']
        devise.innerHTML = countriesArray[id]['devise']
        drapeau.setAttribute("src", countriesArray[id]['drapeau'])
      }
    }

    function onEachFeature(feature, layer) {
        layer.on({
            mouseover: highlightFeature,
            mouseout: resetHighlight,
            click: clickMap
        });
    }
    
    // Choropleth
    geojson = L.geoJson(statesData, {
        style: style,
        onEachFeature: onEachFeature
    }).addTo(map);

    //Point Capital
    statesData["features"].forEach(capitale => {
      if(typeof capitale["properties"]["coordinatesCapital"] !== "undefined"){
        var circle = L.circle([capitale["properties"]["coordinatesCapital"][1],capitale["properties"]["coordinatesCapital"][0]], {
          color: 'red',
          fillColor: '#f03',
          fillOpacity: 0.5,
          radius: 500
        }).addTo(map);
        circle.bindPopup(capitale["properties"]["capital"]);
      }      
    });
    


    /***********************
     * INFO
     ***********************/
    var info = L.control();

    info.onAdd = function (map) {
        this._div = L.DomUtil.create('div', 'info'); // create a div with a class "info"
        this.update();
        return this._div;
    };

    // method that we will use to update the control based on feature properties passed
    info.update = function (id) {        
        this._div.innerHTML = ((id && id in countriesArray) ?
            '<b>' + countriesArray[id]["nom"] + '</b><br />'
            : 'Survole et clique sur un pays !');
    };

    info.addTo(map);
  </script>



</body>

</html>