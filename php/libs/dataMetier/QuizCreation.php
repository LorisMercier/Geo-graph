<?php
$racine = $_SERVER['DOCUMENT_ROOT'];
$p1 = $racine."/php/libs/dataMetier/CountriesDAO.php";
include($p1);
$p2 = $racine."/php/libs/logFunction.php";
include($p2);
$p3 = $racine."/php/libs/dataMetier/CountriesFilters.php";
include($p3);
$p4 = $racine."/php/libs/dataMetier/Categories.php";
include($p4);

/**
 * Crée une proposition de quiz
 * @param string $label Le nom de la proposition
 * @param bool $status Statut de la proposition
 * @return array La proposition créée
 */
function createProposition($label, $status) {
    return array("nom"=>$label, "etat"=>$status);
}

/**
 * Crée les propositions pour une question, selon la liste de pays possible
 * @param array $countries La liste des pays parmis lesquels choisir les propositions
 * @param int $nbPropositions Le nombre de proposition à créer
 * @param string $attr L'attribut sur lequel porte les propositions
 * @param string $id L'ID de la proposition correcte
 * @return array Un tableau contenant les propositions
 */
function createPropositions($countries, $nbPropositions , $attr, $id) {
    $propositions = [];

    //Récupération de l'attribut de la proposition correcte
    $correctLabel = getAttributeByID($id, $attr);
    
    //Ajout en tant que bonne réponse
    array_push($propositions,createProposition($correctLabel,true));
    
    //Suppression du pays de la liste
    unset($countries[$id]);
    $nbPropositions--;

    while($nbPropositions > 0) {
    //Choix d'un pays aléatoire
    $key = array_rand($countries);
    $correctLabel = getAttributeByID($key, $attr);
    
    //Ajout en tant que mauvaise réponse
    array_push($propositions,createProposition($correctLabel,false));
    
    //Suppression du pays de la liste
    unset($countries[$key]);
    $nbPropositions--;
    }

    //ordre des propositions aléatoires
    shuffle($propositions);

    return $propositions;
}

/**
 * Crée l'intitulé d'une question
 * @param string $attrQuestion l'attribut donné en indice pour la question
 * @param string $attrReponse l'attribut attendu en réponse à la question
 * @param string $information L'information donnée pour réponse à la question
 */
function createIntitule($attrQuestion, $attrReponse, $information) {
    switch ($attrQuestion){
        case ("nom"):
            switch($attrReponse){
                case ("capitale"):
                    return "Quelle est la capitale de ".$information." ?";
                case ("continent"):
                    return "Sur quel continent se trouve ".$information." ?";
                case ("population"):
                    return "Quelle est la population de ".$information." ?";
                case ("drapeau"):
                    return "Quel est le drapeau de ".$information." ?";
                case ("nom"):
                    return "Où se situe le pays ".$information." ?";
                default:
                    return "Association attribut question/reponse inconnu";
            }
        case ("capitale"):
            switch($attrReponse){
                case ("nom"):
                    return "De quel pays ".$information." est la capitale ?";
                case ("continent"):
                    return "Sur quel continent se trouve le pays dont ".$information." est la capitale ?";
                case ("population"):
                    return "Quelle est la population du pays dont ".$information." est la capitale ?";
                case ("drapeau"):
                    return "Quel est le drapeau ddu pays dont ".$information." est la capitale ?";                
                case ("capitale"):
                    return "Où se situe ".$information." ?";
                default:
                    return "Association attribut question/reponse inconnu";
            }
        default:
            return $attrQuestion." n'est pas un attribut valide pour une question";
    }

}

/**
 * Crée un tableau de questions sur les pays concernés par le filtre, portant sur les attibuts en paramètre
 * @param string $attrQuestion L'attribut donnée en indice à la question
 * @param string $attrReponse L'attribut sur lequel porte la réponse
 * @param int $nbQuestions Le nombre de questions
 * @param int $nbPropositions Le nombre de porpositions par question
 * @param string $categorie Categorie que laquelle porte le quiz
 * @param string $type Type de quiz (QCM ou carte)
 * @return array Un tableau de question créées aléatoirement
 */
function createQuestions($attrQuestion, $attrReponse, $nbQuestions, $nbPropositions, $categorie, $type) {
    $questions = [];

    //filtrage des pays
    $countries = getCountriesFromCategorie($categorie);

    //liste des pays parmis lesquels sélectionner la bonne proposition (pour éviter les doublons)
    $notAlreadySelectedCountries = $countries;

    while($nbQuestions>0) {
        //Choix d'un pays aléatoire
        $id = array_rand($notAlreadySelectedCountries);

        //suppresion du pays de la liste des pays possibles
        unset($notAlreadySelectedCountries[$id]);

        //création de la question
        $information = getCountryById($id)[$attrQuestion];
        $question = array(  "intitule" => createIntitule($attrQuestion, $attrReponse, $information),
                            "proposition" => createPropositions($countries, $nbPropositions, $attrReponse, $id));

        if($type == "carte"){
            $question["type"] = "carte";
            $question["reponseIso3"] = $id;
            $question["attrReponse"] = $attrReponse;
        }
        else if($attrReponse == "drapeau"){
            $question["type"] = "image";
        } 
        else {
            $question["type"] = "texte";
        }

        //ajout au tableau de questions
        array_push($questions, $question);

        $nbQuestions--;
    }

    return $questions;
    
}

/**
 * Crée un quiz via un template
 * @param array $templateName Nom du template
 * @param array $template Le template à utiliser pour créer le quiz
 * @return array Le quiz créé
 */
function CreateQuiz($templateName, $template){ 
    $varQ = $template["varQuestion"];
    if(array_key_exists("type", $template)){
        $type = $template["type"];
    } else {
        $type = "";
    }
    $quiz = array(  "templateName" => $templateName,
                    "type" => $type,
                    "categorie" => $template["categorie"],
                    "nomQuiz" => $template["nomQuiz"],
                    "questions" =>  createQuestions( $varQ["attrQuestion"],
                                                    $varQ["attrReponse"],
                                                    intval($template["nbQuestion"]),
                                                    intval($template["nbProposition"]),
                                                    $template["categorie"],
                                                    $type
                                    )                    
                    );
    return $quiz;
}

/**
 * Crée un Path pour un quiz créé à partir d'un template
 * @param string $templateName Le nom du template utilisé
 * @return string Le Path créé
 */
function CreateFilePath($templateName) {
    $racine = $_SERVER['DOCUMENT_ROOT'];
    return $racine."/database/quiz/".$templateName."Temp.json";
}

/**
 * Génère un fichier Quiz à partir du template donné
 * @param string $templateName Path du template depuis database/templates/
 */
function CreateQuizFromTemplate($templateName){
    $racine = $_SERVER['DOCUMENT_ROOT'];
    $racineTemplates = $racine."/database/templates/";
     
    try {
        // Récupération du contenu du fichier
        $content = file_get_contents($racineTemplates.$templateName.".json");
        // Convertion du JSON en Array
        $template = json_decode($content, true);
    } catch (Exception $e) {
        echo "ERREUR LECTURE <br/>";
        die($e->getMessage());
    }

    //création du quiz et convertion en JSON
    $quizJSON = json_encode(CreateQuiz($templateName, $template),JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    //création et écriture dans le fichier du quiz
    $quizFile = fopen(CreateFilePath($templateName), 'w');
    fwrite($quizFile,$quizJSON);
    fclose($quizFile);
}


//  TEST
/*
echo "TESTS QuizCreation </br>";

echo json_encode(createProposition("France",false),JSON_UNESCAPED_UNICODE);
echo "</br>";

echo json_encode(createPropositions(getAllCountries(),3,"nom","FRA"),JSON_UNESCAPED_UNICODE);
echo "</br>";

echo json_encode(createIntitule("nom","capitale","France"),JSON_UNESCAPED_UNICODE);
echo "</br>";

echo json_encode(createIntitule("capitale","nom","Paris "),JSON_UNESCAPED_UNICODE);
echo "</br>";

echo json_encode(createQuestions("nom","capitale",4,4,"europe"),JSON_UNESCAPED_UNICODE);
echo "</br>";

echo CreateFilePath("CapitaleEurope");
echo "</br>";
CreateQuizFromTemplate("CapitalesMonde");
echo "Quiz Created </br>";

CreateQuizFromTemplate("CapitalesEurope");
echo "Quiz Created </br>";

CreateQuizFromTemplate("CapitalesG20");
echo "Quiz Created </br>";
*/
?>