<?php
$racine = $_SERVER['DOCUMENT_ROOT'];
$p1 = $racine."/php/libs/logFunction.php";
include($p1);

/**
 * Retourne le chemin absolu vers le dossier qui contient les templates
 * @return string Chemin absolu du dossier
 */
function getTemplatesDirectoryPath() {
    return $_SERVER['DOCUMENT_ROOT']."/database/templates/";
}

/**
 * Récupère le nom de tous les fichiers templates
 * @return array La liste de tous les fichiers templates
 */
function getAllTemplateFileName(){
    //recupération du contenu du répertoire
    $scandir = scandir(getTemplatesDirectoryPath());

    //suppression des fichiers . et ..
    $tabFiles = array_filter($scandir,function($val){
        return !($val=="." | $val=="..");
    });

    //suppression des extensions de fichiers
    $tabFiles = array_map(function($val){
        return substr($val, 0, strrpos($val, "."));
    },$tabFiles);

    //suppression des clés du tableau
    $tabFiles = array_values($tabFiles);

    return $tabFiles;
}

/**
 * Récupère le nom des templates portant sur $categorieName
 * @param string $categorieName Le nom de la categorie
 * @return array Un tableau associatif templateId(le nom du fichier) => nomQuiz des templates portant sur $categorieName
 */
function getTemplatesNameByCategorie($categorieName) {
    $racineTemplates = getTemplatesDirectoryPath();

    $templatesName = [];
    
    //recupération des fichiers de catégorie
    $templatesFiles = getAllTemplateFileName();

    foreach ($templatesFiles as $file) {
        try {
            // Récupération du contenu du fichier
            $content = file_get_contents($racineTemplates.$file.".json");
            // Convertion du JSON en Array
            $template = json_decode($content, true);
        } catch (Exception $e) {
            echo "ERREUR LECTURE <br/>";
            die($e->getMessage());
        }

        //filtre sur le nom de la catégorie
        if ($template["categorie"] == $categorieName) {
            $templatesName[$file] = $template["nomQuiz"];
        }
    }

    return $templatesName;
}

/**
 * Récupère le template portant sur $categorieName
 * @param string $categorieName Le nom de la categorie
 * @return array Un tableau associatif templateId(le nom du fichier) => nomQuiz des templates portant sur $categorieName
 */
function getTemplatesAllByCategorie($categorieName) {
    $racineTemplates = getTemplatesDirectoryPath();

    $templatesName = [];
    
    //recupération des fichiers de catégorie
    $templatesFiles = getAllTemplateFileName();

    foreach ($templatesFiles as $file) {
        try {
            // Récupération du contenu du fichier
            $content = file_get_contents($racineTemplates.$file.".json");
            // Convertion du JSON en Array
            $template = json_decode($content, true);
        } catch (Exception $e) {
            echo "ERREUR LECTURE <br/>";
            die($e->getMessage());
        }

        //filtre sur le nom de la catégorie
        if ($template["categorie"] == $categorieName) {
            $templatesName[$file] = $template;
        }
    }

    return $templatesName;
}

/**
 * Crée un fichier à partir d'un template
 * @param array $template Template sous forme de tableau
 */
function createTemplate($template) {
    //création nom fichier sans espaces
    $name = $template["nomQuiz"];
    $name = str_replace(" ","",$name);

    //création fichier
    $racineTemplate = getTemplatesDirectoryPath();
    $file = fopen($racineTemplate.$name.".json", "w");

    //écriture
    $content = json_encode($template, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    fwrite($file, $content);
}




//  TESTS
/*
echo "TESTS templates</br>";

$var = getAllTemplateFileName();
echo json_encode($var,JSON_UNESCAPED_UNICODE);
echo "</br>";

$var = getTemplatesNameByCategorie("Europe");
echo json_encode($var,JSON_UNESCAPED_UNICODE);
echo "</br>";

$template = array(
    "nomQuiz" => "Les continents du Monde",
    "nbQuestion" =>  7,
    "nbProposition" =>  3,
    "varQuestion" => 
        array(
            "attrQuestion" => "nom",
            "attrReponse" => "continent"
        ),
    "categorie" => "Monde"
    );
createTemplate($template);
echo "</br>";

echo "</br>"
*/
?>