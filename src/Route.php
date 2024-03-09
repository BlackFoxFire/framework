<?php

/*
*
* Route.php
* @Auteur : Christophe Dufour
*
* Classe modélisant une route.
*
*/

namespace Mamba;

class Route
{
	/*
		Les attributs
		-------------
	*/
	
	protected string $url;
	protected string $controller;
	protected string $action;
	protected array $varsNames;
	protected array $vars = [];
	
	/*
		Constructeur
		------------
	*/
	public function __construct(string $url, string $controller, string $action, array $varsNames)
	{
		$this->url = $url;
		$this->controller = $controller;
		$this->action = $action;
		$this->varsNames = $varsNames;
	}
	
	/*
		Les getters
		-----------
	*/
	
	// Retourne la valeur de l'attribut $url
	public function url(): string
	{
		return $this->url;
	}
	
	// Retourne la valeur de l'attribut $controller
	public function controller(): string
	{
		return $this->controller;
	}
	
	// Retourne la valeur de l'attribut $action
	public function action(): string
	{
		return $this->action;
	}
	
	// Retourne la valeur de l'attribut $varsNames
	public function varsNames(): array
	{
		return $this->varsNames;
	}
	
	// Retourne la valeur de l'attribut $vars
	public function vars(): array
	{
		return $this->vars;
	}
	
	/*
		Les setters
		-----------
	*/
	
	// Modifie la valeur de l'attribut $url
	public function setUrl(string $url): void
	{
		if(is_string($url)) {
			$this->url = $url;
		}
	}
	
	// Modifie la valeur de l'attribut $controller
	public function setController(string $controller): void
	{
		if(is_string($controller)) {
			$this->controller = $controller;
		}
	}
	
	// Modifie la valeur de l'attribut $action
	public function setAction(string $action): void
	{
		if(is_string($action)) {
			$this->action = $action;
		}
	}
	
	// Modifie la valeur de l'attribut $varsNames
	public function setVarsNames(array $varsNames): void
	{
		$this->varsNames = $varsNames;
	}
	
	// Modifie la valeur de l'attribut $vars
	public function setVars(array $vars): void
	{
		$this->vars = $vars;
	}
	
	/*
		Les méthodes
		------------
	*/
	
	// Retourne true si le tableau $varsNames n'est pas vide. Sinon false
	public function hasVars(): bool
	{
		return !empty($this->varsNames);
	}
	
	// Retourne la route si le masque corespond à celle-ci. Sinon false
	public function match(string $url): array|false
	{
		if(preg_match("#^" . $this->url . "$#", $url, $matches)) {
			return $matches;
		}
		
		return false;
	}
}
