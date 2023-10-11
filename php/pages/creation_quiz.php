<?php
session_start();
$racine = $_SERVER['DOCUMENT_ROOT'];
$p1 = $racine . "/php/libs/dataMetier/Categories.php";
$p2 = $racine . "/php/libs/dataMetier/Attributes.php";
include($p1);
include($p2);

?>

<!doctype html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Création Quiz</title>
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

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

  <!-- Template Main CSS File -->
  <link href="/assets/css/main.css" rel="stylesheet">
</head>

<script src="/assets/js/creationQuiz.js"></script>

<body class="vh-100 gradient-custom">

  <?php include($racine . "/php/include/header.php"); ?>

  <!-- ======= Breadcrumbs ======= -->
  <div class="breadcrumbs">
    <!-- 
    <div class="page-header d-flex align-items-center">
      <div class="container position-relative">
        <div class="row d-flex justify-content-center">
          <div class="col-lg-10 text-center">
            <h2 id="titreQuiz"></h2>
          </div>
        </div>
      </div>
    </div>
    -->
  </div><!-- End Breadcrumbs -->

  <main class="mainCreateQuiz mainQuiz">

    <section id="quizCreation" class="container">

      <div id="custom-box" class="custom-box">
        <!--Titre-->
        <h2 class="entete">Création d'un quiz</h2>

        <!-- Formulaire-->
        <form class="mb-md-2 mt-md-2" method="post" action="/php/pages/server/createTemplate.php" onsubmit="return verifForm()">

          <div class="form-outline form-white mb-4">
            <label class="label" for="textID">Nom du Quiz (pas de / dans le nom)</label>
            <input class="form-control form-control-lg " type="text" id="nomQuiz" name="nomQuiz" autocomplete="off" placeholder="Ex : Les Capitales du Monde" />
            <div id="errorNomQuiz" class="invalid-feedback"></div>
          </div>          

          <div class="form-outline form-white mb-4">
            <label class="label" for="textCat">Catégorie</label>
            <select class="form-select form-select-lg" id="categorie" name="categorie">
            <option class="dropdownDefault" value="" selected hidden>Choisissez une catégorie</option>
            <!--TODO Mettre le texte par défaut en gris/italique -->
              <?php
              foreach (getAllCategorieName() as $val) {
                echo '<option value="' . $val . '">' . $val . '</option>';
              }
              ?>
            </select>
            <div id="errorCategorie" class="invalid-feedback"></div>
          </div>

          <div class="form-outline form-white mb-4 ">
            <label class="label" for="textAttrQuest">Type de Quiz</label>
            <select class="form-select form-select-lg" id="attrType" name="attrType" onchange=updateForm(this)>
              <option value="" selected hidden>Choisissez un attribut</option>
              <option value="qcm">QCM</option>
              <option value="loc">Localisation</option>
            </select>
            <div id="errorType" class="invalid-feedback"></div>
          </div>  

          <div class="form-outline form-white mb-4 ">
            <label class="label" for="nbQuest">Nombre de questions</label>
            <input class="form-control form-control-lg" onchange="preventNumberInputOverflow(this)"  type="number" min="1" max="20" value="5" id="nbQuestion" name="nbQuestion" autocomplete="off" required />
          </div>

          <div class="form-outline form-white mb-4 " id="champNbProp" hidden>
            <label class="label" for="nbProp">Nombre de propositions</label>
            <input class="form-control form-control-lg" onchange="preventNumberInputOverflow(this)" type="number" min="2" max="8" value="4" id="nbProposition" name="nbProposition" autocomplete="off" required />
          </div>

          <div class="form-outline form-white mb-4 " id="champQuestion" hidden>
            <label class="label" for="textAttrQuest">Les questions seront posées sur </label>
            <select class="form-select form-select-lg" id="attrQuestion" name="attrQuestion">
              <option value="" selected hidden>Choisissez un attribut</option>
              <?php 
              foreach (getAllUniqueAttributesName() as $val) {
                echo '<option value="' . $val . '">' . $val . '</option>';
              }
              ?>
            </select>
            <div id="errorQuestion" class="invalid-feedback"></div>

          </div>

          <div class="form-outline form-white mb-4" id="champReponse" hidden>
            <label class="label" for="textAttrRep">La réponse attendu sera</label>
            <select class="form-select form-select-lg" id="attrReponse" name="attrReponse">
              <option value="" selected hidden>Choisissez un attribut</option>
              <?php
              foreach (getAllAttributesName() as $val) {
                echo '<option value="' . $val . '">' . $val . '</option>';
              }
              ?>
            </select>
            <div id="errorReponse" class="invalid-feedback"></div>
          </div>

          <div class="form-outline form-white mb-4 " id="champLoc"s hidden>
            <label class="label" for="textAttrQuest">Ce qu'il faut localiser</label>
            <select class="form-select form-select-lg" id="attrLoc" name="attrLoc">
              <option value="nom">Pays</option>
              <option value="capitale">Capitale</option>
            </select>
          </div>

          <div class="btn-center">
            <button class="btn-submit" name="submitTemplate" type="submit">Valider</button>
          </div>
        </form>

      </div>

    </section>
  </main>

  <?php include($racine . "/php/include/footer.php"); ?>

  <script src="/assets/js/utils.js"></script>
  <script>
    function verifForm(){
      nomQuiz = document.getElementById("nomQuiz")
      categorie = document.getElementById("categorie")
      nbQuestion = document.getElementById("nbQuestion")
      nbProposition = document.getElementById("nbProposition")
      attrType = document.getElementById("attrType")
      attrQuestion = document.getElementById("attrQuestion")
      attrReponse = document.getElementById("attrReponse")
      attrLoc = document.getElementById("attrLoc")
      errorNomQuiz = document.getElementById("errorNomQuiz")

      //erreur sur le nom du quiz
      if(nomQuiz.value.includes("/")){
        errorNomQuiz.innerHTML = "Il ne peut pas y avoir de / dans le nom du quiz"
        errorNomQuiz.style.display = "block"
        nomQuiz.classList.add("is-invalid")
        return false
      }
      if(nomQuiz.value=== ""){
        errorNomQuiz.innerHTML = "Le nom du Quiz ne peut pas être vide"
        errorNomQuiz.style.display = "block"
        nomQuiz.classList.add("is-invalid")
        return false
      }
      errorNomQuiz.style.display = "none"
      nomQuiz.classList.remove("is-invalid")
      nomQuiz.classList.add("is-valid")

      //erreur sur la catégorie
      if(categorie.value === ""){
        errorCategorie.innerHTML = "Il faut choisir une catégorie"
        errorCategorie.style.display = "block"
        categorie.classList.add("is-invalid")
        return false
      }
      errorCategorie.style.display = "none"
      categorie.classList.remove("is-invalid")
      categorie.classList.add("is-valid")

      //pas d'erreur possible pour les champs number et attr type
      nbQuestion.classList.add("is-valid")
      nbProposition.classList.add("is-valid")
      attrLoc.classList.add("is-valid")

      //erreur sur le type de quiz
      if(attrType.value === ""){
        errorType.innerHTML = "Il faut choisir un type"
        errorType.style.display = "block"
        attrType.classList.add("is-invalid")
        return false
      }
      errorType.style.display = "none"
      attrType.classList.remove("is-invalid")
      attrType.classList.add("is-valid")

      //erreur sur l'attribut question
      if(attrQuestion.value === "" && attrType.value !== "loc"){
        errorQuestion.innerHTML = "Il faut choisir un attribut"
        errorQuestion.style.display = "block"
        attrQuestion.classList.add("is-invalid")
        console.log("here")
        return false
      }
      errorQuestion.style.display = "none"
      attrQuestion.classList.remove("is-invalid")
      attrQuestion.classList.add("is-valid")

      //erreur sur l'attribut réponse
      if(attrReponse.value === "" && attrType.value !== "loc"){
        errorReponse.innerHTML = "Il faut choisir un attribut"
        errorReponse.style.display = "block"
        attrReponse.classList.add("is-invalid")
        console.log("here2")
        return false
      }
      errorReponse.style.display = "none"
      attrReponse.classList.remove("is-invalid")
      attrReponse.classList.add("is-valid")

      return true
    }

    
  </script>
</body>

</html>