<?php
session_start();
$racine = $_SERVER['DOCUMENT_ROOT'];
$p1 = $racine . "/php/libs/dataMetier/Categories.php";
$p2 = $racine . "/php/libs/dataMetier/Attributes.php";
$p3 = $racine . "/php/libs/dataMetier/CountriesDAO.php";
include($p1);
include($p2);
include($p3);

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

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Raleway:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

  <!-- Template Main CSS File -->
  <link href="/assets/css/main.css" rel="stylesheet">
</head>

<?php
$array = getAllCountries();
$countries = json_encode($array);
$filterAttr = getAllFilterableAttributesName();
$filterAttributes = json_encode($filterAttr);
$val = getValuesByAttributeList($filterAttr, $array);
$values = json_encode($val);
?>

<script>
  var countries = <?php echo $countries; ?>;
  var filterAtrributes = <?php echo $filterAttributes; ?>;
  var values = <?php echo $values; ?>;
</script>

<body class="vh-100 gradient-custom">

  <?php include($racine . "/php/include/header.php"); ?>
    <!-- ======= Breadcrumbs ======= -->
    <div class="breadcrumbs">
    <div class="page-header d-flex align-items-center">
      <div class="container position-relative">
        <div class="row d-flex justify-content-center">
          <div class="col-lg-10 text-center">
            <h2>Création d'une catégorie</h2>
          </div>
        </div>
      </div>
    </div>
  </div><!-- End Breadcrumbs -->

  <main id="createCategories" class="mainCreateCategories mainQuiz">

    <form class="mb-md-2 mt-md-2" id="formCat" method="post" action="/php/pages/server/createCategorie.php">

      <div class="form-outline form-white mb-4 row">
        <label class="label col-md-3 offset-md-2 textInput" for="textID">Nom de la Catégorie :</label>
        <div class="col-md-4">
          <input class="form-control form-control-lg" type="text" id="nomCat" name="nomCat" autocomplete="off" placeholder="Ex : Les Pays d'Europe" />
        </div>
        <div id="errorNomCategorie" class="col-md-4 offset-md-5 invalid-feedback"></div>
      </div>

      <!-- Linked buttons for list/constraint choice -->
      <div class="text-center row">
        <label class="label col-md-3 offset-md-2 textInput" for="textID">Création par :</label>
        <div class="row col-md-3 d-flex justify-content-evenly" id="typeCat" valType="0">
        <button type="button" id="btn-constraint" class="btn-linked width100" onclick=switchCategorieMode(this) disabled>Contraintes</button>
          <button type="button" id="btn-list" class="btn-linked width100" onclick=switchCategorieMode(this)>Liste</button>
          
        </div>
      </div>

      <!-- === Category By Constraint === -->
      <div id="countryConstraintSelection" class="row">
        <div class="col-xs-6 col-md-6">
          <section class="container">

            <div class="custom-box">
              <!--Titre-->
              <h2 class="enteteList">Contraintes</h2>

              <!-- Liste des contraintes -->
              <div id="constraintList" class="constraintList"></div>

              <div class="footerList">
                <button type="button" class="btn-add" onclick=createConstraintBox(filterAtrributes,values)>Ajouter</button>
              </div>
            </div>
          </section>
        </div>

        <div class="col-xs-6 col-md-6">
          <section class="container">

            <div class="custom-box">
              <!--Titre-->
              <h2 class="enteteList">Simulation</h2>

              <!-- Simulation des pays -->
              <div id="simulationList" class="simulationList"></div>

              <div class="footerList">
                <button type="button" class="btn-add" onclick=simulateConstraint(countries)>Simuler</button>
              </div>
            </div>
          </section>
        </div>
      </div><!-- End Category by Constraint -->


      <!-- === Category By List === -->
      <div id="countryListSelection" class="row" hidden="">
        <div class="col-xs-6 col-md-6">
          <section class="container">

            <div class="custom-box">
              <!--Titre-->
              <h2 class="enteteList">Liste</h2>

              <!-- Liste des pays -->
              <div id="countryList" class="countryList"></div>
            </div>
          </section>
        </div>

        <div class="col-xs-6 col-md-6">
          <section class="container">

            <div class="custom-box">
              <!--Titre-->
                <h2 class="enteteList">Sélectionnés : <span id="countryCount">0</span>
                </h2>
              <!-- Liste des pays -->
              <div id="selectedList" class="countryList"></div>
            </div>
          </section>
        </div>
      </div><!-- End Category by List -->

      <div class="text-center">
        <button type="submit" class="btn-add">Valider</button>
      </div>
    </form>

  </main>

  <?php include($racine . "/php/include/footer.php"); ?>

  <script src="/assets/js/creationCategorie.js"></script>

  <script>
    createCountryList(countries)
    createConstraintBox(filterAtrributes, values)

    function verifForm() {
      nomCategorie = document.getElementById('nomCat')
      typeCategorie = document.getElementById('typeCat')

      //erreur sur le nom de la categorie
      if (nomCategorie.value.includes("/")) {
        errorNomCategorie.innerHTML = "Il ne peut pas y avoir de / dans le nom de la Categorie"
        errorNomCategorie.style.display = "block"
        nomQuiz.classList.add("is-invalid")
        return false
      }
      if (nomCategorie.value === "") {
        errorNomCategorie.innerHTML = "Le nom de la Categorie ne peut pas être vide"
        errorNomCategorie.style.display = "block"
        nomCategorie.classList.add("is-invalid")
        console.log("false")
        return false
      }
      errorNomCategorie.style.display = "none"
      nomCategorie.classList.remove("is-invalid")
      nomCategorie.classList.add("is-valid")

      return true;
    }


    //création requete
    const form = document.getElementById("formCat")

    form.addEventListener("submit", function(event) {
      //empeche l'envoie
      event.preventDefault()

      if (verifForm()) {
        const xhr = new XMLHttpRequest()

        xhr.open("POST", form.action, true)
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')

        var name = document.getElementById("nomCat").value
        var type = document.getElementById('typeCat').getAttribute("valType")
        var list
        switch (type) {
          case "0":
            list = getConstraints()
            break;
          case "1":
            list = getSelectedIds()
            break;
        }

        list = JSON.stringify(list)
        console.log(list)
        xhr.send(`name=${name}&type=${type}&list=${list}&submitTemplate=True`)

        xhr.onreadystatechange = function() {
          if (xhr.readyState === 4) {
            console.log("reponse")
            console.log(xhr.responseText)
          }
        }

        alert("La catégorie a bien été créée")
        location.reload()
      }
    })
  </script>

</body>

</html>