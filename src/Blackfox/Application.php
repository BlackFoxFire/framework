<?php

/**
 * Application.php
 * @Auteur: Christophe Dufour
 * 
 * Classe modélisant une application avec ses requêtes et ses réponces
 */

namespace Blackfox;

use Blackfox\Config\Config;
use Blackfox\Config\Link;
use Blackfox\Config\Enums\AreaConfig;
use Blackfox\Exceptions\InvalidRouteException;
use Blackfox\Factories\DBFactory;
use Blackfox\Factories\Enums\DatabaseAPI;
use Blackfox\Handlers\ErrorHandler;
use Blackfox\Router\Router;
use Blackfox\User\User;

abstract class Application
{
	// Dossier racine de l'application
	protected string $rootDir;
	// Dossier des fichiers sources de l'application
	protected string $appDir;
	// Namespace de l'application où l'on se trouve
	protected string $nameSpace;
	// Objet représentant une requête html
	protected HTTPRequest $httpRequest;
	// Objet représentant une réponce html
	protected HTTPResponse $httpResponse;
	// Objet représentant l'utilisateur
	protected User $user;
	// Factory de la base de données
	protected mixed $dbFactory;
	
	/**
	 * Constructeur
	 * 
	 * @param string $rootDir
	 * Le dossier racine de l'application
	 */
	public function __construct()
	{
		$classname = get_called_class();
		$this->rootDir = dirname($_SERVER['DOCUMENT_ROOT']);
		$this->nameSpace = substr($classname, 0, strrpos($classname, '\\'));
		$this->appDir = $this->rootDir . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, "/src/" . $this->nameSpace);
		$this->httpRequest = new HTTPRequest($this);
		$this->httpResponse = new HTTPResponse($this);
		$this->user = new User($this);

		Config::init($this);
		Link::init($this);
		$this->dbFactory = new DBFactory(DatabaseAPI::from(Config::get(AreaConfig::Database, 'api')),
															Config::get(AreaConfig::Database, 'dbname'), 
															Config::get(AreaConfig::Database, 'username'),
															Config::get(AreaConfig::Database, 'password'));
		ErrorHandler::init($this);
	}
	
	/**
	 * Retourne la valeur de rootDir
	 * 
	 * @return string
	 */
	public function rootDir(): string
	{
		return $this->rootDir;
	}

	/**
	 * Retourne la valeur de appDir
	 * 
	 * @return string
	 */
	public function appDir(): string
	{
		return $this->appDir;
	}

	/**
	 * Retourne la valeur de nameSpace
	 * 
	 * @return string
	 */
	public function nameSpace(): string
	{
		return $this->nameSpace;
	}

	/**
	 * Retourne la valeur de httpRequest
	 * 
	 * @return HTTPRequest
	 */
	public function httpRequest(): HTTPRequest
	{
		return $this->httpRequest;
	}
	
	/**
	 * Retourne la valeur de httpResponse
	 * 
	 * @return HTTPResponse
	 */
	public function httpResponse(): HTTPResponse
	{
		return $this->httpResponse;
	}
	
	/**
	 * Retourne la valeur de user
	 * 
	 * @return User
	 */
	public function user(): User
	{
		return $this->user;
	}

	/**
	 * Retourne la valeur de dbFactory
	 * 
	 * @return mixed
	 */
	public function dbFactory(): mixed
	{
		return $this->dbFactory;
	}

	/**
	 * Méthodes
	 */

	/**
	 * Retourne le controleur à éxécuter
	 * 
	 * @return BackController
	 */
	public function getController(): BackController
	{
		require_once $this->appDir . str_replace("/", DIRECTORY_SEPARATOR, "/routes/routes.php");

		try {
			$matchedRoute = Router::getRoute($this->httpRequest->requestURI());
		}
		catch (InvalidRouteException $e) {
			$this->httpResponse->redirect404();
		}
		
		$_GET = array_merge($_GET, $matchedRoute->vars());
		$controllerClass = $this->nameSpace . "\\Controllers\\" . $matchedRoute->controller() . "\\" . $matchedRoute->controller() . "Controller";
		
		return new $controllerClass($this, $matchedRoute->controller(), $matchedRoute->method());
	}

	/**
	 * Lance l'application
	 * 
	 * @return void
	 */
	abstract public function run(): void;
	
}
