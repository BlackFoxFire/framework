<?php

/**
 * FrontConfig.php
 * @Auteur : Christophe Dufour
 * 
 * Gère les paramètres de l'application
 */

namespace Blackfox\Mamba;

class FrontConfig extends AbstractConfig
{

    /**
     * Constructeur
     * 
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        parent::__construct($application);
        $this->filename = $this->app->appDir() . "App" . DIRECTORY_SEPARATOR . "Frontend" . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "app.json";

        if(!$this->load()) {
			$this->create();
		}
    }

    /**
     * Crée la structure du tableau des paramètres de configuration et l'enregistre dans un fichier json
	 * Retourne le nombre d'octets écrits, ou false si une erreur survient.
     * 
     * @return int|false
     */
    public function create(array $vars = []): int|false
    {
        if(!empty($vars)) {
            $this->vars = $vars;
        }
        
       return $this->write();
    }

}
