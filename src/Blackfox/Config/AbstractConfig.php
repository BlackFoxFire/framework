<?php

/**
 * AbstractConfig.php
 * @Auteur : Christophe Dufour
 * 
 * Cette classe représentant un tableau avec des paramètres de configuration
 */

namespace Blackfox\Config;

use Blackfox\Application;
use Blackfox\ApplicationComponent;

abstract class AbstractConfig extends ApplicationComponent
{
    // Tableau des fichiers de configuration
    protected static array $file = [];
    // Tableau des paramètres de configuration
    protected static array $vars = [];

    /**
     * Initialisation
     * 
     * @return void
     */
    abstract static function init(Application $app): void;

    /**
     * Retourne la valeur de vars
     * 
     * @return array|null
     */
    public static function vars(): array|null
    {
        return !empty(self::$vars[static::class]) ? self::$vars[static::class] : null;
    }

    /**
     * Modifie la valeur de vars
     * 
     * @return void
     */
    public function setVars(array $vars): void
    {
        self::$vars[static::class] = $vars;
    }

    /**
     * Lit un fichier de configuration au format json
	 * 
	 * @return bool
     * Retourne true en cas de succès, sinon false
     */
	protected static function load(): bool
	{
        if(!is_file(self::$file[static::class])) {
            return false;
        }

        $file = new \SplFileObject(self::$file[static::class]);
        $content = $file->fread($file->getSize());
        self::$vars[static::class] = json_decode($content, true);
        
        return true;
	}

    /**
	 * Ecrit un fichier de paramètres au format json
	 * 
	 * @return int|false
     * Retourne le nombre d'octets écrits, ou false si une erreur survient.
     * @throws RuntimeException
	 */
	public static function write(): int|false
	{
        if(empty(self::$vars[static::class])) {
            throw new \RuntimeException("Impossible d'écrire un tableau de paramètres vide!");
        }

		$file = new \SplFileObject(self::$file[static::class], "w");
		return $file->fwrite(json_encode(self::$vars[static::class], JSON_PRETTY_PRINT));
	}
    
}
