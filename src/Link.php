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

class Link
{
	/**
     * Propriété
     */
	
	// Tableau des liens de l'application
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
	 * Retourne une valeur du tableau des liens
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

	/**
     * Retourne le tableau des liens
     * 
     * @return array
     */
	public static function getAll(): array
	{
		return self::$vars;
	}
}
