<?php

/**
 * CreateProject.php
 * @Auteur : Christophe Dufour
 * 
 * Gère l'initialisation d'un nouveau projet
 */

namespace Blackfox\Scripts;

class CreateProject
{
    // Dossier à créer
    private static $folders = ['/log', "/public/css", "/public/javascript", 
            "/public/pictures", "/src/Lib/Entities", "/src/Lib/Enums", 
            "/src/Lib/Interfaces", "/src/Lib/Models", "/src/Lib/Traits"];

    /**
     * Crée les dossiers nécessaire
     * 
     * @param string $rootDir
     * Dossier racine de l'application
     * @return void
     * Ne retourne aucune valeur
     */
    public static function createFolders(string $rootDir): void
    {
        foreach(self::$folders as $forder) {
            $dir = str_replace('/', DIRECTORY_SEPARATOR, $rootDir . $forder);
            if(!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
        }
    }

}
