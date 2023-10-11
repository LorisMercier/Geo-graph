<?php
session_start();
$racine = $_SERVER['DOCUMENT_ROOT'];
$p0 = "/php/libs/dataMetier/QuizCreation.php";
require_once($racine.$p0);

if(isset($_GET['nameQuiz'])){
    //Nom fichier créé : $_GET['nameQuiz']."Temp.json"
    CreateQuizFromTemplate($_GET['nameQuiz']);
    header("Location: /php/pages/quiz.php?nameQuiz=".$_GET['nameQuiz']);
    exit(0); 

} 
?>