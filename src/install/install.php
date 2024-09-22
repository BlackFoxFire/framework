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

class InstallationManager
{
    /**
     * Attributs
     */

    // Nom de l'application et namespace principale
    protected static string $composerName;
    protected static string $appName;
    protected static string $url;
    protected static string $db = "";
    protected static string $user = "";
    protected static string $password = "";

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
        printf("Vous êtes dans le programme d'installation du framework Mamba." . PHP_EOL);
        printf("Nous allons créer les dossiers et fichiers nécessaire au fonctionnement de l'application." . PHP_EOL);
        printf("Ce script doit-être lancé à partir du dossier racine de votre projet. (Au même endroit que composer.json)" . PHP_EOL . PHP_EOL);

        self::getComposerInfos();
        self::getAppName();
        self::getUrl();
        self::getDbInfos();

        if(!is_writeable(ROOTDIR)) {
            die("\e[1;37;41mErreur: Le dossier \"" . ROOTDIR . "\" doit être accessible en écriture.\e[0m");
        }

        self::init();
        printf(PHP_EOL . "Création des dossiers et fichiers nécessaires ..." . PHP_EOL);
        self::createFolder();
        self::createFile();
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
     * Lit les données du fichier composer.json
     */
    protected static function getComposerInfos(): void
    {
        printf("Lecture de composer.json ..." . PHP_EOL);

        if($contend = self::readFile("composer.json")) {
            $json = json_decode($contend, false);

            if(property_exists($json, 'name')) {
                $name = explode('/', $json->name);
                $name[0] = ucfirst(preg_replace("#[.-]#", '', $name[0]));
                $name[1] = ucfirst(preg_replace("#[.-]#", '', $name[1]));
                self::$composerName = $name[0] . "\\" . $name[1];
            }
        }
    }

    /**
     * Demande le nom de l'application à l'utilisateur
     */
    protected static function getAppName(): void
    {
        $flag = true;
        $pattern = "#^([A-Z][[:alpha:]_]*[[:alpha:]])+(\\\\[A-Z][[:alpha:]_]*[[:alpha:]]+)*$#";
        printf(PHP_EOL . "Veuillez entrer le namespace principale de votre application." . PHP_EOL);

        while($flag) {    
            $appName = readline("Namespace: [" . self::$composerName . "] ");

            if(empty($appName)) {
                self::$appName = self::$composerName;
                $flag = false;
            }
            else {
                if(preg_match($pattern, $appName)) {
                    self::$appName = $appName;
                    $flag = false;
                }
                else {
                    $pattern2 = "^([A-Z][a-zA-Z_]*[a-zA-Z])+(\\[A-Z][a-zA-Z_]*[a-zA-Z]+)*$";
                    printf("\e[1;37;41mNamespace invalide. Il doit commencer par une majuscule suivie de minuscule(s). Eventuellement un ou plusieurs sous-espace de nom séparé par un '\\'\e[0m" . PHP_EOL);
                    printf("Le namespace doit correspondre au motif suivant : $pattern2" . PHP_EOL . PHP_EOL);
                }
            }
        }
    }

    /**
     * Demande l'url de l'application à l'utilisateur
     */
    protected static function getUrl(): void
    {
        $flag = true;
        printf(PHP_EOL . "Veuillez entrer l'url de votre site web." . PHP_EOL);

        while($flag) {    
            $url = readline("Url: [/] ");

            if(empty($url)) {
                self::$url = '/';
                $flag = false;
            }
            else {
                if(filter_var($url, FILTER_VALIDATE_URL)) {
                    $infos = parse_url($url);

                    if(array_key_exists('path', $infos)) {
                        self::$url = preg_match("#[/]$#", $infos['path']) ? $infos['path'] : $infos['path'] . '/';
                    }
                    else {
                        self::$url = '/';
                    }

                    $flag = false;
                }
                else {
                    printf("\e[1;37;41mVeuillez entrer une url valide\e[0m" . PHP_EOL . PHP_EOL);
                }
            }
        }
    }

    /**
     * Demande les informations sur la base de donners à l'utilisateur
     */
    protected static function getDbInfos(): void
    {
        $flag = true;
        $i = 1;
        $inc = false;
        printf(PHP_EOL . "Si vous utilisez une base de données, entrez les renseignements suivants." . PHP_EOL);

        while($flag) {
            if($i == 1) {
                $db = readline("Nom de la base de données: [] ");
            }
            elseif($i==2) {
                $user = readline("Nom d'utilisateur: [] ");
            }
            else {
                $password = readline("Mot de passe: [] ");
            }

            if(empty($db)) {
                $flag = false;
                printf("\e[0;31;40mAucune base de données configurée.\e[0m" . PHP_EOL);
                printf("Vous pouvez modifier plutard le fichier db.xml" . PHP_EOL);
            }
            else {
                if($i == 1) {
                    self::$db = $db;
                    $inc = true;
                }
                elseif($i == 2) {
                    if(!empty($user)) {
                        self::$user = $user;
                        $inc = true;
                    }
                    else {
                        printf("\e[0;31;40mVeuillez entrer un nom d'utilisateur.\e[0m" . PHP_EOL . PHP_EOL);
                    }
                }
                else {
                    self::$password = (empty($password)) ? "" : $password;
                    $inc = true;
                }

                if($inc) {
                    $i++;
                    $inc = false;
                }

                $flag = ($i == 4) ? false : true;
            }
        }
    }

    /**
     * Initialise les variables $files et $
     */
    protected static function init(): void
    {
        self::$filesForlder = dirname(__FILE__) . DIRECTORY_SEPARATOR . "files" . DIRECTORY_SEPARATOR;

        $dbPatttern = array("#{{ db }}#", "#{{ user }}#", "#{{ password }}#");
        $dbReplacement = array('{{ bd }}' => self::$db, '{{ user }}' => self::$user, '{{ password }}' => self::$password);

        self::$files = array(
            (object) array('name' => "404.html",    'dir' => self::$forlders['errors']),
            (object) array('name' => "layout.html", 'dir' => self::$forlders['templates']),
            (object) array('name' => "style.css",   'dir' => self::$forlders['css']),
            (object) array('name' => "java.js",     'dir' => self::$forlders['js']),
            (object) array('name' => "app.xml",     'dir' => self::$forlders['backConf']),
            (object) array('name' => "app.xml",     'dir' => self::$forlders['frontConf']),
            (object) array('name' => "index.html",  'dir' => self::$forlders['frontView']),

            (object) array('name' => "db.xml",                  'dir' => self::$forlders['config'],     'pattern' => $dbPatttern,       'replacement' => $dbReplacement),
            (object) array('name' => "link.xml",                'dir' => self::$forlders['config'],     'pattern' => "#{{ url }}#",     'replacement' => self::$url),
            (object) array('name' => ".htaccess",               'dir' => self::$forlders['public'],     'pattern' => "#{{ url }}#",     'replacement' => self::$url),
            (object) array('name' => "app.php",                 'dir' => self::$forlders['public'],     'pattern' => "#{{ appName }}#", 'replacement' => self::$appName),
            (object) array('name' => "BackendApplication.php",  'dir' => self::$forlders['back'],       'pattern' => "#{{ appName }}#", 'replacement' => self::$appName),
            (object) array('name' => "routes.xml",              'dir' => self::$forlders['backConf'],   'pattern' => "#{{ url }}#",     'replacement' => self::$url . 'admin/'),
            (object) array('name' => "FrontendApplication.php", 'dir' => self::$forlders['front'],      'pattern' => "#{{ appName }}#", 'replacement' => self::$appName),
            (object) array('name' => "routes.xml",              'dir' => self::$forlders['frontConf'],  'pattern' => "#{{ url }}#",     'replacement' => self::$url),
            (object) array('name' => "ExampleController.php",   'dir' => self::$forlders['frontMod'],   'pattern' => "#{{ appName }}#", 'replacement' => self::$appName)
        );
    }

    /**
     * Créer les dossiers nécessaire au framework
     */
    protected static function createFolder(): void
    {
        foreach(self::$forlders as $folder) {
            if(!file_exists($folder)) {
                mkdir($folder, 0777, true);
            }
        }
    }

    /**
     * Créer les fichiers nécessaire au framework
     */
    protected static function createFile(): void
    {
        foreach(self::$files as $file) {
            $src = self::$filesForlder . $file->name;
            $dest  = $file->dir . DIRECTORY_SEPARATOR . $file->name;

            if(is_readable($src)) {
                $contend = self::readFile($src);

                if(property_exists($file, 'pattern')) {
                    $contend = preg_replace($file->pattern, $file->replacement, $contend);
                }

                if(!file_exists($dest)) {
                    if(!self::writeFile($dest, $contend)) {
                        printf("\e[1;37;41mErreur! Impossible d'écrire le fichier: " . $dest . "\e[0m" . PHP_EOL);
                    }
                }
                else {
                    $flag = true;

                    while($flag) {
                        $response = readline("Le fichier $dest existe déjà, faut-il le réécrire ? [O-N] ");

                        if(in_array($response, ['o', 'O', 'n', 'N', ''])) {
                            if(in_array($response, ['o', 'O'])) {
                                printf("\e[0;31;40mRéécriture de: " . $dest . "\e[0m" . PHP_EOL);

                                if(!self::writeFile($dest, $contend)) {
                                    printf("\e[1;37;41mErreur! Impossible d'écrire le fichier: " . $dest . "\e[0m" . PHP_EOL);
                                }
                            }
                            else {
                                printf("\e[0;32;40mAucune modification du fichier: " . $dest . "\e[0m" . PHP_EOL);
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
