<?php

/**
 * Link.php
 * @Auteur : Christophe Dufour
 * 
 * Gère les liens de l'application
 */

namespace Blackfox\Config;

use Blackfox\Application;
use Blackfox\Exceptions\BadConfigParamException;

class Link extends AbstractConfig
{
    const FILENAME = "/config/link.json";

    /**
     * Initialisation
     * 
     * @return void
     */
    public static function init(Application $app): void
    {
        self::$file[static::class] = $app->rootDir() . str_replace('/', DIRECTORY_SEPARATOR, self::FILENAME);
        !self::load();
    }

    /**
     * Ajoute ou modifie une variable du tableau des paramètres
     * 
     * @param string $key
     * 
     * @param mixed $value
     * 
     * @return void
     */
    public static function set(string $key, mixed $value): void
    {
        self::$vars[static::class][$key] = $value;
    }

    /**
     * Vérifie l'existence d'une variable dans le tableau des paramètres
     * 
     * @param string $key
     * 
     * @return bool
     * Retourne true si la variable existe, sinon false
     */
    public static function exists(string $key): bool
    {
        return array_key_exists($key, self::$vars[static::class]);
    }

    /**
	 * Retourne la valeur d'un paramètre
	 * 
	 * @param string $key
     * 
	 * @return mixed
     * 
     * @throws BadConfigParamExecption
	 */
	public static function get(string $key): mixed
	{
		if(!array_key_exists($key, self::$vars[static::class])) {
            throw new BadConfigParamException("Paramètre de configuration inexistant.  [$key]");
		}
		
        return self::$vars[static::class][$key];
	}

}
