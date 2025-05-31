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
    /**
     * Constructeur
     * 
     * @param Application $application
     */
    protected function __construct(Application $application)
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
     * 
     * @return void
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
     * 
     * @param mixed $value
     * 
     * @param AreaConfig
     * 
	 * @return void
	 */
	public function set(string $key, mixed $value, AreaConfig $index): void
	{
        $this->vars[$index->value][$key] = $value;
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
    public function exists(string $key, AreaConfig $index): bool
    {
        return array_key_exists($key, $this->vars[$index->value]);
    }

    /**
	 * Retourne la valeur d'un paramètres
	 * 
	 * @param string $key
     * 
     * @param AreaConfig $index
     * 
	 * @return mixed
     * 
     * @throws BadConfigParamException
	 */
	public function get(string $key, AreaConfig $index): mixed
	{
        if(!$this->exists($key, $index)) {
            throw new BadConfigParamException("Paramètre de configuration inexistant. [$key]");
        }
		
		return $this->vars[$index->value][$key];
	}

    /**
     * Retourne la valeur d'un paramètres
     * 
     * @param string $name
     * 
     * @param array $arguments
     * 
     * @return mixed
     * 
     * @throws BadConfigParamException
     */
    public function __call(string $name, array $arguments): mixed
    {
        if(!AreaConfig::tryFrom($name)) {
            throw new BadConfigParamException("Paramètre de configuration inexistant. [$name]");
        }

        if(empty($arguments)) {
            throw new BadConfigParamException("Vous devez spécifier un argument pour la méthode [$name].");
        }

        if(!array_key_exists($arguments[0], $this->vars[$name])) {
            throw new BadConfigParamException("Paramètre de configuration inexistant. [$name][$arguments[0]]");
        }

        return $this->vars[$name][$arguments[0]];
    }

}
