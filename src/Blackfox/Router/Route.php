<?php

/**
 * Route.php
 * @Auteur: Christophe Dufour
 * 
 * Classe modélisant une route
 */

namespace Blackfox\Router;

class Route
{
	/**
     * Propriétés
     */
	
	// Url de la route
	protected string $url;
	// Contrôlleur qu'il faudra instancier
	protected string $controller;
	// La méthode qu'il faudra appeler
	protected string $method;
	// Variable à initialiser à partir de l'url
	protected array $varsNames;
	// Tableau des variables
	protected array $vars = [];

	/**
	 * Constructeur
	 * 
	 * @param string $url
	 * Url de la route
	 * @param string $controller
	 * Contrôlleur qu'il faudra instancier
	 * @param string $method
	 * La méthode qu'il faudra appeler
	 * @param array $varsNames
	 * Variable à initialiser à partir de l'url
	 */
	public function __construct(string $url, string $controller, string $method, array $varsNames)
	{
		$this->url = $url;
		$this->controller = $controller;
		$this->method = $method;
		$this->varsNames = $varsNames;
	}
	
	/**
	 * Getters
	 */
	
	/**
	 * Retourne la valeur de $url
	 * 
	 * @return string
	 * Retourne une chaine de caractère
	 */
	public function url(): string
	{
		return $this->url;
	}
	
	/**
	 * Retourne la valeur de $controller
	 * 
	 * @return string
	 * Retourne une chaine de caractère
	 */
	public function controller(): string
	{
		return $this->controller;
	}
	
	/**
	 * Retourne la valeur de $method
	 * 
	 * @return string
	 * Retourne une chaine de caractère
	 */
	public function method(): string
	{
		return $this->method;
	}
	
	/**
	 * Retourne la valeur de $varsNames
	 * 
	 * @return string
	 * Retourne une chaine de caractère
	 */
	public function varsNames(): array
	{
		return $this->varsNames;
	}
	
	/**
	 * Retourne la valeur de $vars
	 * 
	 * @return string
	 * Retourne une chaine de caractère
	 */
	public function vars(): array
	{
		return $this->vars;
	}
	
	/**
	 * Setters
	 */
	
	/**
	 * Modifie la valeur de $url
	 * 
	 * @param string $url
	 * Url de la route
	 * @return void
	 * Ne retourne aucune valeur
	 */
	public function setUrl(string $url): void
	{
		if(is_string($url)) {
			$this->url = $url;
		}
	}
	
	/**
	 * Modifie la valeur de $controller
	 * 
	 * @param string $controller
	 * Contrôlleur qu'il faudra instancier
	 * @return void
	 * Ne retourne aucune valeur
	 */
	public function setController(string $controller): void
	{
		if(is_string($controller)) {
			$this->controller = $controller;
		}
	}
	
	/**
	 * Modifie la valeur de $method
	 * 
	 * @param string $method
	 * La méthode qu'il faudra appeler
	 * @return void
	 * Ne retourne aucune valeur
	 */
	public function setMethod(string $method): void
	{
		if(is_string($method)) {
			$this->method = $method;
		}
	}
	
	/**
	 * Modifie la valeur de $varsNames
	 * 
	 * @param string $varsNames
	 * Variable à initialiser à partir de l'url
	 * @return void
	 * Ne retourne aucune valeur
	 */
	public function setVarsNames(array $varsNames): void
	{
		$this->varsNames = $varsNames;
	}
	
	/**
	 * Modifie la valeur de $vars
	 * 
	 * @param array $vars
	 * Tableau des variables
	 * @return void
	 * Ne retourne aucune valeur
	 */
	public function setVars(array $vars): void
	{
		$this->vars = $vars;
	}
	
	/**
	 * Méthodes
	 */
	
	/**
	 * Vérifie si le tableau $varsNames n'est pas vide
	 * 
	 * @return bool
	 * Retourne true si des valeurs sont présentes, sinon false
	 */
	public function hasVars(): bool
	{
		return !empty($this->varsNames);
	}
	
	/**
	 * Retourne une route qui corespont à un masque corespond
	 * 
	 * @param string $url
	 * L'url à rechercher
	 * @return array|false
	 * Retourne un tableau avec l'url en cas de succès, sinon false
	 */
	public function match(string $url): array|false
	{
		if(preg_match("#^" . $this->url . "$#", $url, $matches)) {
			return $matches;
		}
		
		return false;
	}

}
