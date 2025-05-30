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
    /**
     * Constructeur
     * 
     * @param Application $application
     */
    protected function __construct(Application $application)
    {
        parent::__construct($application);

        $this->filename = $this->app->rootDir() . str_replace('/', DIRECTORY_SEPARATOR, "/config/link.json");

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
                'index' => "/"
            );
        }
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
    public function set(string $key, mixed $value): void
    {
        $this->vars[$key] = $value;
    }

    /**
     * Vérifie l'existence d'une variable dans le tableau des paramètres
     * 
     * @param string $key
     * 
     * @return bool
     * Retourne true si la variable existe, sinon false
     */
    public function exists(string $key): bool
    {
        return array_key_exists($key, $this->vars);
    }

    /**
	 * Retourne la valeur d'une variable du tableau des paramètres
	 * 
	 * @param string $key
     * 
	 * @return mixed
     * 
     * @throws BadConfigParamExecption
	 */
	public function get(string $key): mixed
	{
		if(!array_key_exists($key, $this->vars)) {
            throw new BadConfigParamException("Paramètre de configuration inexistant.  [$key]");
		}
		
        return $this->vars[$key];
	}

    /**
     * Retourne la valeur d'une variable du tableau des paramètres
     * 
     * @param string $name
     * 
     * @return mixed
     * 
     * @throws BadConfigParamExecption
     */
    public function __get(string $name): mixed
    {
        return $this->get($name);
    }

}
