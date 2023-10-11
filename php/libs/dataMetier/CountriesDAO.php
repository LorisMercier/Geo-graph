<?php 

/**
 * Retourne le chemin absolu vers le fichier qui contient les pays
 * @return string Chemin absolu du fichier
 */
function getCountriesFilePath() {
    return $_SERVER['DOCUMENT_ROOT']."/database/world/countriesApi.json";
}

/**
 * Récupère la liste complète de tous les pays
 * @return array Liste des pays
 */
function getAllCountries() {
    try {
        // Récupération du contenu du fichier
        $content = file_get_contents(getCountriesFilePath());
        // Convertion du JSON en Array
        return json_decode($content, true, JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        echo "ERREUR LECTURE <br/>";
        die($e->getMessage());
    }
}

/**
 * Recupère un pays via son id s'il existe
 * @param string $id Identifiant 3 lettres du pays
 * @return array|bool le pays s'il existe, sinon false
 */
function getCountryById( $id ) {
    //passage de id en majuscule
    $idUpper = strtoupper($id);

    //Récupération de la liste de tous les pays
    $countries = getAllCountries();

    // Si le pseudo est une clé de la liste des utilisateurs ...
    return array_key_exists($idUpper, $countries)
        ? $countries[$idUpper] // alors: retourne la valeur qui correspond
        : false;         // sinon: retourne faux
}

/**
 * Recupère une liste de pays via une liste d'ID
 * @param array $ids Liste d'identifiant 3 lettres des pays
 * @return array|bool La liste des pays
 */
function getCountriesByIdList( $ids ) {
    //Récupération de la liste de tous les pays
    $countries = getAllCountries();

    //Filtre la liste des pays, en ne concervant que ceux dont l'id est dans $ids
    return array_filter($countries, 
                function ($key) use ($ids){
                    return in_array($key,$ids);
                },
                ARRAY_FILTER_USE_KEY);
}

/**
 * Recupère un attribut du pays via son ID s'il existe
 * @param string $id ID 3 lettres du pays
 * @param string $attr L'attribut à récupérer (sensible à la casse)
 * @return string|bool la valeur de l'attribut pour le pays s'il existe, sinon false
 */
function getAttributeByID( $id, $attr ) {
    $idUpper = strtoupper($id);

    //Récupération de la liste de tous les pays
    $countries = getAllCountries();

    // Si le pseudo est une clé de la liste des utilisateurs ...
    return $countries[$idUpper][$attr];
}


// SCRIPT TEST

/*
echo "TESTS CountriesDAO</br>";

$var = json_encode(getAllCountries(), JSON_UNESCAPED_UNICODE);
echo $var;
echo "</br>";

$var = json_encode(getCountryById("bra"), JSON_UNESCAPED_UNICODE);
echo $var;
echo "</br>";

$var = json_encode(getCountriesByIdList(["FRA","ITA","BRA","DEU"]), JSON_UNESCAPED_UNICODE);
echo $var;
echo "</br>";

$var = getAttributeById("BRA","continent");
echo $var;
echo "</br>";

$var = getValuesByAttribute("continent");
echo json_encode($var,JSON_UNESCAPED_UNICODE);
echo "</br>";

echo "</br>";
*/

?>