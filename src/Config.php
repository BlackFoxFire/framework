<?php

/**
 * Config.php
 * @Auteur : Christophe Dufour
 * 
 * Gère les paramètres de configuration globale de l'application
 */

namespace Blackfox\Mamba;

use Blackfox\Mamba\Enums\ConfigValue;

class Config extends AbstractConfig
{

    /**
     * Constructeur
     * 
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        parent::__construct($application);
        $this->filename = $this->app->rootDir() . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "conf.json";

        if(!$this->load()) {
			$this->create();
		}
    }

    /**
     * Crée la structure du tableau des paramètres de configuration et l'enregistre dans un fichier json
     * 
     * @param array $vars
     * Tableau contenant les paramètres de configuration
     * @return int|false
     * Retourne le nombre d'octets écrits, ou false si une erreur survient
     */
    public function create(array $vars = []): int|false
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
        
        return $this->write();
    }

    /**
	 * Retourne le tableau des paramètres ou une valeur du tableau des paramètres
	 * 
	 * @param string $key
     * La clé du tableau dont la valeur est à retourner
     * @param ConfigValue $index
     * [Optional]
     * Sous tableau où l'analyse doit se faire
     * @param mixed $returnValue
     * [Optional]
     * Valeur de retour personnalisée en cas d'échec
	 * @return mixed
     * Retourne un tableau ou une valeur en cas de succès.
     * En cas d'échec, retourne null ou une valeur personnalisée
	 */
	public function get(string $key = null, ConfigValue $index = ConfigValue::Frontend, mixed $returnValue = null): mixed
	{
		if($key === null) {
			if(!empty($this->vars)) {
				return $this->vars;
			}
		}
		else {
            if($this->exists($key, $index)) {
				return $this->vars[$index->value][$key];
			}
		}
		
		return $returnValue;
	}

	/**
	 * Modifie la valeur d'une variable dans le tableau des configurations si celle-ci existe.
	 * 
	 * @param string $key
     * La vlé du tableau à modifier
     * @param mixed $value
     * La valeur à assigner
     * @param ConfigValue
     * [Optional]
     * Sous tableau où l'analyse doit se faire
	 * @return void
     * Ne retourne aucune valeur
	 */
	public function set(string $key, mixed $value, ConfigValue $index = ConfigValue::Frontend): void
	{
        $this->vars[$index->value][$key] = $value;
	}

}
