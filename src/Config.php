<?php

/*
*
* Config.php
* @Auteur : Christophe Dufour
*
* Gère les paramètres de l'application
*
*/

namespace Blackfox\Mamba;

class Config
{
	/**
     * Propriété
     */
	
	// Tableau des paramètres de l'application
    protected static array $vars = [];

	/**
     * Méthodes
     */

	/**
     * Lit un fichier de configuration json
     * 
     * @param string $filename, le nom du fichier à lire
     */
	public static function load(string $filename): void
	{
		if(empty(self::$vars)) {
            $file = new \SplFileObject($filename);
            $content = $file->fread($file->getSize());
            self::$vars = json_decode($content, true);
		}
	}

	/**
	 * Retourne la valeur d'un parametre de l'application
	 * 
	 * @param string $key, un index dans le tableau des parametres
	 * 
	 * @return mixed
	 */
	public static function get(string $key): mixed
	{
		if(isset(self::$vars[$key])) {
			return self::$vars[$key];
		}

		return null;
	}
}
