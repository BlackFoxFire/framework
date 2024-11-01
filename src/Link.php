<?php

/**
 * Link.php
 * @Auteur : Christophe Dufour
 * 
 * Gère les liens de l'application
 */

namespace Blackfox\Mamba;

class Link extends AbstractConfig
{

    /**
     * Constructeur
     * 
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        parent::__construct($application);
        $this->filename = $this->app->rootDir() . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "link.json";

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
                'index' => "/"
            );
        }

       return $this->write();
    }

    /**
	 * Retourne le tableau des paramètres ou une valeur du tableau des paramètres
	 * 
	 * @param string $key
     * La clé du tableau dont la valeur est à retourner
	 * @return mixed
     * Retourne un tableau ou une valeur en cas de succès.
     * En cas d'échec, retourne null
	 */
	public function get(string $key = null): mixed
	{
		if($key === null) {
			if(!empty($this->vars)) {
				return $this->vars;
			}
		}
		else {
			if(isset($this->vars[$key])) {
				return $this->vars[$key];
			}
		}
		
		return null;
	}

    /**
     * Modifie la valeur d'une variable dans le tableau des configurations si celle-ci existe.
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

}
