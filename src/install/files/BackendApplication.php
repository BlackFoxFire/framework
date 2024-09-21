<?php

/*
*
* BackendApplication.php
* @Auteur : Christophe Dufour
*
* Application orientée administrateur.
*
*/

namespace {{ appName }}\App\Backend;

use \Mamba\Application;

class BackendApplication extends Application
{
	/*
		Constructeur
		------------
	*/
	public function __construct(string $rootDir, string $appDir, string $appName)
	{
		$this->name = "Backend";
		parent::__construct($rootDir, $appDir, $appName);
	}
	
	/*
		Les méthodes
		------------
	*/
	
	// Lance l'application
	public function run(): void
	{
		$controller = $this->getController();
		$controller->execute();
		
		$this->httpResponse->setView($controller->view());
		$this->httpResponse->render();
	}
}
