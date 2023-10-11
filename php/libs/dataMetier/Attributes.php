<?php 

/**
 * Retourne le chemin absolu vers le fichier qui contient les attributs
 * @return string Chemin absolu du fichier
 */
function getAttributesFilePath() {
    return $_SERVER['DOCUMENT_ROOT']."/database/world/attributes.json";
}

/**
 * Récupère la liste complète de tous les attributs
 * @return array Liste des attibuts
 */
function getAllAttributes() {
    try {
        // Récupération du contenu du fichier
        $content = file_get_contents(getAttributesFilePath());
        // Convertion du JSON en Array
        return json_decode($content, true, JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        echo "ERREUR LECTURE <br/>";
        die($e->getMessage());
    }
}

/**
 * Récupère la liste complète des noms de tous les attributs
 * @return array Liste des noms des attributs
 */
function getAllAttributesName() {
    //récupération de tous les attributs
    $attributes = getAllAttributes();
    //ne garde que les noms des attributs
    return array_keys($attributes);
}

/**
 * Récupère la liste complète des noms de tous les attributs qui sont uniques
 * @return array Liste des noms des attributs
 */
function getAllUniqueAttributesName() {
    //récupération de tous les attributs
    $attributes = getAllAttributes();

    //Callback PHP vérifiant que "unique" est dans le tableau $val
    $filterCallback = function($val) {
        return in_array("unique", $val);
    };
    
    //maj de la liste des attributs en appliquant le filtre
    $attributes = array_filter($attributes, $filterCallback);

    //ne garde que les noms des attributs
    return array_keys($attributes);
}

/**
 * Récupère la liste complète des noms de tous les attributs qui sont filtrables
 * @return array Liste des noms des attributs
 */
function getAllFilterableAttributesName() {
    //récupération de tous les attributs
    $attributes = getAllAttributes();

    //Callback PHP vérifiant que "image" n'est pas dans le tableau $val
    $filterCallback = function($val) {
        return !in_array("image", $val);
    };
    
    //maj de la liste des attributs en appliquant le filtre
    $attributes = array_filter($attributes, $filterCallback);

    //ne garde que les noms des attributs
    return array_keys($attributes);
}

/**
 * Retourne vrai si l'attribut est une liste
 * @param string $attr Le nom de l'attribut
 */
function isAttributeList( $attr ) {
    $attributes = getAllAttributes();
    return in_array("list",$attributes[$attr]);
}

/**
 * Retourne vrai si l'attribut est une image
 * @param string $attr Le nom de l'attribut
 */
function isAttributeImage( $attr ) {
    $attributes = getAllAttributes();
    return in_array("image",$attributes[$attr]);
}

/**
 * Récupère la liste des valeurs possible pour un attribut parmis une liste de pays
 * @param string $attr Le nom de l'attribut
 * @param array $countries Liste de pays
 * @return array Un tableau de valeur possible pour cet attribut
 */
function getValuesByAttribute( $attr, $countries ) {
    //remplace les pays par la valeur de leur attribut
    $values = array_map(function($val) use ($attr) {
        return $val[$attr];
    },$countries);

    if(isAttributeList($attr)) {
        //ne garde qu'une itération de chaque valeur, après avoir concaténé les tableaux contenants les valeurs
        return array_values(array_unique(array_merge(...array_values($values))));
    }
    else {
        //ne garde qu'une itération de chaque valeur
        return array_values(array_unique($values));
    }
}


/**
 * Récupère la liste des valeurs possible pour une liste d'attributs parmis une liste de pays
 * @param array $attributes Liste d'attributs
 * @param array $countries Liste de pays
 * @return array Un tableau de valeur possible pour cet attribut
 */
function getValuesByAttributeList( $attributes, $countries ) {
    $values = [];
    foreach ($attributes as $attr) {
        $values[$attr] = getValuesByAttribute($attr,$countries);
    }
    return $values;
}

// SCRIPT TEST

/*
echo "TESTS Attributes</br>";

$racine = $_SERVER['DOCUMENT_ROOT'];
$p1 = $racine."/php/libs/dataMetier/CountriesDAO.php";
include($p1);

$var = getAllAttributes();
echo json_encode($var,JSON_UNESCAPED_UNICODE);
echo "</br>";

$var = getAllAttributesName();
echo json_encode($var,JSON_UNESCAPED_UNICODE);
echo "</br>";

$var = getAllUniqueAttributesName();
echo json_encode($var,JSON_UNESCAPED_UNICODE);
echo "</br>";

$var = isAttributeList("langue");
echo json_encode($var,JSON_UNESCAPED_UNICODE);
echo "</br>";

$var = isAttributeList("nom");
echo json_encode($var,JSON_UNESCAPED_UNICODE);
echo "</br>";

$var = getValuesByAttribute("continent", getAllCountries());
echo json_encode($var,JSON_UNESCAPED_UNICODE);
echo "</br>";

$var = getValuesByAttributeList(["continent","capitale"], getAllCountries());
echo json_encode($var,JSON_UNESCAPED_UNICODE);
echo "</br>";

$var = getValuesByAttribute("langue", getAllCountries());
echo json_encode($var,JSON_UNESCAPED_UNICODE);
echo "</br>";

$var = getValuesByAttribute("devise", getAllCountries());
echo json_encode($var,JSON_UNESCAPED_UNICODE);
echo "</br>";

echo "</br>";
*/

?>