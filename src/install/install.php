<?php

/*
*
* install.php
* @Auteur : Christophe Dufour
*
*/

define("DS", DIRECTORY_SEPARATOR);
//define("ROOTDIR", dirname(__DIR__ . DIRECTORY_SEPARATOR, 3));
define("ROOTDIR", realpath('.') . DIRECTORY_SEPARATOR);

/**
 * Classe qui gère l'installation des fichiers nécessaire au framework Mamba
 */
class InstallationManager
{
    /**
     * Attributs
     */

    // Nom de l'application et namespace principale
    protected static string $appName;

    // Les dossiers nécessaire au framework
    protected static array $forlders = array(
        'config'    => ROOTDIR . "config", 
        'errors'    => ROOTDIR . "html"   . DS . "errors",
        'templates' => ROOTDIR . "html"   . DS . "templates",
        'public'    => ROOTDIR . "public",
        'css'       => ROOTDIR . "public" . DS . "css",
        'js'        => ROOTDIR . "public" . DS . "js",
        'pictures'  => ROOTDIR . "public" . DS . "pictures",
        'back'      => ROOTDIR . "src"    . DS . "App" . DS . "Backend",
        'backConf'  => ROOTDIR . "src"    . DS . "App" . DS . "Backend" . DS . "config",
        'backMod'   => ROOTDIR . "src"    . DS . "App" . DS . "Backend" . DS . "Modules",
        'front'     => ROOTDIR . "src"    . DS . "App" . DS . "Frontend",
        'frontConf' => ROOTDIR . "src"    . DS . "App" . DS . "Frontend" . DS . "config",
        'frontMod'  => ROOTDIR . "src"    . DS . "App" . DS . "Frontend" . DS . "Modules" . DS . "Example" . DS,
        'frontView' => ROOTDIR . "src"    . DS . "App" . DS . "Frontend" . DS . "Modules" . DS . "Example" . DS . "views",
        'entities'  => ROOTDIR . "src"    . DS . "Lib" . DS . "Entities",
        'enums'     => ROOTDIR . "src"    . DS . "Lib" . DS . "Enums",
        'models'    => ROOTDIR . "src"    . DS . "Lib" . DS . "Models",
        'traits'    => ROOTDIR . "src"    . DS . "Lib" . DS . "Traits"
    );

    // Les fichiers nécessaire au framework
    protected static array $files = array();

    // Les fichiers à créer nécessaire au framework
    protected static array $filesToCreate = array();

    // Dossier contenant les fichiers à copier
    protected static string $filesForlder = "";

    /**
     * Méthodes
     */

    /**
     * Lance le processus d'installation des fichiers du framwork Mamba
     */
    public static function go(): void
    {
        $options = getopt("", ["help", "app:"]);

        if(empty($options)) {
            printf("Utilisation : install.php [--help] [--app Nom_application]" . PHP_EOL);
            die("Erreur: vous devez spécifier un nom d'application." . PHP_EOL);
        }
        
        if(array_key_exists("help", $options)) {
            self::help();
            exit(0);
        }

        self::init();
        self::$appName = ucfirst($options['app']);
        
        if(!is_writeable(ROOTDIR)) {
            die("Le dossier \"" . ROOTDIR . "\" doit être accessible en écriture.");
        }

        self::createFolder();
        self::copyFiles();
        self::createFile();
    }

    /**
     * Affiche l'aide de la ligne de commande
     */
    protected static function help(): void
    {
        printf("Utilisation : install.php [--help] [--app Nom_application]" . PHP_EOL);
        printf("Installe les fichiers necessaire du framework Mamba." . PHP_EOL . PHP_EOL);
        printf("Argument requis :" . PHP_EOL);
        printf("   --app		Nom de l'application (Namespace principale)." . PHP_EOL);
        printf("        		Les noms de classe DOIVENT être déclarés en StudlyCaps." . PHP_EOL);
        printf("        		Il est possible de modifier le nom dans le fichier app.php." . PHP_EOL);
        printf("Arguments optionnels :" . PHP_EOL);
        printf("   --help		Affiche l'aide." . PHP_EOL);
    }

    /**
     * Initialise les variables $files et $
     */
    protected static function init(): void
    {
        self::$filesForlder = dirname(__FILE__) . DIRECTORY_SEPARATOR . "files" . DIRECTORY_SEPARATOR;

        self::$files = array(
            (object) array('name' => "db.xml",                  'dir' => self::$forlders['config']),
            (object) array('name' => "link.xml",                'dir' => self::$forlders['config']),
            (object) array('name' => "404.html",                'dir' => self::$forlders['errors']),
            (object) array('name' => "layout.html",             'dir' => self::$forlders['templates']),
            (object) array('name' => ".htaccess",               'dir' => self::$forlders['public']),
            (object) array('name' => "style.css",               'dir' => self::$forlders['css']),
            (object) array('name' => "java.js",                 'dir' => self::$forlders['js']),
            (object) array('name' => "app.xml",                 'dir' => self::$forlders['backConf']),
            (object) array('name' => "routes.xml",              'dir' => self::$forlders['backConf']),
            (object) array('name' => "app.xml",                 'dir' => self::$forlders['frontConf']),
            (object) array('name' => "routes.xml",              'dir' => self::$forlders['frontConf']),
            (object) array('name' => "index.html",              'dir' => self::$forlders['frontView']),
            //(object) array('name' => "", 'dir' => self::$forlders['']),
        );

        self::$filesToCreate = array(
            (object) array('name' => "app.php",                 'dir' => self::$forlders['public']),
            (object) array('name' => "BackendApplication.php",  'dir' => self::$forlders['back']),
            (object) array('name' => "FrontendApplication.php", 'dir' => self::$forlders['front']),
            (object) array('name' => "ExampleController.php",   'dir' => self::$forlders['frontMod'])
        );
    }

    /**
     * Créer les dossiers nécessaire au framework
     */
    protected static function createFolder(): void
    {
        printf("Création des dossiers nécessaire:" . PHP_EOL);
        
        foreach(self::$forlders as $folder) {
            if(!file_exists($folder)) {
                printf($folder . PHP_EOL);
                mkdir($folder, 0777, true);
            }
        }

        printf(PHP_EOL);
    }

    /**
     * Copie les fichiers nécessaire au framework
     */
    protected static function copyFiles(): void
    {
        printf("Copie des fichiers:" . PHP_EOL);
        
        foreach(self::$files as $file) {
            $src = self::$filesForlder . $file->name;
            $dest  = $file->dir . DIRECTORY_SEPARATOR . $file->name;

            if(is_readable($src)) {
                if(!file_exists($dest)) {
                    printf("Copie de: " . $dest . PHP_EOL);
                    if(!copy($src, $dest)) {
                        printf("Erreur! Impossible d'écrire le fichier: " . $dest . PHP_EOL);
                    }
                }
                else {
                    $flag = true;

                    while($flag) {
                        $response = readline("Le fichier $dest existe déjà, faut-il le réécrire ? [O-N] (Oui par default): ");
                        
                        if(in_array($response, ['o', 'O', 'n', 'N', ''])) {
                            if(in_array($response, ['o', 'O', ''])) {
                                printf("Copie de: " . $dest . PHP_EOL);

                                if(!copy($src, $dest)) {
                                    printf("Erreur! Impossible d'écrire le fichier: " . $dest . PHP_EOL);
                                }
                            }
                            else {
                                printf("Attention: Le fichier " . $dest . " n'a pas été copié." . PHP_EOL);
                            }
                            $flag = false;
                        }
                    }
                }
            }
            else {
                printf("Erreur! Impossible d'accéder au fichier: " . $src . PHP_EOL);
            }
        }

        printf(PHP_EOL);
    }

    /**
     * Créer les fichiers nécessaire au framework
     */
    protected static function createFile(): void
    {
        printf("Création des fichiers:" . PHP_EOL);

        foreach(self::$filesToCreate as $file) {
            $src = self::$filesForlder . $file->name;
            $dest  = $file->dir . DIRECTORY_SEPARATOR . $file->name;

            if(is_readable($src)) {
                $contend = self::readFile($src);
                $contend = preg_replace("#\{\{ appName \}\}#", self::$appName, $contend);

                if(!file_exists($dest)) {
                    printf("Création de: " . $dest . PHP_EOL);
                    
                    if(!self::writeFile($dest, $contend)) {
                        printf("Erreur! Impossible d'écrire le fichier: " . $dest . PHP_EOL);
                    }
                }
                else {
                    $flag = true;

                    while($flag) {
                        $response = readline("Le fichier $dest existe déjà, faut-il le réécrire ? [O-N] (Oui par default): ");

                        if(in_array($response, ['o', 'O', 'n', 'N', ''])) {
                            if(in_array($response, ['o', 'O', ''])) {
                                printf("Création de: " . $dest . PHP_EOL);

                                if(!self::writeFile($dest, $contend)) {
                                    printf("Erreur! Impossible d'écrire le fichier: " . $dest . PHP_EOL);
                                }
                            }
                            else {
                                printf("Attention: Le fichier " . $dest . " n'a pas été copié." . PHP_EOL);
                            }
                            $flag = false;
                        }
                    }
                }
            }
            else {
                printf("Erreur! Impossible d'accéder au fichier: " . $src . PHP_EOL);
            }
            
        }

        printf(PHP_EOL);
    }

    /**
     * Lit le contenu d'un fichier
     */
    protected static function readFile(string $file): string|false
    {
        if($handle = fopen($file, 'r')) {
            $contend = fread($handle, filesize($file));
            fclose($handle);

            return $contend;
        }

        return false;
    }

    /**
     * Lit un contenu dans un fichier
     */
    protected static function writeFile(string $file, string $contend): int|false
    {
        if($handle = fopen($file, 'w')) {
            $lenght = fwrite($handle, $contend);
            fclose($handle);

            return $lenght;
        }

        return false;
    }

}

InstallationManager::go();
