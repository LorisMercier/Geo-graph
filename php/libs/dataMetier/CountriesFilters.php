<?php
$racine = $_SERVER['DOCUMENT_ROOT'];
$p1 = $racine."/php/libs/dataMetier/Attributes.php";
include($p1);


/**
 * Applique un filtre à un pays
 * @param array $country Le pays à filtrer
 * @param array $filter Le filtre à appliquer
 * @return bool true si le pays passe le filtre, false sinon
 */
function filterCountry($country,$filter){
    if (!isAttributeList($filter["nomAttr"])){
        //attributs simples
        switch ($filter["type"]) {
            case ("="):
                return $country[$filter["nomAttr"]] == $filter["valeurAttr"];
            case ("!="):
                return $country[$filter["nomAttr"]] != $filter["valeurAttr"];
            case ("<="):
                return $country[$filter["nomAttr"]] <= $filter["valeurAttr"];
            case (">="):
                return $country[$filter["nomAttr"]] >= $filter["valeurAttr"];
            case ("<"):
                return $country[$filter["nomAttr"]] < $filter["valeurAttr"];
            case (">"):
                return $country[$filter["nomAttr"]] > $filter["valeurAttr"];
            default:
                return false;
        }
    }
    else {
        //attributs liste de valeur
        switch ($filter["type"]) {
            case ("="):
                //appartenance à la liste
                return in_array($filter["valeurAttr"], $country[$filter["nomAttr"]]);
            case ("!="):
                //non-apartenance à la liste
                return !(in_array($filter["valeurAttr"], $country[$filter["nomAttr"]]));
            default:
                return false;
        }
    }
}

/**
 * Applique une liste de filtres à une liste de pays
 * @param array $countries La liste de pays à filtrer
 * @param array $filters La liste de filtres à appliquer
 * @return array La liste des pays filtrés
 */
function filterCountries($countries, $filters){
    foreach($filters as $filter) {
        //Callback PHP utilisant la fonction filterCountry()
        $filterCallback = function($val) use ($filter) {
            return filterCountry($val,$filter);
        };
        
        //maj de la liste de pays en appliquand le filtre
        $countries = array_filter($countries, $filterCallback);
    }

    return $countries;
}


//  TESTS
/*
echo "TESTS CountriesFilters</br>";

$racine = $_SERVER['DOCUMENT_ROOT'];
$p1 = $racine."/php/libs/dataMetier/CountriesDAO.php";
include($p1);

$filterEurope = array("nomAttr"=>"continent","type"=>"=","valeurAttr"=>"Europe");
$filterDebutAlphabet = array("nomAttr"=>"nom","type"=>"<=","valeurAttr"=>"B");
$filterEuro = array("nomAttr"=>"devise","type"=>"=","valeurAttr"=>"euro");

$var = getCountryById("FRA");
echo filterCountry($var,$filterEurope) ? "true":"false";
echo "</br>";

$var = getCountryById("BRA");
echo filterCountry($var,$filterEurope) ? "true":"false";
echo "</br>";

$var = getAllCountries();
echo json_encode(filterCountries($var,[$filterEurope,$filterDebutAlphabet]),JSON_UNESCAPED_UNICODE);
echo "</br>";

$var = getCountryById("FRA");
echo filterCountry($var,$filterEuro) ? "true":"false";

echo "</br>";
*/
