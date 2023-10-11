/* On selectionne les composants */
const titreQuiz = document.getElementById("titreQuiz")
const entetetxt = document.getElementById("enteteTxt")
const enteteQuestion = document.getElementById("entetetxtQuestion")
const containerAccueil = document.getElementById("containerAccueil")
const containerQuestion = document.getElementById("containerQuestion")
const containerFin = document.getElementById("containerFin")

const SpanNbrQuestion = document.getElementsByClassName("nbrTotalQuestion")
const SpanNumQuestion = document.getElementById("numQuestion")
const titreQuestion = document.getElementById("titreQuestion")
const reponsesQuestion = document.getElementById("reponsesQuestion")
let btnBegin = document.getElementById("btnBegin")
let btnSuivant = document.getElementById("btnSuivant")
let btnHome = document.getElementById("btnHome")
let btnNewQuiz = document.getElementById("btnNewQuiz")
const btnPasse = document.getElementById("btnPasse")
const btnRejouer = document.getElementById("btnRejouer")
const scoreTxt = document.getElementById("scoreEnCours")
const indicateur = document.getElementById("indicateur")

const divMap = document.getElementById("map")

/* Déclaration des variables */
let run = false;
let numQuestion = 0;
let score = 0;
let scoreQuestion = 1;
let nbrTentativeQuestion = 0;
let nbrTentativeTotal = 0;
let quiz = null;
let tabQuestions = Array();
let mode = ""

let reponseCorrect = 0;
let reponsePartielle = 0;
let reponseFausse = 0;

let questionCourante;
let boolMap;


/* Gestion du quiz */
function gestionQuiz(quiz) {
  // Affichage des titres
  titreQuiz.innerHTML = quiz.nomQuiz.toUpperCase()
  entetetxt.innerHTML = "Informations"

  // Chargement des données
  tabQuestions = quiz.questions
  Array.from(SpanNbrQuestion).forEach(e => {
    e.innerHTML = tabQuestions.length
  });


  btnEntrainement.addEventListener("click", () => {
    mode = "entrainement"
    lancerQuiz();
  })

  btnEval.addEventListener("click", () => {
    mode = "eval"
    lancerQuiz();
  })

  btnSuivant.addEventListener("click", () => {
    if (run) {
      if (numQuestion < tabQuestions.length) {
        majScore()
        changeQuestion()
      }
    }
    else {
      resetQuiz();
      run = false
    }
  })

  btnNewQuiz.addEventListener("click", () => {
    dest = "/php/pages/server/createQuiz.php?nameQuiz=" + quiz.templateName
    window.location.href = dest
  })


  btnHome.addEventListener("click", () => {
    dest = "/index.php"
    window.location.href = dest    
  })

  btnRejouer.addEventListener("click", () => {
      window.location.reload()    
  })

  btnPasse.addEventListener("click", () => {
    nbrTentativeTotal = nbrTentativeTotal + nbrTentativeQuestion
    scoreQuestion = 0;
    if(questionCourante.type == "carte"){
      if(questionCourante.attrReponse == "capitale"){
        affichageBonneReponseCARTECAPITAL()
      } else{
        affichageBonneReponseCARTE()
      }

    } else {
      //changeQuestion()
      affichageBonneReponse(true)
    }
    
    //changeQuestion()
  })

}


function lancerQuiz() {
  //Initialisation des variables
  numQuestion = 0;
  score = 0;
  run = true;

  reponseCorrect = 0;
  reponsePartielle = 0;
  reponseFausse = 0;

  scoreQuestion = 1;
  nbrTentativeQuestion = 0;
  nbrTentativeTotal = 0;


  //Changement d'affichage  
  containerAccueil.classList.add("hide")
  containerQuestion.classList.remove("hide")
  entetetxt.innerHTML = "Questions"
  enteteQuestion.classList.remove("hide")
  indicateur.classList.remove("hide")
  btnSuivant.classList.remove("hide")

  // Mise à jour des textes
  btnSuivant.innerHTML = "Suivant"
  scoreTxt.innerHTML = 0
  scoreTxt.classList.toggle("hide")
  if (mode != "eval") {
    btnPasse.classList.remove("hide")
  }
  afficherQuestion();
  createIndicateur();
}

function afficherQuestion() {
  //On supprime l'affichage actuel
  resetQuestion();
  btnSuivant.disabled = true
  SpanNumQuestion.innerHTML = numQuestion + 1;

  // Récupération de la question à afficher
  questionCourante = tabQuestions[numQuestion];
  scoreQuestion = 1;
  nbrTentativeQuestion = 0

  // Affichage du titre
  titreQuestion.innerHTML = questionCourante.intitule

  if (questionCourante.type != "carte") {
    afficheReponseQCM(questionCourante)
  } else {
    afficheReponseCarte(questionCourante)
  }

}
/*************************************
 * ***********************************
 * ************ POUR QCM *************
 * ***********************************
 ************************************/
function afficheReponseQCM(questionCourante) {
  divMap.classList.add("hide")
  //Ordre aléatoires des réponses
  questionCourante.proposition.sort(function (a, b) {
    return Math.random() - 0.5;
  })

  // Affichage des questions
  questionCourante.proposition.forEach(reponse => {
    const bouton = document.createElement("button");
    if (questionCourante.type != "image") {
      bouton.innerHTML = reponse.nom
    } else {
      const img = document.createElement("img");
      img.setAttribute("src", reponse.nom)

      const divImg = document.createElement("div");
      divImg.classList.add("img-quiz-reponse")

      divImg.appendChild(img)
      bouton.appendChild(divImg)
    }

    bouton.classList.add("btn-Reponse")
    bouton.classList.add("col-12")
    bouton.classList.add("col-sm-5")
    reponsesQuestion.appendChild(bouton)

    if (reponse.etat) {
      bouton.dataset.etat = reponse.etat
    }

    bouton.addEventListener("click", clickReponse);
  });
}

function resetQuestion() {
  if(boolMap){
    // On remet le style par défaut de chaque pays
    map.eachLayer(function (layer) {
      if("feature" in layer){
        layer.selected = false        
      }
    });
  }else {
    while (reponsesQuestion.firstChild) {
      reponsesQuestion.removeChild(reponsesQuestion.firstChild)
    }
  }
  boolMap = false
}

function clickReponse(e) {
  const boutonSelect = e.currentTarget
  const correct = boutonSelect.dataset.etat === "true"
  nbrTentativeQuestion = nbrTentativeQuestion + 1


  // BONNE REPONSE
  if (correct) {
    boutonSelect.classList.add('vrai')
    updateIndicateur('vrai')
    // On affiche l'état de tous les boutons
    affichageBonneReponse(false)

  } else { //MAUVAISE REPONSE    
    scoreQuestion = 0
    if (mode == "eval") {
      updateIndicateur('faux')
      nbrTentativeQuestion = 10
      // On affiche l'état de tous les boutons
      affichageBonneReponse(true)
    }
    else {
      boutonSelect.classList.add('faux')
      if (nbrTentativeQuestion == 1) {
        updateIndicateur('partielle')
        scoreQuestion = 0.5
      }
      else {
        updateIndicateur('partielle', false)
        updateIndicateur('faux')
      }
      boutonSelect.disabled = true;

    }
  }
}

function affichageBonneReponse(colorBad) {
  // On affiche l'état de tous les boutons
  Array.from(reponsesQuestion.children).forEach(bouton => {
    if (bouton.dataset.etat === "true") {
      bouton.classList.add('vrai')
      bouton.disabled = true
    } else {
      if (colorBad) {
        bouton.classList.add('faux')
      }
      bouton.disabled = true
    }
  })
  
  btnSuivant.disabled = false
  //majScore()
}

function majScore(){
  btnSuivant.disabled = false
  score = score + scoreQuestion;
  scoreTxt.innerHTML = score
  nbrTentativeTotal = nbrTentativeTotal + nbrTentativeQuestion

  if(nbrTentativeQuestion != 0){
    if (nbrTentativeQuestion == 1) {
      reponseCorrect++
    } else if (nbrTentativeQuestion == 2) {
      reponsePartielle++
    } else {
      reponseFausse++
    }
  }
  
}

/***************** 
 * FIN QCM 
 * **************** */

/*************************************
 * ***********************************
 * *********** POUR CARTE ************
 * ***********************************
 ************************************/
var geojson;
var map;
var circleLayerGroup;
var layersDict = {};

function createMapPays(){
  geojson = L.geoJson(statesData, {
    style: style,
    onEachFeature: onEachFeature
  }).addTo(map);

}

function createMapCapitale(){
  geojson = L.geoJson(statesData, {
    style: style,
  }).addTo(map);

  // Créer un LayerGroup pour les cercles
  circleLayerGroup = L.layerGroup().addTo(map);
  
  //Point Capital
  statesData["features"].forEach(capitale => {
    if(typeof capitale["properties"]["coordinatesCapital"] !== "undefined"){
      if(capitale["properties"]["capital"] == "Rome"){
        var circle = L.circle([capitale["properties"]["coordinatesCapital"][1],capitale["properties"]["coordinatesCapital"][0]], {
          color: '#0018AE',
          fillColor: '#A9E0FF',
          fillOpacity: 1,
          radius: 15000
        }).addTo(circleLayerGroup)
      }
      else if(capitale["properties"]["capital"] == "Vatican"){
        var circle = L.circle([capitale["properties"]["coordinatesCapital"][1],capitale["properties"]["coordinatesCapital"][0]], {
          color: '#0018AE',
          fillColor: '#A9E0FF',
          fillOpacity: 1,
          radius: 5000
        }).addTo(circleLayerGroup)
      } else {
        var circle = L.circle([capitale["properties"]["coordinatesCapital"][1],capitale["properties"]["coordinatesCapital"][0]], {
          color: '#0018AE',
          fillColor: '#A9E0FF',
          fillOpacity: 1,
          radius: 10000
        }).addTo(circleLayerGroup)
      }


      circle.on('click', onClickCapital);
      circle.on('mouseover', highlightCapital);
      circle.on('mouseout', resethighlightCapital);

      circle.ISO_A3= capitale["properties"]["ISO_A3"]
      circle.CAPITALE= capitale["properties"]["capital"]
      circle.bindPopup(capitale["properties"]["capital"]);
    }      
  }); 
}

function afficheReponseCarte(questionCourante) {
  divMap.classList.remove("hide")
  boolMap = true;
  /***********************
  * INIT MAP
  ***********************/
  // Limite de la MAP
  var corner1 = L.latLng(-90, -200),
  corner2 = L.latLng(90, 200),
  bounds = L.latLngBounds(corner1, corner2);

  // ZOOM
  if(map){
    map.off();
    map.remove();
  }
  map = L.map('map', {
  center: [20, 0],
  zoom: 2,
  maxBounds: bounds,
  })

  // Fond de carte
  var tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  minZoom: 2,
  maxZoom: 19,

  attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
  //id: 'mapbox/light-v9',
  }).addTo(map);

  if(questionCourante.attrReponse == "capitale"){
    createMapCapitale();
  } else {
    createMapPays();
  }    
}



//Style de la carte
function style(feature) {
  return {
    fillColor: "#fff",
    weight: 1,
    opacity: 1,
    color: '#000',
    fillOpacity: 1
  };
}

/*******************
 * PAYS
 * 
 */

// HOVER des pays
function highlightFeature(e) {
  var layer = e.target;
  if(!e.target.selected){ 
    layer.setStyle({
          fillColor: "#cccccc",
    });
  }
}


// RESET hover pays
function resetHighlight(e) {
  if(!e.target.selected){
    geojson.resetStyle(e.target);
  }
}

// On applique ces paramètres à chaque pays
function onEachFeature(feature, layer) {
  layer.layerID = layer.feature.properties.ISO_A3
  if(layer.layerID in countriesArray){
    layer.bindPopup(countriesArray[layer.layerID]['nom'])
  } 
  else {
    layer.bindPopup("Cette zone n'est pas un pays");
  }

  layer.on({
    mouseover: highlightFeature,
    mouseout: resetHighlight,
    click: clickMap
  });
}


/* GESTION CLIQUE sur PAYS */
function clickMap(e) {
  // Couleur pays selectionné
  var layer = e.target
  if(!layer.selected){
    layer.selected = true

    nbrTentativeQuestion = nbrTentativeQuestion + 1
    // BONNE Reponse
    let iso3Select = layer.feature.properties.ISO_A3;
    if( iso3Select == questionCourante.reponseIso3){
      updateIndicateur('vrai')
      affichageBonneReponseCARTE(false)
    } 
    // MAUVAISE Reponse
    else{
      layer.setStyle({
        fillColor: "#FF8888"
      });
      

      scoreQuestion = 0
      if (mode == "eval") {
        updateIndicateur('faux')
        nbrTentativeQuestion = 10
        // On affiche l'état de tous les boutons
        layer.unbindPopup()
        affichageBonneReponseCARTE(true)
      }
      else {
        if (nbrTentativeQuestion == 1) {
          updateIndicateur('partielle')
          scoreQuestion = 0.5
        }
        else {
          updateIndicateur('partielle', false)
          updateIndicateur('faux')
        }
      }
    }
  }  
}



function affichageBonneReponseCARTE(colorBad){
  map.eachLayer(function (layer) {
    if("feature" in layer){
        if(layer.feature.properties.ISO_A3 == questionCourante.reponseIso3){
        
        layer.setStyle({
          fillColor: "#88FF8D"
        });

        layer.openPopup()
      } else if(!layer.selected){
        layer.setStyle({
          fillColor: "#DEDEDE"
        });
        layer.unbindPopup()
      } else {
        layer.bindPopup(countriesArray[layer.layerID]['nom'])
      }
    }    
    layer.selected = true
  })
  btnSuivant.disabled = false
  //majScore()
}

/*******************
 * CAPITAL
 * 
 */

function onClickCapital(e){
  layer = e.target
  if(!layer.selected){
    layer.selected = true

    nbrTentativeQuestion = nbrTentativeQuestion + 1
    // BONNE Reponse
    let iso3Select = layer.ISO_A3;
    if( iso3Select == questionCourante.reponseIso3){
      updateIndicateur('vrai')
      affichageBonneReponseCARTECAPITAL(false)
    } 
    // MAUVAISE Reponse
    else{
      if(layer.CAPITALE == "Vatican"){
        layer.setStyle({
          color : "#6E0000",
          fillColor: "#FF4545"
        });
      }
      else {
        layer.setStyle({
          color: "#FF2020",
          fillColor: "#FF2020"
        });  
      }
    

      scoreQuestion = 0
      if (mode == "eval") {
        updateIndicateur('faux')
        nbrTentativeQuestion = 10
        // On affiche l'état de tous les boutons
        layer.unbindPopup()
        affichageBonneReponseCARTECAPITAL(true)
      }
      else {
        if (nbrTentativeQuestion == 1) {
          updateIndicateur('partielle')
          scoreQuestion = 0.5
        }
        else {
          updateIndicateur('partielle', false)
          updateIndicateur('faux')
        }
      }
    }
  } 
}

function highlightCapital(e){
  var layer = e.target;
  if(!e.target.selected){ 
    if(layer.CAPITALE == "Vatican"){
      layer.setStyle({
        color: "#000000",
        fillColor: "#00A2FF",
      });

    } else {
      layer.setStyle({
        color: "#41BDFF",
        fillColor: "#41BDFF",
      });
    }
    
  }
}

function resethighlightCapital(e){
  var layer = e.target;
  if(!e.target.selected){
    layer.setStyle({
      color: '#0018AE',
      fillColor: '#A9E0FF',
    });
  }
}



function affichageBonneReponseCARTECAPITAL(colorBad){
  // Boucler sur tous les cercles du LayerGroup
  circleLayerGroup.eachLayer(function(layer) {
    if(layer.ISO_A3 == questionCourante.reponseIso3){        
      layer.setStyle({
        color: "#16CD00",
        fillColor: "#16CD00"
      });
      //layer.unbindPopup()
      layer.openPopup()
      
    } else if(!layer.selected){
      if(layer.CAPITALE == "Vatican"){
        layer.setStyle({
          color : "#505050",
          fillColor: "#505050"
        });

      } else {
        layer.setStyle({
          color : "#505050",
          fillColor: "#505050"
        });
      }

      layer.unbindPopup()
    } else {
      layer.bindPopup(layer.CAPITALE);
    }
    layer.selected = true 
  });
  btnSuivant.disabled = false
  //majScore()
}














/***************** 
 * FIN CARTE 
 * **************** */


function changeQuestion() {
  numQuestion++;
  scoreTxt.innerHTML = score
  if (numQuestion < tabQuestions.length) {
    afficherQuestion()
  } else {
    afficherFin()
  }
}

function afficherFin() {
  resetQuestion()
  btnSuivant.disabled = false
  if (mode == "eval") {
    trResult = document.getElementsByClassName("tr-train")
    for (let tr of trResult) {
      tr.classList.add("hide")
    }
  } else {
    trResult = document.getElementsByClassName("tr-train")
    for (let tr of trResult) {
      tr.classList.remove("hide")
    }
  }
  enteteQuestion.classList.add("hide")
  scoreTxt.classList.add("hide")
  btnPasse.classList.add("hide")
  containerQuestion.classList.add("hide")
  indicateur.classList.add("hide")
  btnSuivant.classList.add("hide")

  containerFin.classList.remove("hide")
  btnHome.classList.remove("hide")
  btnNewQuiz.classList.remove("hide")
  btnRejouer.classList.remove("hide")

  run = 0;
  entetetxt.innerHTML = "Résultats"
  btnSuivant.innerHTML = "Rejouer"

  writeResult()
  sendStatsToServer()
}

function writeResult() {
  document.getElementById("total-question").innerHTML = tabQuestions.length
  document.getElementById("total-tentative").innerHTML = nbrTentativeTotal
  document.getElementById("total-correct").innerHTML = reponseCorrect
  document.getElementById("total-partielle").innerHTML = reponsePartielle
  document.getElementById("total-faux").innerHTML = reponseFausse
  document.getElementById("pourcantageCorrect").innerHTML = (reponseCorrect * 100 / tabQuestions.length) + "%"
  document.getElementById("total-score").innerHTML = score + "/" + tabQuestions.length
  document.getElementById("note").innerHTML = ((score * 20) / tabQuestions.length) + "/20"
}

function resetQuiz() {
  //Changement d'affichage  
  containerAccueil.classList.remove("hide")
  containerQuestion.classList.add("hide")
  enteteQuestion.classList.add("hide")
  containerFin.classList.add("hide")
  btnHome.classList.add("hide")
  btnNewQuiz.classList.add("hide")

  btnSuivant.classList.add("hide")
  entetetxt.innerHTML = "Informations"
}

function createIndicateur() {
  indicateur.innerHTML = '';
  for (let i = 0; i < tabQuestions.length; i++) {
    const divIndic = document.createElement("div");
    indicateur.appendChild(divIndic)
  }
}

function updateIndicateur(classType, ajout = true) {
  if (ajout) {
    indicateur.children[numQuestion].classList.add(classType)
  } else {
    indicateur.children[numQuestion].classList.remove(classType)
  }
  addtooltipIndicateur(classType)
}

// data-toggle="tooltip" data-placement="top" title="Bonne réponse après 1 erreur"
function addtooltipIndicateur(classType) {
  switch (classType) {
    case "vrai":
      if (!indicateur.children[numQuestion].hasAttribute("data-toggle")) {
        title = "Bonne réponse !"
      }
      break;
    case "faux":
      title = "Mauvaise réponse !"
      break;
    case "partielle":
      title = "1 erreur !"
      break;
    default:
      title = "X"
      break;
  }
  indicateur.children[numQuestion].setAttribute("data-toggle", "tooltip")
  indicateur.children[numQuestion].setAttribute("data-placement", "top")
  indicateur.children[numQuestion].setAttribute("title", title)
}


function sendStatsToServer(){
  console.log("SEND REQUETE SCORE")
  const xhr = new XMLHttpRequest()

  url = "/php/pages/server/updateStatsUser.php"
  xhr.open("POST", url, true)
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
  console.log(quiz.type)
  var data = {
    quizStats: true,
    type: quiz.type,
    nbQuestion: tabQuestions.length,
    pourcentage: (reponseCorrect * 100 / tabQuestions.length),
    nomQuiz: quiz.nomQuiz,
    nomCat: quiz.categorie
  }

  var jsonData = JSON.stringify(data);
  xhr.send(jsonData);


  xhr.onreadystatechange = function() {
    if (xhr.readyState === 4) {
      console.log(xhr.responseText)
    }
  }
}

