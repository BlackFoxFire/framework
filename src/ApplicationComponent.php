<?php

/*
*
* ApplicationComponent.php
* @Auteur : Christophe Dufour
*
* Classe de base pour les composantes de l'application.
*
*/

namespace Blackfox\Mamba;

abstract class ApplicationComponent
{
	/*
		Les attributs.
		--------------
	*/
	
	// Objet de l'application.
	protected Application $app;
	
	/*
		Constructeur.
		-------------
	*/
	
	// Constructeur de classe.
	public function __construct(Application $app)
	{
		$this->app = $app;
	}
	
	/*
		Les getters.
		------------
	*/
	
	// Retourne l'objet $app.
	public function app(): Application
	{
		return $this->app;
	}
}
