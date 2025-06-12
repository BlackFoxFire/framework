<?php

/**
 * ApplicationComponent.php
 * @Auteur: Christophe Dufour
 * 
 * Classe de base pour les composantes de l'application
 */

namespace Blackfox;

abstract class ApplicationComponent
{
	// Instance de l'application
	protected Application $app;
	
	/**
	 * Constructeur
	 * 
	 * @param Application $app
	 */
	public function __construct(Application $app)
	{
		$this->app = $app;
	}
	
	/**
	 * Retourne la valeur de $app
	 * 
	 * @return Application 
	 */
	public function app(): Application
	{
		return $this->app;
	}
	
}
