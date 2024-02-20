<?php

/*
*
* Config.php
* @Auteur : Christophe Dufour
*
* Gère les paramètres de l'application
*
*/

namespace Mamba;

use Mamba\Trait\LoadXMLFile;

class Config
{
	use LoadXMLFile;

	/*
		Les attributs
		-------------
	*/
	
	// Fichier de configuration
	protected static string $file = "";
	// Tableau des paramètres de l'application
    protected static array $vars = [];

	/*
		Les méthodes
		------------
	*/

	// Initialise le tableau des paramètres de l'application
	public static function init(Application $app): void
	{
		if(empty(self::$vars)) {
			self::$file = $app->appDir() . "App" . DIRECTORY_SEPARATOR . $app->name() . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "app.xml";
			self::$vars = self::loadXmlFile(self::$file);
		}
	}

	// Retourne la valeur d'un parametre de l'application
	public static function get(string $key = null): mixed
	{
		if(isset(self::$vars[$key])) {
			return self::$vars[$key];
		}

		return null;
	}
}
