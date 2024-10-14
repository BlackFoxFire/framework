<?php

/*
*
* DbConfig.php
* @Auteur : Christophe Dufour
*
* Gère les paramètres de la base de données
*
*/

namespace Blackfox\Mamba;

class DbConfig
{
    /**
     * Propriété
     */

    // Tableau des paramètres de la base de données
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
     * Retourne le tableau des paramètres de la base de données, sinon null
     * 
     * @return array|null
     */
    public static function get(): array|null
    {
        if(!empty(self::$vars)) {
            return self::$vars;
        }

        return null;
    }
}
