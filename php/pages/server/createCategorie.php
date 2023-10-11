<?php
$racine = $_SERVER['DOCUMENT_ROOT'];
$p0 = "/php/libs/dataMetier/Categories.php";
require_once($racine.$p0);

echo var_dump($_POST);

if(isset($_POST['submitTemplate'])){
    //Nom fichier créé : $_GET['nameQuiz']."Temp.json"
    if ($_POST["type"] == 0) {
        $content = array(
                'type' => $_POST["type"],
                'filtres' => json_decode($_POST["list"])
        );
    }
    else {
        $content = array(
                "type" => $_POST["type"],
                "pays" => json_decode($_POST["list"])
        ); 
    }
    createCategorie($_POST["name"],$content);
} 

//header("Location: /index.php");
?>