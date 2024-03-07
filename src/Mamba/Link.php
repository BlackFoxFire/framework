<?php

/*
*
* Link.php
* @Auteur : Christophe Dufour
*
* Gère les liens de l'application
*
*/

namespace Mamba;

use Mamba\Traits\LoadXMLFile;

class Link
{	
	use LoadXMLFile;

	/*
		Les attributs
		-------------
	*/
	
	// Fichier de configuration
	protected static string $file = "";
	// Tableau des liens de l'application
    protected static array $vars = [];
	
	/*
		Les méthodes
		------------
	*/

	// Initialise le tableau des liens
	public static function init(Application $app): void
	{
		if(empty(self::$vars)) {
			self::$file = $app->appDir() . "config" . DIRECTORY_SEPARATOR . "link.xml";
			self::$vars = self::loadXmlFile(self::$file);
		}
	}
	
	// Retourne un lien ou le tableau des liens
	public static function get(string $key = null): mixed
	{
		if(!is_null($key)) {
			if(isset(self::$vars[$key])) {
				return self::$vars[$key];
			}
		}
		else {
			if(!empty(self::$vars)) {
				return self::$vars;
			}
		}
		
		return null;
	}
}
