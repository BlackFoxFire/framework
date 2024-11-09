<?php

/**
 * Config.php
 * @Auteur : Christophe Dufour
 * 
 * Gère les paramètres de configuration
 */

namespace Blackfox\Config;

use Blackfox\Application;
use Blackfox\Config\Enums\ConfigEnum;
use Blackfox\Exceptions\BadConfigParamException;
use Blackfox\Config\Interfaces\ConfigInterface;

class Config extends AbstractConfig implements ConfigInterface
{
     // Instance de cette classe
     private static ?self $instance = null;

     /**
     * Retourne l'instance de la classe Config
     * 
     * @param Application $application
     * Instance de l'application
     * @return self
     * Retourne l'instance de cette classe
     */
    public static function getInstance(Application $application): self
    {
        if(is_null(self::$instance)) {
            self::$instance = new self($application);
        }

        return self::$instance;
    }

    /**
     * Constructeur
     * 
     * @param Application $application
     * Instance de l'application
     */
    private function __construct(Application $application)
    {
        parent::__construct($application);

        $this->filename = $this->app->rootDir() . str_replace('/', DIRECTORY_SEPARATOR, "/config/conf.json");

        if(!$this->load()) {
			$this->create();
            $this->write();
		}
    }

    /**
     * Crée la structure du tableau des paramètres
     * 
     * @param array $vars
     * Tableau contenant les paramètres de configuration
     * @return void
     * Ne retourne aucune valeur
     */
    public function create(array $vars = []): void
    {
        if(!empty($vars)) {
            $this->vars = $vars;
        }
        else {
            $this->vars = array(
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

    /**
	 * Modifie la valeur d'une variable dans le tableau des configurations si celle-ci existe.
	 * 
	 * @param string $key
     * La vlé du tableau à modifier
     * @param mixed $value
     * La valeur à assigner
     * @param ConfigEnum
     * [Optionnel]
     * Sous tableau où l'analyse doit se faire
	 * @return void
     * Ne retourne aucune valeur
	 */
	public function set(string $key, mixed $value, ConfigEnum $index = ConfigEnum::Frontend): void
	{
        $this->vars[$index->value][$key] = $value;
	}

    /**
     * Vérifie l'existence d'une variable dans le tableau des paramètres
     * 
     * @param string $key
     * La clé à analyser
     * @param ConfigEnum $index
     * [Optionnel]
     * Sous tableau où l'analyse doit se faire
     * 
     * @return bool
     * Retourne true en cas de succès, sinon false
     */
    public function exists(string $key, ConfigEnum $index = ConfigEnum::Frontend): bool
    {
        return array_key_exists($key, $this->vars[$index->value]);
    }

    /**
	 * Retourne le tableau des paramètres ou une valeur du tableau des paramètres
	 * 
	 * @param string $key
     * La clé du tableau dont la valeur est à retourner
     * @param ConfigEnum $index
     * [Optionnel]
     * Sous tableau où l'analyse doit se faire
	 * @return mixed
     * Retourne un tableau ou une valeur en cas de succès.
	 */
	public function get(string $key, ConfigEnum $index = ConfigEnum::Frontend): mixed
	{
        if(!$this->exists($key, $index)) {
            throw new BadConfigParamException("Paramètre de configuration inexistant. [$key]");
        }
		
		return $this->vars[$index->value][$key];
	}

}
