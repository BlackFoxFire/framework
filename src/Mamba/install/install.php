<?php

class MambaInstallation
{
    private string $appName;
    private string $rootDir;
    private string $sourceFile;
    private string $appDir;

    //
    public function __construct($appName, $rootDir)
    {
        $this->appName = $appName;
        $this->rootDir = $rootDir;
        $this->sourceFile = __DIR__ . DIRECTORY_SEPARATOR . "files" . DIRECTORY_SEPARATOR;
        $this->appDir = $rootDir . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR;
    }

    // 
    public function run()
    {
        $this->publicForder();
        // $this->createHtaccess();
    }

    private function createFolder(array $folders, string $dir): void
    {
        foreach($folders as $folder) {
            if(!file_exists($folder)) {
                echo $folder . "\n";
                mkdir($folder, 0777, true);
            }
        }
    }

    private function copyFile()
    {

    }

    private function publicForder()
    {
        echo "Création du dossier public ... \n";

        $folders = array(
            'public_css' => "public" . DIRECTORY_SEPARATOR . "css",
            'public_js' => "public" . DIRECTORY_SEPARATOR . "js",
            'public_pictures' => "public" . DIRECTORY_SEPARATOR . "pictures"
        );

        $this->createFolder($folders, $this->rootDir);

        $files = array(
            'htaccess' => "htaccess2",
            'app' => "app.php"
        );

        copy("files/htaccess2", $this->rootDir . "public" . DIRECTORY_SEPARATOR . ".htaccess");
        copy($this->sourceFile . "app.php", $this->rootDir . "public" . DIRECTORY_SEPARATOR . "app.php");

    }

    // 
    private static function createFolder2()
    {
        $folders = array(
            // Dossiers de l'application
            // 'bkd_config' => "App" . DS . "Backend" . DS . "config",
            // 'bkd_modules' => "App" . DS . "Backend" . DS . "Modules",
            // 'ftd_config' => "App" . DS . "Frontend" . DS . "config",
            // 'ftd_modules' => "App" . DS . "Frontend" . DS . "Modules",
            // Dossier des fichiers de comfiguration
            // 'config' => "config",
            // Dossiers des templates
            // 'html_errors' => "html" . DS . "errors",
            // 'html_templates' => "html" . DS . "templates",
            // Librairies de l'application
            // 'lib_entities' => "Lib" . DS . "Entities",
            // 'lib_functions' => "Lib" . DS . "Functions",
            // 'lib_modes' => "Lib" . DS . "Models",
            // Racines web
        );
    }

    // 
    private function createHtaccess()
    {
//         $contend=
// "#
// # .htaccess
// #

// <IfModule mod_authz_core.c>
//     Require all denied
// </IfModule>

// <IfModule !mod_authz_core.c>
//     Order deny,allow
//     Deny from all
// </IfModule>
// ";

//         $folders = array(
//             'app' => "App",
//             'config' => "config",
//             'html' => "html",
//             'lib' => "Lib",
//             'vendor' => "vendor"
//         );

//         echo "\nCréation des fichiers .htaccess :\n";
        
//         foreach($folders as $folder) {
//             if(file_exists($folder) && is_dir($folder)) {
//                 $file = $folder . DS . ".htaccess";
                
//                 if(!file_exists($file)) {
//                     echo $file . "\n";
//                     file_put_contents($file, $contend);
//                 }
//             }
//         }

        $sourceFile = "files" . DIRECTORY_SEPARATOR . "htaccess2";
        $destinationFile = "public" . DIRECTORY_SEPARATOR . ".htaccess";
        copy($sourceFile, $destinationFile);

    }

}

$appName = "src";
$rootDir = dirname(__DIR__, 3);

if(is_writable($rootDir)) {
    $install = new MambaInstallation($appName, $rootDir);
    $install->run();
}
else {
    die("Impossible d'écrire dans le dossier $rootDir.");
}
