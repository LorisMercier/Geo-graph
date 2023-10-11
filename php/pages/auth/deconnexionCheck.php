<?php
session_start();
$racine = $_SERVER['DOCUMENT_ROOT'];
$path = $racine."/php/libs/logFunction.php";
include($path);

$pseudo = $_SESSION['user_pseudo'];
addLogEvent("Déconnexion de $pseudo");
session_destroy();
header("Location: /index.php");
?>