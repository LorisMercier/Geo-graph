<?php
$connecte = isset($_SESSION['user_pseudo']);
$racine = $_SERVER['DOCUMENT_ROOT'];
$p0 = "/php/libs/dataMetier/Categories.php";
$p1 = "/php/libs/dataMetier/Templates.php";
require_once($racine.$p0);
require_once($racine.$p1);
?>

<header id="header" class="header d-flex align-items-center">

    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
        <a href="/index.php" class="logo d-flex align-items-center">
            <!-- Uncomment the line below if you also wish to use an image logo -->
            <img src="/assets/img/icon/LogoGlobe.png" alt="">
            <h1>Géo-Graph<span>.</span></h1>
        </a>
        <nav id="navbar" class="navbar">
            <ul>
                <li><a href="/index.php#hero" name="hero">Accueil</a></li>
                <li class="dropdown"><a href="#" name="edition2021"><span>Cours</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
                    <ul>
                        <li><a href="/php/pages/map.php" name="chiffres">Carte Intéractive</a></li>
                        <li><a href="/php/pages/cours_table.php" name="parcours">Les tableaux</a></li>
                    </ul>
                </li>
                <li class="dropdown"><a href="#" name="edition2021"><span>Quiz</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
                    <ul>
                        <?php
                        $categories  = getAllCategorieName();
                        foreach ($categories as $index => $category) {
                            echo '<li class="dropdown"><a href="#"><span>' . $category . '</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>';
                            echo '<ul>';
                            $templates = getTemplatesAllByCategorie($category);
                            foreach (array_keys($templates) as $key) {
                                if(key_exists("type", $templates[$key])){
                                    $name = "[".$templates[$key]['type']."] ".$templates[$key]['nomQuiz'];
                                  } else {
                                    $name = $templates[$key]['nomQuiz'];
                                  }
                                  echo '<li><a href=/php/pages/server/createQuiz.php?nameQuiz=' . $key . '>' . $name . '</a></li>';
                                };
                            echo '</ul></li>';
                        }
                        ?>
                    </ul>                   
                </li>                
                <li class="dropdown"><a href="#" name="edition2021"><span>Création</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
                    <ul>
                        <li><a href="/php/pages/creation_quiz.php" name="creation_quiz">Ajout d'un quiz</a></li>
                        <li><a href="/php/pages/creation_categorie.php" name="creation_cat">Ajout d'une catégorie</a></li>
                    </ul>
                </li>
                <?php echo ($connecte) ? '
                <li class="dropdown"><a class="dernierLabel" href="#"><span>Mon compte</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
                <ul>
                    <li><a href="/php/pages/espace_membre.php">Mon espace</a></li>              
                    <li><a href="/php/pages/auth/deconnexionCheck.php">Déconnexion</a></li>
                </ul>'
                :
                '<li><a href="/php/pages/connexion.php" class="dernierLabel">Connexion</a></li>';
                ?>
                </li>
            </ul>
        </nav><!-- .navbar -->

        <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
        <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>

    </div>
</header><!-- End Header -->
<!-- End Header -->