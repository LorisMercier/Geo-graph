<?php
session_start();
$racine = $_SERVER['DOCUMENT_ROOT'];
$p1 = $racine."/php/libs/UsersDAO.php";
$p2 = $racine."/php/libs/logFunction.php";
include($p1);
include($p2);




/*****************************************
*  Constantes et variables
*****************************************/
//$map_users = ['LORIS' => 'JJG', 'TERRY' => 'RAEX'];


if (isset($_POST['connexionBtn'])) {
  if (!empty($_POST['id']) && !empty($_POST['mdp'])) {
    $pseudo = strtoupper(htmlspecialchars(trim($_POST['id'])));
    $mdp = htmlspecialchars($_POST['mdp']);

    $error = login($pseudo,$mdp);

    if (!$error) {
      addLogEvent("Connexion de $pseudo");
      header("Location: /php/pages/espace_membre.php");
      exit(0);
    } elseif($error == 1) {
      addLogEvent("Echec de connexion... Pseudo invalide [$pseudo]");
      header("Location: /php/pages/connexion.php?error=1");
      exit(0);      
    } else {
      addLogEvent("Echec de connexion... Mot de passe invalide [$pseudo]");
      header("Location: /php/pages/connexion.php?error=2");
      exit(0);     
    }
  } else {
    addLogEvent("Echec de connexion... Champs vides [$pseudo]");
    header("Location: /php/pages/connexion.php?error=3");
    exit(0);
  }
}
addLogEvent("Echec de connexion... Pas de formulaire soumis");
header("Location: /php/pages/connexion.php");

?>