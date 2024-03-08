<?php

define("DS", DIRECTORY_SEPARATOR);
define("ROOT", dirname(__DIR__));

class MambaInstallation
{
    private string $appName;
    private string $appDir;

    //
    public function __construct($appName, $appDir)
    {
        $this->appName = $appName;
        $this->appDir = $appDir;
    }

    // 
    public function run()
    {
        $this->createFolder();
        // $this->createHtaccess();
    }

    // 
    private static function createFolder()
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
            'public_css' => "public" . DIRECTORY_SEPARATOR . "css",
            'public_js' => "public" . DIRECTORY_SEPARATOR . "js",
            'public_pictures' => "public" . DIRECTORY_SEPARATOR . "pictures"
        );

        echo "Créations des dossiers : \n";
        
        foreach($folders as $folder) {
            if(!file_exists($folder)) {
                echo $folder . "\n";
                // mkdir($folder, 0777, true);
            }
        }
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
$appDir = dirname(__DIR__, 3);
$install = new MambaInstallation($appName, $appDir);

try{
    $install->run();
}
catch(\RuntimeException $e) {
    throw new \RuntimeException($e->getMessage() . " " . $e->getFile() . " " . $e->getCode());
}
