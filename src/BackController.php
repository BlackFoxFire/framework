<?php

/*
*
* BackController.php
* @Auteur : Christophe Dufour
*
* Controleur de base pour tous les controleurs d'une application.
*
*/

namespace Mamba;

abstract class BackController extends ApplicationComponent
{
	/*
		Les attributs
		-------------
	*/
	
	// Objet des managers
	protected Managers $managers;
	// Le nom du contrôleur actuel
	protected string $controller = "";
	// L'action à éxécuter
	protected string $action = "";
	// Le nom du fichier vue à afficher
	protected string $viewFile = "";
	// La vue a afficher
	protected View $view;
	
	/*
		Constructeur
		------------
	*/
	public function __construct(Application $application, string $controller, string $action)
	{
		parent::__construct($application);
		
		$this->managers = new Managers("PDO", PDOFactory::mysqlConnexion(DbConfig::get()), $this->app->appName());
		$this->view = new View($application, $controller);

		$this->setController($controller);
		$this->setAction($action);
		$this->setViewFile($action);
	}
	
	/*
		Les getters
		-----------
	*/
	
	// Retourne l'objet $view
	public function view(): View
	{
		return $this->view;
	}
	
	/*
		Les setters
		-----------
	*/
	
	// Modifie la valeur de l'attribut $controller
	public function setController(string $controller): void
	{
		if(!is_string($controller) || empty($controller)) {
			throw new \InvalidArgumentException("Le controller doit être une chaine de caractères valide");
		}
		
		$this->controller = $controller;
	}
	
	// Modifie la valeur de l'attribut $action
	public function setAction(string $action): void
	{
		if(!is_string($action) || empty($action)) {
			throw new \InvalidArgumentException("L'action doit être une chaine de caractères valide");
		}
		
		$this->action = $action;
	}
	
	// Modifie la valeur de l'attribut $viewFile
	public function setViewFile(string $viewFile): void
	{
		if(!is_string($viewFile) || empty($viewFile)) {
			throw new \InvalidArgumentException("La vue doit être une chaine de caractères valide");
		}
		
		$this->viewFile = $viewFile;
		$this->view->setViewFile($this->viewFile);
	}
	
	/*
		Les méthodes
		------------
	*/
	
	// Exécute l'action demandée si celle-ci existe
	public function execute(): void
	{
		$method = "execute" . ucfirst($this->action);
		
		if(!is_callable(array($this, $method))) {
			throw new \RuntimeException("L'action $this->action n'est pas définie sur ce controller.");
		}
		
		$this->$method($this->app->httpRequest());
	}
}
