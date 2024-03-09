<?php

/*
* app.php
* @Auteur : Christophe Dufour
* 
* Controleur frontal de l'application.
*
*/

// Nom de l'application, du namespace et du dossier de l'application dans le dossier src
$appName = "{{ appName }}";

// Dossier racine
$rootDir = dirname(__DIR__);

// Autoload de Composer
require $rootDir . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . "autoload.php";

// Gestion des erreurs
error_reporting(E_ALL);         // Toutes les erreurs sont rapportées
ini_set("display_errors", 1);   // Les erreurs sont affichées

// Dossier source de l'application
$appDir = $rootDir . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . $appName . DIRECTORY_SEPARATOR ;

// Si l'application n'est pas valide, on va charger l'application par défaut qui se chargera de générer une erreur 404
if(!isset($_GET['app']) || !file_exists($appDir . "App" . DIRECTORY_SEPARATOR . $_GET['app'])) {
    $_GET['app'] = "Frontend";
}

// Il ne nous suffit plus qu'à déduire le nom de la classe et de l'instancier
$appClass = $appName . "\\App\\" . $_GET['app'] . '\\' . $_GET['app'] . "Application";

$app = new $appClass($appDir, $appName);
$app->run();
