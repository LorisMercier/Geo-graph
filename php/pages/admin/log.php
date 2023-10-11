<?php
session_start();
// foreach($_SESSION as $clef => $valeur){
//     echo $clef. ' : ' .$valeur. '<br>';
// }
// echo session_id();

echo '<pre>';
var_dump($_SESSION);
echo '</pre>';
?>

<?php
// Si le typage strict est activé c.à.d. declare(strict_types=1);
$filename = '../../../log/fichier.log';
if (file_exists($filename)) {
    $file = file_get_contents($filename);
    echo nl2br( $file );
}
else {
    echo "Pas de fichier log...";
}
?>
