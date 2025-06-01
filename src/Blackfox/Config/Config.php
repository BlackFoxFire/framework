<?php

/**
 * Config.php
 * @Auteur : Christophe Dufour
 * 
 * Gère les paramètres de configuration
 */

namespace Blackfox\Config;

use Blackfox\Application;
use Blackfox\Config\Enums\AreaConfig;
use Blackfox\Exceptions\BadConfigParamException;

class Config extends AbstractConfig
{
    const FILENAME = "/config/conf.json";
    
    /**
     * Initialisation
     * 
     * @return void
     */
    public static function init(Application $app): void
    {
        self::$file[static::class] = $app->rootDir() . str_replace('/', DIRECTORY_SEPARATOR, self::FILENAME);
        self::load();
    }

    /**
	 * Modifie la valeur d'une variable dans le tableau des configurations si celle-ci existe.
	 * 
	 * @param string $key
     * 
     * @param mixed $value
     * 
     * @param AreaConfig
     * 
	 * @return void
	 */
	public static function set(string $key, mixed $value, AreaConfig $index): void
	{
        self::$vars[static::class][$index->value][$key] = $value;
	}

    /**
     * Vérifie l'existence d'une variable dans le tableau des paramètres
     * 
     * @param string $key
     * 
     * @param AreaConfig $index
     * 
     * @return bool
     * Retourne true en cas de succès, sinon false
     */
    public static function exists(string $key, AreaConfig $index): bool
    {
        return array_key_exists($key, self::$vars[static::class][$index->value]);
    }

    /**
	 * Retourne la valeur d'un paramètre
     * 
     * @param AreaConfig $index
     * 
     * @param string $key
     * [Optionnel]
     * Si key est vide, le retour sera une section du tableau de configuration, 
     * sinon c'est la valeur de key qui est retournée
	 * @return mixed
     * 
     * @throws BadConfigParamException
	 */
	public static function get(AreaConfig $index, string $key = ""): mixed
	{
        if(empty($key)) {
            return self::$vars[static::class][$index->value];
        }

        if(!array_key_exists($key, self::$vars[static::class][$index->value])) {
            throw new BadConfigParamException("Paramètre de configuration inexistant. [$key]");
        }
		
		return self::$vars[static::class][$index->value][$key];
	}

    /**
     * Retourne la valeur d'un paramètre
     * 
     * @param string $name
     * 
     * @param array $arguments
     * 
     * @return mixed
     * 
     * @throws BadConfigParamException
     */
    public static function __callStatic(string $name, array $arguments): mixed
    {
        if(!AreaConfig::tryFrom($name)) {
            throw new BadConfigParamException("Paramètre de configuration inexistant. [$name]");
        }

        if(empty($arguments)) {
            throw new BadConfigParamException("Vous devez spécifier un argument pour la méthode [$name].");
        }

        if(!array_key_exists($arguments[0], self::$vars[static::class][$name])) {
            throw new BadConfigParamException("Paramètre de configuration inexistant. [$name][$arguments[0]]");
        }

        return self::$vars[static::class][$name][$arguments[0]];
    }

    /**
     * Crée la structure du tableau des paramètres
     * 
     * @param array $vars
     * 
     * @return void
     */
    public static function create(): void
    {
        self::$vars = array(
            'database' => array(
                'dbname' => "",
                'username' => "",
                'password' => ""
            ),
            'global' => array(),
            'backend' => array(),
            'frontend' => array()
        );
    }

}
