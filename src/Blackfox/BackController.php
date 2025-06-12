<?php

/**
 * BackController.php
 * @Auteur: Christophe Dufour
 * 
 * Controleur de base pour tous les controleurs d'une application
 */

namespace Blackfox;

use Blackfox\Config\Config;
use Blackfox\Config\Enums\AreaConfig;
use Blackfox\Factories\EntityFactory;
use Blackfox\Factories\ModelFactory;
use Blackfox\Factories\Enums\DatabaseAPI;
use Blackfox\View\View;

abstract class BackController extends ApplicationComponent
{
	// Créateur pour les modèles
	protected ModelFactory $modelFactory;
	// Créateur pour les entités
	protected EntityFactory $entityFactory;
	// Le nom du contrôleur actuel
	protected string $controller = "";
	// La méthode à éxécuter
	protected string $method = "";
	// Le nom du fichier vue à afficher
	protected string $viewFile = "";
	// La vue à afficher
	protected View $view;
	
	/**
	 * Constructeur
	 * 
	 * @param Application $app
	 * 
	 * @param string $controller
	 * 
	 * @param string $method
	 */
	public function __construct(Application $app, string $controller, string $method)
	{
		parent::__construct($app);
		$this->modelFactory = new ModelFactory(DatabaseAPI::from(Config::get(AreaConfig::Database, 'api')), $this->app->dbFactory()->get());
		$this->entityFactory = new EntityFactory;
		$this->view = new View($app, $controller);
		$this->setController($controller);
		$this->setMethod($method);
		$this->setViewFile($method);
	}
	
	/**
	 * Retourne la valeur de view
	 * 
	 * @return View
	 */
	public function view(): View
	{
		return $this->view;
	}
	
	/**
	 * Modifie la valeur de controller
	 * 
	 * @param string $controller
	 * 
	 * @return void
	 * 
	 * @throws ValueError
	 */
	public function setController(string $controller): void
	{
		if(empty($controller)) {
			throw new \ValueError("Le controller ne peut pas être une chaine de caractère vide");
		}
		
		$this->controller = $controller;
	}
	
	/**
	 * Modifie la valeur de method
	 * 
	 * @param string $method
	 * 
	 * @return void
	 * 
	 * @throws ValueError
	 */
	public function setMethod(string $method): void
	{
		if(empty($method)) {
			throw new \ValueError("La méthode ne peut pas être une chaine de caractère vide");
		}
		
		$this->method = $method;
	}
	
	/**
	 * Modifie la valeur de viewFile
	 * 
	 * @param string $viewFile
	 * 
	 * @return void
	 * 
	 * @throws ValueError
	 */
	public function setViewFile(string $viewFile): void
	{
		if(empty($viewFile)) {
			throw new \ValueError("La vue ne peut pas être une chaine de caractère vide");
		}
		
		$this->viewFile = $viewFile;
		$this->view->setViewFile($this->viewFile);
	}
	
	/**
	 * Exécute la méthode demandée si celle-ci existe
	 * 
	 * @return void
	 * 
	 * @throws RuntimeException
	 */
	public function execute(): void
	{
		$method = "execute" . ucfirst($this->method);
		
		if(!is_callable(array($this, $method))) {
			throw new \RuntimeException("La méthode $this->method n'est pas définie sur ce controller");
		}
		
		$this->$method($this->app->httpRequest());
	}
	
}
