<?php

/**
 * BackController.php
 * @Auteur: Christophe Dufour
 * 
 * Controleur de base pour tous les controleurs d'une application
 */

namespace Blackfox;

use Blackfox\Config\Enums\AreaConfig;
use Blackfox\Factories\EntityFactory;
use Blackfox\Factories\ModelFactory;
use Blackfox\Factories\Enums\DatabaseAPI;
use Blackfox\View\View;

abstract class BackController extends ApplicationComponent
{
	/**
	 * Propriétes
	 */
	
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
	 * Instance de l'application
	 * @param string $controller
	 * Contrôleur où l'on se trouve
	 * @param string $method
	 * La méthodes à appeler
	 */
	public function __construct(Application $app, string $controller, string $method)
	{
		parent::__construct($app);
		$this->modelFactory = new ModelFactory(DatabaseAPI::from($this->app->config()->get('api', AreaConfig::Database)), $this->app->dbFactory()->get());
		$this->entityFactory = new EntityFactory;
		$this->view = new View($app, $controller);
		$this->setController($controller);
		$this->setMethod($method);
		$this->setViewFile($method);
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
	 * Modifie la valeur de $controller
	 * 
	 * @param string $controller
	 * Contrôleur où le traitement se passe
	 * @return void
	 * Ne retourne aucune valeur
	 * @throws ValueError
	 * Lance une exception ValueError si le paramètre passé est une chaine vide
	 */
	public function setController(string $controller): void
	{
		if(empty($controller)) {
			throw new \ValueError("Le controller ne peut pas être une chaine de caractère vide");
		}
		
		$this->controller = $controller;
	}
	
	/**
	 * Modifie la valeur de $method
	 * 
	 * @param string $method
	 * La méthodes qu'il faudra appeler
	 * @return void
	 * Ne retourne aucune valeur
	 * @throws ValueError
	 * Lance une exception ValueError si le paramètre passé est une chaine vide
	 */
	public function setMethod(string $method): void
	{
		if(empty($method)) {
			throw new \ValueError("La méthode ne peut pas être une chaine de caractère vide");
		}
		
		$this->method = $method;
	}
	
	/**
	 * Modifie la valeur de $viewFile
	 * 
	 * @param string $viewFile
	 * Le chemin vers le fichier de la vue qu'il faudra afficher
	 * @return void
	 * Ne retourne aucune valeur
	 * @throws ValueError
	 * Lance une exception ValueError si le paramètre passé est une chaine vide
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
	 * Méhtodes
	 */
	
	/**
	 * Exécute la méthode demandée si celle-ci existe
	 * 
	 * @return void
	 * Ne retourne aucune valeur
	 * @throws RuntimeException
	 * Lance une exception RuntimeException si la méthodes à appeler n'existe pas dans le controlleur
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
