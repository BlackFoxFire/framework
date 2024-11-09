<?php

/**
 * BackController.php
 * @Auteur: Christophe Dufour
 * 
 * Controleur de base pour tous les controleurs d'une application
 */

namespace Blackfox;

use Blackfox\Views\View;
use Blackfox\Database\Managers;

abstract class BackController extends ApplicationComponent
{
	/**
	 * Propriétes
	 */
	
	// Objet des managers
	protected Managers $managers;
	// Le nom du contrôleur actuel
	protected string $controller = "";
	// L'action à éxécuter
	protected string $action = "";
	// Le nom du fichier vue à afficher
	protected string $viewFile = "";
	// La vue à afficher
	protected View $view;
	
	/**
	 * Constructeur
	 * 
	 * @param Application $app
	 * Instance de l'application
	 * @param string $controller
	 * Contrôleur où l'on se trouve
	 * @param string $action
	 * La méthodes à appeler
	 */
	public function __construct(Application $app, string $controller, string $action)
	{
		parent::__construct($app);

		$this->setManager();
		$this->view = new View($app, $controller);
		$this->setController($controller);
		$this->setAction($action);
		$this->setViewFile($action);
	}
	
	/**
	 * Getters
	 */
	
	/**
	 * Retourne la valeur de $view
	 * 
	 * @return View
	 * Retourne un objet Blackfox\View
	 */
	public function view(): View
	{
		return $this->view;
	}
	
	/**
	 * Setters
	 */

	/**
	 * Modifie la valeur de $managers
	 * 
	 * @return void
	 * Ne retourne aucune valeur
	 */
	protected function setManager(): void
	{
		$api = $this->app->config()['database']['api'];
		$dbname = $this->app->config()['database']['dbname'];
		$username = $this->app->config()['database']['username'];
		$password = $this->app->config()['database']['password'];

		$factoryName = "Blackfox\\Database\\" . $api . "Factory";
		$bdInstance = $factoryName::getInstance($dbname, $username, $password);
		$this->managers = new Managers($this->app, $api, $bdInstance);
	}
	
	/**
	 * Modifie la valeur de $controller
	 * 
	 * @param string $controller
	 * Contrôleur où le traitement se passe
	 * @return void
	 * Ne retourne aucune valeur
	 */
	public function setController(string $controller): void
	{
		if(!is_string($controller) || empty($controller)) {
			throw new \InvalidArgumentException("Le controller doit être une chaine de caractères valide");
		}
		
		$this->controller = $controller;
	}
	
	/**
	 * Modifie la valeur de $action
	 * 
	 * @param string $action
	 * La méthodes qu'il faudra appeler
	 * @return void
	 * Ne retourne aucune valeur
	 */
	public function setAction(string $action): void
	{
		if(!is_string($action) || empty($action)) {
			throw new \InvalidArgumentException("L'action doit être une chaine de caractères valide");
		}
		
		$this->action = $action;
	}
	
	/**
	 * Modifie la valeur de $viewFile
	 * 
	 * @param string $viewFile
	 * Le chemin vers le fichier de la vue qu'il faudra afficher
	 * @return void
	 * Ne retourne aucune valeur
	 */
	public function setViewFile(string $viewFile): void
	{
		if(!is_string($viewFile) || empty($viewFile)) {
			throw new \InvalidArgumentException("La vue doit être une chaine de caractères valide");
		}
		
		$this->viewFile = $viewFile;
		$this->view->setViewFile($this->viewFile);
	}
	
	/**
	 * Méhtodes
	 */
	
	/**
	 * Exécute l'action demandée si celle-ci existe
	 * 
	 * @return void
	 * Ne retourne aucune valeur
	 */
	public function execute(): void
	{
		$method = "execute" . ucfirst($this->action);
		
		if(!is_callable(array($this, $method))) {
			throw new \RuntimeException("L'action $this->action n'est pas définie sur ce controller");
		}
		
		$this->$method($this->app->httpRequest());
	}
	
}
