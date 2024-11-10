<?php

/**
 * Managers.php
 * @Auteur: Christophe Dufour
 * 
 * Crée le manageur demandé.
 */

namespace Blackfox\Database;

use Blackfox\Application;
use Blackfox\Exceptions\NoConnectionException;

class Managers
{
	/**
	 * Propriétes
	 */
	
	// L'api utilisé pour accéder à la base de données
	protected string $api;
	// Le lien avec la base de données
	protected mixed $dao;
	// Namespace principale de l'application
	protected string $appName;
	// Tableau des modeles
	protected array $managers = [];
	
	/**
	 * Constructeur
	 * 
	 * @param Application $app
	 * Instance de l'application
	 * @param string $api
	 * L'api utilisé pour accéder à la base de données
	 * @param mixed $dao
	 * Le lien avec la base de données
	 */
	public function __construct(Application $app, string $api, mixed $dao)
	{
		$this->api = $api;
		$this->dao = $dao;
		//$this->appName = $app->appName();
	}
	
	/**
	 * Méthodes
	 */

	/**
	 * Retourne le modèle demandé
	 * 
	 * @param string $model
	 * Le modele demandé
	 * @return mixed
	 * Peut retourner plusieurs types d'objets
	 * @throws NoConnectionException
	 * Lance une NoConnectionException si on tente re récupérer un manageur sans connexion à une base de données
	 * @throws ValueError
	 * Lance une exception ValueError si le paramètre passé est une chaine vide
	 */
	public function getManagerOf(string $model): mixed
	{
		if(is_null($this->dao)) {
			throw new NoConnectionException("Aucune connexion avec une base de données.");
		}

		if(empty($model)) {
			throw new \ValueError('Le module spécifié est invalide');
		}
		
		if(!isset($this->managers[$model])) {
			//$manager = '\\' . $this->appName . "\\Lib\\Models\\" . $model . "Model" .  $this->api;
			//$manager = $this->appName . "\\Models\\" . $model . "Model" .  $this->api;
			$manager = "Lib\Models\\" . $model . "Model" .  $this->api;
			
			$this->managers[$model] = new $manager($this->dao);
		}
		
		return $this->managers[$model];
	}

}
