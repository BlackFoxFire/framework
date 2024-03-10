<?php

/*
*
* DbConfig.php
* @Auteur : Christophe Dufour
*
* Gère les paramètres de la base de données
*
*/

namespace Mamba;

use Mamba\Traits\LoadXMLFile;

class DbConfig
{
    use LoadXMLFile;

    /*
		Les attributs
		-------------
	*/

    // Fichier de configuration
    protected static string $file = "";
    // Tableau des paramètres de la base de données
    protected static array $vars = [];

    /*
		Les méthodes
		------------
	*/

    // Initialise le tableau des paramètres de la base de données
	public static function init(Application $app): void
	{
		if(empty(self::$vars)) {
            self::$file = $app->rootDir() . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "db.xml";
			self::$vars = self::loadXmlFile(self::$file);
		}
	}

    // Retourne le tableau des paramètres de la base de données
    public static function get(): mixed
    {
        if(!empty(self::$vars)) {
            return self::$vars;
        }

        return null;
    }
}
