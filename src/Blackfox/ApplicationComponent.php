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
	/**
	 * Propriétes
	 */
	
	// Instance de l'application
	protected Application $app;
	
	/**
	 * Constructeur
	 * 
	 * @param Application $app
	 * Instance de l'application
	 */
	public function __construct(Application $app)
	{
		$this->app = $app;
	}
	
	/**
	 * Getters
	 */
	
	/**
	 * Retourne la valeur de $app
	 * 
	 * @return Application 
	 * Retourne un objet Blackfox\Application
	 */
	public function app(): Application
	{
		return $this->app;
	}
	
}
