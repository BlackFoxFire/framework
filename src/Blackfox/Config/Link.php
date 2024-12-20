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
     * Instance de l'application
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
                'index' => "/"
            );
        }
    }

    /**
     * Ajoute ou modifie une variable du tableau des paramètres
     * 
     * @param string $key
     * La vlé du tableau à modifier
     * @param mixed $value
     * La valeur à assigner
     * @return void
     * Ne retourne aucune valeur
     */
    public function set(string $key, mixed $value): void
    {
        $this->vars[$key] = $value;
    }

    /**
     * Vérifie l'existence d'une variable dans le tableau des paramètres
     * 
     * @param string $key
     * La clé à analyser
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
     * La clé du tableau dont la valeur est à retourner
	 * @return mixed
     * Retourne un tableau ou une valeur en cas de succès.
     * @throws BadConfigParamExecption
     * Lance une exception BadConfigParamExecption si une variable du tableau des paramètres n'existe pas
	 */
	public function get(string $key): mixed
	{
		if(!$this->exists($key)) {
            throw new BadConfigParamException("Paramètre de configuration inexistant.  [$key]");
		}
		
        return $this->vars[$key];
	}

}
