<?php

/**
 * Application.php
 * @Auteur: Christophe Dufour
 * 
 * Classe modélisant une application avec ses requêtes et ses réponces
 */

namespace Blackfox;

use Blackfox\User\User;
use Blackfox\Config\Link;
use Blackfox\Config\Config;
use Blackfox\Router\Router;
use Blackfox\Exceptions\InvalidRouteException;
use Blackfox\Handler\ErrorHandler;

abstract class Application
{
	/**
	 * Propriétes
	 */
	
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
	// Nom de l'application
	protected string $name;
	// Objet représentant l'utilisateur
	protected User $user;
	// Object contenant les paramètres de configuration de l'application
	protected Config $config;
	// Object contenant les liens de l'application
	protected Link $link;
	
	/**
	 * Constructeur
	 * 
	 * @param string
	 * Le dossier racine de l'application
	 * @param string
	 * Le dossier des fichiers sources de l'application
	 * @param string
	 * Namespace de l'application
	 */
	public function __construct(string $rootDir, string $appDir, string $nameSpace)
	{
		$this->rootDir = $rootDir;
		$this->appDir = $appDir;
		$this->nameSpace = $nameSpace;
		$this->httpRequest = new HTTPRequest($this);
		$this->httpResponse = new HTTPResponse($this);
		$this->user = new User($this);
		$this->config = Config::getInstance($this);
		$this->link = Link::getInstance($this);

		ErrorHandler::init($this);
	}
	
	/**
	 * Getters
	 */
	
	/**
	 * Retourne la valeur de $rootDir
	 * 
	 * @return string
	 * Retourne une chaine de caractère
	 */
	public function rootDir(): string
	{
		return $this->rootDir;
	}

	/**
	 * Retourne la valeur de $appDir
	 * 
	 * @return string
	 * Retourne une chaine de caractère
	 */
	public function appDir(): string
	{
		return $this->appDir;
	}

	/**
	 * Retourne la valeur de $nameSpace
	 * 
	 * @return string
	 * Retourne une chaine de caractère
	 */
	public function nameSpace(): string
	{
		return $this->nameSpace;
	}

	/**
	 * Retourne la valeur de $name
	 * 
	 * @return string
	 * Retourne une chaine de caractère
	 */
	public function name(): string
	{
		return $this->name;
	}

	/**
	 * Retourne la valeur de $httpRequest
	 * 
	 * @return HTTPRequest
	 * Retourne un objet Blackfox\HTTPRequest
	 */
	public function httpRequest(): HTTPRequest
	{
		return $this->httpRequest;
	}
	
	/**
	 * Retourne la valeur de $httpResponse
	 * 
	 * @return HTTPResponse
	 * Retourne un objet Blackfox\HTTPResponse
	 */
	public function httpResponse(): HTTPResponse
	{
		return $this->httpResponse;
	}
	
	/**
	 * Retourne la valeur de $user
	 * 
	 * @return User
	 * Retourne un objet Blackfox\User
	 */
	public function user(): User
	{
		return $this->user;
	}

	/**
	 * Retourne la valeur de $config
	 * 
	 * @return Config
	 * Retourne un objet Blackfox\Config
	 */
	public function config(): Config
	{
		return $this->config;
	}

	/**
	 * Retourne la valeur de $link
	 * 
	 * @return Link
	 * Retourne un objet Blackfox\Link
	 */
	public function link(): Link
	{
		return $this->link;
	}
	
	/**
	 * Méthodes
	 */

	/**
	 * Retourne le controleur à éxécuter
	 * 
	 * @return BackController
	 * Retourne un objet Blackfox\BackController
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
	 * Ne retourne aucune valeur
	 */
	abstract public function run(): void;
	
}
