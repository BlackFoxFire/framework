<?php

/*
*
* FrontendApplication.php
* Application orientée utilisateur.
*
*/

namespace {{ appName }}\App\Frontend;

use \Blackfox\Mamba\Application;

class FrontendApplication extends Application
{
	/*
		Constructeur
		------------
	*/
	public function __construct(string $rootDir, string $appDir, string $appName)
	{
		$this->name = "Frontend";
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
