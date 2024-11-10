<?php

/**
 * Router.php
 * @Auteur: Christophe Dufour
 * 
 * Gére les différentes route de l'application
 */

namespace Blackfox\Router;

use Blackfox\Exceptions\InvalidRouteException;

class Router
{
    /**
     * Propriétés
     */

    // Tableau des routes
    private static array $routes = [];

    /**
     * Méthodes
     */

    /**
     * Recherche et retourne une route en fonction d'une url
     * 
     * @param string $url
     * L'url de la route à rechercher
     * @return Route
     * Retourne un objet Balckfox\Route en cas de succès
     * @throws InvalidRouteException
     * Lance une exception InvalidRouteException si une route n'existe pas
     */
    public static function getRoute(string $url): Route
    {
        foreach(self::$routes as $route) {
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
		
		throw new InvalidRouteException("Aucune route ne correspond cette url. ($url)");
    }

    /**
     * Ajoute une route
     * 
     * @param string $url
     * Url de la route
     * @param string $controller
     * Contrôlleur qu'il faudra instancier
     * @param string $method
     * La méthode qu'il faudra appeler
     * @param string $vars
     * [Optionnel]
     * Variable à initialiser à partir de l'url
     * @return void
     * Ne retourne aucune valeur
     */
    public static function set(string $url, string $controller, string $method, string $varsNames = ""): void
    {
        $vars = [];

        if(!empty($varsNames)) {
            $vars = explode(',', $varsNames);
        }

        self::$routes[] = new Route($url, $controller, $method, $vars);
    }

}
