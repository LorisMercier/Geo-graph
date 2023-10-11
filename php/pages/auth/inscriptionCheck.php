<?php
session_start();

$racine = $_SERVER['DOCUMENT_ROOT'];
$p1 = $racine."/php/libs/UsersDAO.php";
$p2 = $racine."/php/libs/logFunction.php";
include($p1);
include($p2);
    


/**
 * 0 : OK
 * 1 : User déjà inscrit
 * 2 : MDP identique
 * 3 : Champs vide
 */
$code = -1;

if (isset($_POST['validationBtn'])) {
    if (!empty($_POST['id']) && !empty($_POST['mdp']) && !empty($_POST['mdp2'])) {
        $pseudo =  strtoupper(htmlspecialchars(trim($_POST['id'])));
        $mdp = htmlspecialchars($_POST['mdp']);
        $mdp2 = htmlspecialchars($_POST['mdp2']);

        if ($mdp == $mdp2) {
            if (addUser($pseudo, $mdp)) {
                $code = 0;
                addLogEvent("INSCRIPTION : [$pseudo] [$mdp]");
                header("Location: /php/pages/admin/inscription.php?error=0");
                exit;
            } else {
                addLogEvent("Echec dans l'inscription... Utilisateur déjà enregistré [$pseudo]");
                header("Location: /php/pages/admin/inscription.php?error=1");
                exit;
            }
        } else {
            addLogEvent("Echec dans l'inscription... Mot de passe différent [$pseudo]");
            header("Location: /php/pages/admin/inscription.php?error=2");
            exit;
        }
    } else {
        addLogEvent("Echec d'inscription... Champs vides...");
        header("Location: /php/pages/admin/inscription.php?error=3");
        exit;
    }
}

header("Location: /php/pages/admin/inscription.php");
?>
