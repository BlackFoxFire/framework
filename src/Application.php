<?php

/*
*
* Application.php
* @Auteur : Christophe Dufour
*
* Classe modélisant une application html avec
* ses requêtes et ses réponces.
*
*/

namespace Blackfox\Mamba;

abstract class Application
{
	/*
		Les attributs
		-------------
	*/
	
	// Dossier racine de l'application
	protected string $rootDir;
	// Dossier des sources php de l'application
	protected string $appDir;
	// Nom de l'application, du namespace et du dossier de l'application
	protected string $appName;
	// Objet représentant une requête html
	protected HTTPRequest $httpRequest;
	// Objet représentant une réponce html
	protected HTTPResponse $httpResponse;
	// Nom de l'application
	protected string $name;
	// Objet représentant l'utilisateur
	protected User $user;
	// Object contenant la configuration de la base de données
	protected DBConfig $dbConfig;
	// Object contenant la configuration globale de l'application
	protected AppConfig $appConfig;
	// Object contenant la configuration de l'application frontend
	protected FrontConfig $frontConfig;
	// Object contenant la configuration de l'application backend
	protected BackConfig $backConfig;
	// Object contenant les liens de l'application
	protected Link $link;

	
	/*
		Constructeur
		------------
	*/
	public function __construct(string $rootDir, string $appDir, string $appName)
	{
		$this->rootDir = $rootDir;
		$this->appDir = $appDir;
		$this->appName = $appName;
		$this->httpRequest = new HTTPRequest($this);
		$this->httpResponse = new HTTPResponse($this);
		$this->user = new User($this);
		$this->dbConfig = new DBConfig($this);
		$this->appConfig = new AppConfig($this);
		$this->frontConfig = new FrontConfig($this);
		$this->backConfig = new BackConfig($this);
		$this->link = new Link($this);
	}
	
	/*
		Les getters
		-----------
	*/
	
	// Retourne l'objet $rootDir
	public function rootDir(): string
	{
		return $this->rootDir;
	}

	// Retourne l'objet $appDir
	public function appDir(): string
	{
		return $this->appDir;
	}

	// Retourne l'objet $appName
	public function appName(): string
	{
		return $this->appName;
	}

	// Retourne l'objet $httpRequest
	public function httpRequest(): HTTPRequest
	{
		return $this->httpRequest;
	}
	
	// Retourne l'objet $httpResponse
	public function httpResponse(): HTTPResponse
	{
		return $this->httpResponse;
	}
	
	// Retourne la valeur de l'attribut $name
	public function name(): string
	{
		return $this->name;
	}
	
	// Retourne la valeur de l'attribut $user
	public function user(): User
	{
		return $this->user;
	}

	// Retourne la valeur de $dbConfig
	public function dbConfig(): DBConfig
	{
		return $this->dbConfig;
	}

	// Retourne la valeur de $appConfig
	public function appConfig(): AppConfig
	{
		return $this->appConfig;
	}

	// Retourne la valeur de $config
	public function frontConfig(): FrontConfig
	{
		return $this->frontConfig;
	}

	// Retourne la valeur de $config
	public function backConfig(): BackConfig
	{
		return $this->backConfig;
	}

	// Retourne la valeur de $link
	public function link(): Link
	{
		return $this->link;
	}
	
	/*
		Les méthodes
		------------
	*/
	
	// Retourne le controleur à éxécuter
	public function getController(): BackController
	{
		$router = new Router;
		
		$xml = new \DOMDocument;
		$xml->load($this->appDir . "App" . DIRECTORY_SEPARATOR . $this->name . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "routes.xml");
		
		$routes = $xml->getElementsByTagName("route");
		
		foreach($routes as $route) {
			$vars = array();
			
			if ($route->hasAttribute('vars')) {
				$vars = explode(',', $route->getAttribute('vars'));
			}
			
			$router->setRoutes(new Route($route->getAttribute('url'), $route->getAttribute('controller'),
																		$route->getAttribute('action'), $vars));
		}
		
		try {
			$matchedRoute = $router->getRoute($this->httpRequest->requestURI());
		}
		catch (\RuntimeException $e) {
			if ($e->getCode() == Router::NO_ROUTE) {
				$this->httpResponse->redirect404();
			}
		}
		
		$_GET = array_merge($_GET, $matchedRoute->vars());
		
		$controllerClass = $this->appName . "\\App\\" . $this->name . "\\Modules\\" . $matchedRoute->controller() . DIRECTORY_SEPARATOR . $matchedRoute->controller() . "Controller";
		
		return new $controllerClass($this, $matchedRoute->controller(), $matchedRoute->action());
	}
	
	// Lance l'application
	abstract public function run(): void;
	
}
