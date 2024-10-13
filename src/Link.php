<?php

/*
*
* Link.php
* @Auteur : Christophe Dufour
*
* Gère les liens de l'application
*
*/

namespace Blackfox\Mamba;

use Blackfox\Mamba\Traits\LoadXMLFile;

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
			self::$file = $app->rootDir() . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "link.xml";
			self::$vars = self::loadXmlFile(self::$file);
		}
	}
	
	// Retourne un lien du tableau des liens
	public static function get(string $key): mixed
	{
		if(isset(self::$vars[$key])) {
			return self::$vars[$key];
		}
		
		return null;
	}

	// Retourne le tableau des liens
	public static function getAll(): array
	{
		return self::$vars;
	}
}
