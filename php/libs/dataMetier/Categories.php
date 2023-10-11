<?php

/**
 * Retourne le chemin absolu vers le fichier qui contient les categories
 * @return string Chemin absolu du fichier
 */
function getCategorieFilePath() {
    return $_SERVER['DOCUMENT_ROOT']."/database/categories.json";
}

/**
 * Récupère toutes les catégories
 * @return array Tableaux de toutes les catégories
 */
function getAllCategories() {
    try {
        // Récupération du contenu du fichier
        $content = file_get_contents(getCategorieFilePath());
        // Convertion du JSON en Array
        return json_decode($content, true, JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        echo "ERREUR LECTURE <br/>";
        die($e->getMessage());
    }
}

/**
 * Recupère une catégorie via son nom si elle existe
 * @param string $name Nom de la cétgories
 * @return array|bool La catégorie si elle existe, sinon false
 */
function getCategorieByName($name) {
    $categories = getAllCategories();

    // Si le nom est dans le tableau de catégories
    return array_key_exists($name, $categories)
        ? $categories[$name] // alors: retourne la valeur qui correspond
        : false;         // sinon: retourne faux
}

/**
 * Récupère le nom de toutes les catégories
 * @return array La liste de toutes les catégories
 */
function getAllCategorieName() {
    $categories = getAllCategories();

    return array_keys($categories);
}

/**
 * Récupère la liste des pays d'une catégorie
 * @param string $categorieName Le nom de la catégorie
 * @return array Un tableau des pays contenu dans la catégorie
 */
function getCountriesFromCategorie($categorieName) {
    $categorie = getCategorieByName($categorieName);
    $countries = getAllCountries();
    switch ($categorie["type"]){
        case 0:
            //liste de filtres
            return filterCountries($countries, $categorie["filtres"]);
        case 1:
            //liste de pays
            return getCountriesByIdList($categorie["pays"]);
        default:
            return $countries;
    }
}

/**
 * Ajoute une catégorie à la liste des catégories
 * @param string $name Le nom de la catégorie
 * @param array $categorie Catégorie sous forme de tableau
 */
function createCategorie($name, $categorie) {
    //fichier categorie
    $file = getCategorieFilePath();
    $content = file_get_contents($file);
    $content = json_decode($content, true);

    //écriture
    $content[$name] = $categorie;
    $content = json_encode($content, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    file_put_contents($file, $content);
}


//  TESTS
/*
echo "TESTS Categories</br>";

$var = getCategorieFilePath();
echo $var;
echo "</br>";

$var = getAllCategories();
echo json_encode($var,JSON_UNESCAPED_UNICODE);
echo "</br>";

$var = getCategorieByName("Europe");
echo json_encode($var,JSON_UNESCAPED_UNICODE);
echo "</br>";

$var = getAllCategorieName();
echo json_encode($var,JSON_UNESCAPED_UNICODE);
echo "</br>";

$var = getCountriesFromCategorie("Europe");
echo json_encode($var,JSON_UNESCAPED_UNICODE);
echo "</br>";

$var = getCountriesFromCategorie("Test");
echo json_encode($var,JSON_UNESCAPED_UNICODE);
echo "</br>";

$var = getCountriesFromCategorie("undefined");
echo json_encode($var,JSON_UNESCAPED_UNICODE);
echo "</br>";

echo "</br>";
*/
?>