<?php 
if(!isset($_SESSION)){
    session_start();
}

/**
 * Retourne le chemin absolu vers le fichier qui contient les utilisateurs
 * @return string Chemin absolu du fichier
 */
function getUsersFilePath() {
    return $_SERVER['DOCUMENT_ROOT']."/database/users.json";
}

/**
 * Récupère la liste complète de tous les utilisateurs
 * @return array Liste des utilisateurs
 */
function getAllUsers() {
    try {
        // Récupération du contenu du fichier
        $content = file_get_contents(getUsersFilePath());
        // Convertion du JSON en Array
        return json_decode($content, true);
    } catch (Exception $e) {
        echo "ERREUR LECTURE <br/>";
        die($e->getMessage());
    }
}

/**
 * Recupère un seul utilisateur s'il existe
 * @param string $pseudo Pseudo de l'utilisateur
 * @return array|bool l'utilisateur s'il existe, sinon false
 */
function getOneUser( $pseudo ) {
    $pseudoUpper = strtoupper($pseudo);
    // Récupéartion de la liste de tous les utilisateurs
    $users = getAllUsers();

    // Si le pseudo est une clé de la liste des utilisateurs ...
    return array_key_exists($pseudo, $users)
        ? $users[$pseudo] // alors: retourne la valeur qui correspond
        : false;         // sinon: retourne faux
}

/**
 * Ajoute utilisateur dans le fichier
 * @param string $pseudo Pseudo de l'utilisateur
 * @param string $password Mot de passe non hashé
 */
function addUserMETIER( $pseudo, $password ) {
    // Récupération de la liste de tous les utilisateurs
    $users = getAllUsers();
    addLogEvent("Ajout de : [$pseudo]=>[".hashPassword( $password )."]");

    //Paramètres ajoutés
    $dateCreation = time();//date('d m Y h:i:s');
    $dateConnexion = null;

    // Ajout du nouvel utilisateur
    $users[$pseudo] = [
        'password' => hashPassword( $password ),
        'dateCreation' => $dateCreation,
        'dateConnexion' => $dateConnexion,
        'token'    => generateToken()
    ];
    // Sauvegarde de la liste des utilisateurs
    saveUsers( $users );
}

/**
 * Ajoute utilisateur dans le fichier
 * @param string $pseudo pseudo de l'utilisateur
 * @param string $password Mot de passe non hashé
 * 
 */
function deleteUserMETIER($pseudo) {
    // Récupération de la liste de tous les utilisateurs
    $users = getAllUsers();
    // Suppression de l'utilisateur
    unset($users[$pseudo]);
    // Sauvegarde de la liste des utilisateurs
    saveUsers( $users );
}


/**
 * Met à jour la liste des utilisateurs dans le fichier
 * @param array $users Liste des utilisateurs
 */
function saveUsers( $users ) {
    try {
        // Conversion de la liste des utilisateurs en JSON indenté
        $content = json_encode( $users, JSON_PRETTY_PRINT );
        // Remplacement du contenu du fichier.
        file_put_contents( getUsersFilePath(), $content );
    }
    catch( Exception $e ) {
        die( $e->getMessage() );
    }
}

/**
 * Hash un mot de passe
 * @param string $password Mot de passe non hashé
 * @return string Mot de passe hashé
 */
function hashPassword( $password ) {
    // Retour du mot de pass hashé
    return sha1( $password );
}

/**
 * Génère un nouveau token
 * @param string $length Taille du token souhaité. Par défaut : 40
 * @return string Token généré
 */
function generateToken( $length = 40 ) {
    $characters       = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_!?.$';
    $charactersLength = strlen( $characters );
    $token            = '';
    for( $i = 0; $i < $length; $i++ ) {
        $token .= $characters[rand(0, $charactersLength - 1)];
    }
    return $token;
}

/****************************
 * 
 * ---- FONCTION PUBLIC -----
 * 
 ****************************/

/**
 * Enregistre un nouvel utilisateur
 * @param string $pseudo Pseudo de l'utilisateur
 * @param string $password Mot de passe non hashé
 * 
 * @return bool $error Code erreur {false: Utilisateur déjà enregistré, 1: OK}
 *
 */
function addUser( $pseudo, $password ) {
    $pseudo = strtoupper($pseudo);
    // Récupération de l'utilisateur demandé
    $user = getOneUser( $pseudo );
    // Si l'utilisateur existe déjà, on arrête tout.
    if( $user ) {
        //echo "L'utilisateur {$pseudo} est déjà enregistré.";
        return false;
    }

    // Enregistrement du nouvel utilisateur
    addUserMETIER( $pseudo, $password );
    return true;
}

/**
 * Ajoute utilisateur dans le fichier
 * @param string $pseudo pseudo de l'utilisateur
 * @param string $password Mot de passe non hashé
 * 
 * @return bool False si utilisateur inexistant, True sinon.
 */
function deleteUser($pseudo, $password="") {
    $pseudoUpper = strtoupper($pseudo);
    // Vérification si utilisateur existe
    if( !getOneUser($pseudoUpper) ) {
        echo "Echec deleteUser(). L'utilisateur {$pseudoUpper} n'est pas enregistré.";
        return false;
    }
    if($password != "admin"){
        echo "Echec deleteUser(). Mot de passe incorrect";
        return false; 
    }

    deleteUserMETIER($pseudoUpper);
    return true;
}

/**
 * Tente de connecter un utilisateur. Affecte les sessions.
 * @param string $pseudo Pseudo de l'utilisateur
 * @param string $password Mot de passe non hashé
 * 
 * @return int $error Code erreur {0: OK, 1: ID invalide, 2: MDP invalide}
 */
function login( $pseudo, $password ) {
    $pseudoUpper = strtoupper($pseudo);
    // Récupéartion de la liste de tous les utilisateurs
    $users = getAllUsers();

    // Si le pseudoUpper n'a pas été trouvé.
    if( !array_key_exists($pseudoUpper, $users) ) {
        //echo "L'utilisateur {$pseudoUpper} n'est pas enregistré.";
        return 1;
    }

    // Si le de passe (hashé) ne correspond pas, on arrête tout.
    if( $users[$pseudoUpper]['password'] !== hashPassword($password) ) {
        //echo "Le mot de passe de l'utilisateur {$pseudoUpper} est invalide.";
        return 2;
    }

    // Génération d'un nouveau token de sécurité.
    $token = generateToken();

    // Enregistrement du nouveau token et sauvegarde des utilisateurs
    $users[$pseudoUpper]['token'] = $token;
    $users[$pseudoUpper]['dateConnexion'] = time();
    saveUsers( $users );

    // Enregistrement des données dans la session de l'utilisateur
    $_SESSION['user_pseudo'] = $pseudoUpper;
    $_SESSION['user_token'] = $token;
    $_SESSION['user_connexionTime'] = $users[$pseudoUpper]['dateConnexion'];

    return 0;
}

/**
 * Vérifie que l'utilisateur soit connecté et que son token est valide
 * @return bool Indique si l'utilisateur et connecté et valide
 */
function isLogged() {
    // Si la session contient "user_pseudo" et "user_token"
    if( isset($_SESSION['user_pseudo']) && isset($_SESSION['user_token']) ) {
        // Récupération de l'utilisateur dans la liste
        $user = getOneUser( $_SESSION['user_pseudo'] );
        
        // Si un utilisateur a bien été récupéré
        if( $user ) {
            // Si le token de la session correspond au token dans le fichier
            if( $_SESSION['user_token'] === $user['token'] ) {
                // Tout est bon
                return true;
            }
        }
    }

    // Une erreur a empêché d'arriver au "return true"
    return false;
}

/**
 * Déconnecte l'utilisateur (affecte la session)
 */
function logout() {
    $_SESSION = array();
    session_destroy();
}

/*
 * Script Ajout ADMIN
 */
// addUser('admin', 'admin');

// if (addUser('lololo', 'aaaa')) {
//     echo "true <br/>";
// } else {
//     echo "false <br/>";
// }
// switch (login('lololo', 'aaaa')) {
//     case 0:
//         echo "OK <br/>";
//         break;
//     case 1:
//         echo "ID mauvais <br/>";
//         break;
//     default:
//         echo "MDP mauvais <br/>";
// }
// $_SESSION['user_token'] = 'test';
// echo '<pre>';
// var_dump($_SESSION);
// echo "</pre>";
// logout();
// echo '<pre>';
// echo "<br/>";
// var_dump($_SESSION);
// echo "<br/>";
// var_dump(isLogged());
// echo "<br/>";
// var_dump(getAllUsers());
// echo '</pre>';
