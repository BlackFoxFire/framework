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
    // Instance de cette classe
    private static array $instance = [];
    // Nom du fichier de configuration json
    protected string $filename;
    // Tableau des paramètres de configuration de l'application
    protected array $vars = [];

    /**
     * Crée et/ou retourne l'instance d'une classe qui hérite de AbstractConfig
     * 
     * @param Application $application
     * 
     * @return AbstractConfig
     */
    public static function getInstance(Application $app): AbstractConfig
    {
        $class = static::class;

        if(!isset(self::$instance[$class])) {
            self::$instance[$class] = new static($app);
        }

        return self::$instance[$class];
    }

    /**
     * Retourne la valeur de filename
     * 
     * @return string
     */
    public function filename(): string
    {
        return $this->filename;
    }

    /**
     * Retourne la valeur de vars
     * 
     * @return array|null
     */
    public function vars(): array|null
    {
        return !empty($this->vars) ? $this->vars : null;
    }

    /**
     * Lit un fichier de configuration au format json
	 * 
	 * @return bool
     * Retourne true en cas de succès, sinon false
     */
	protected function load(): bool
	{
		if(empty($this->vars)) {
			if(is_file($this->filename)) {
            	$file = new \SplFileObject($this->filename);
            	$content = $file->fread($file->getSize());
            	$this->vars = json_decode($content, true);
				return true;
			}

			return false;
		}

		return true;
	}

	/**
	 * Crée la structure du tableau des paramètres
	 * 
     * @param array $vars
     * [Optionnel]
     * Tableau contenant les paramètres de configuration
	 * @return void
	 */
	abstract public function create(array $vars = []): void;

    /**
	 * Ecrit un fichier de paramètres au format json
	 * 
	 * @return int|false
     * Retourne le nombre d'octets écrits, ou false si une erreur survient.
	 */
	public function write(): int|false
	{
		$file = new \SplFileObject($this->filename, "w");
		return $file->fwrite(json_encode($this->vars, JSON_PRETTY_PRINT));
	}
    
}
