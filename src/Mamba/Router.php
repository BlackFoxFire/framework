<?php

/*
*
* Router.php
* @Auteur : Christophe Dufour
*
* Gére les différentes route de l'application.
*
*/

namespace Mamba;

class Router
{
	/*
		Les attributs
		-------------
	*/
	
	// Tableau des routes
	protected array $routes = [];
	
	const NO_ROUTE = 1;
	
	/*
		Les getters
		-----------
	*/
	
	// Retourne une route en fonction d'un url
	public function getRoute(string $url): Route
	{
		foreach($this->routes as $route) {
			if(($varsValues = $route->match($url)) !== false) {
				if($route->hasVars()) {
					$varsNames = $route->varsNames();
					$listVars = array();
					
					foreach($varsValues as $key => $match) {
						if($key !== 0) {
							$listVars[$varsNames[$key - 1]] = $match;
						}
					}
					
					$route->setVars($listVars);
				}
				
				return $route;
			}
		}
		
		throw new \RuntimeException("Aucune route ne correspond à l'url.", self::NO_ROUTE);
	}
	
	/*
		Les setters
		-----------
	*/
	
	// Ajoute une route à l'attribut $routes
	public function setRoutes(Route $route): void
	{
		if(!in_array($route, $this->routes)) {
			$this->routes[] = $route;
		}
	}
}
