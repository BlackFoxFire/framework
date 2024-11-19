<?php

/**
 * ModelFactory.php
 * @Auteur: Christophe Dufour
 * 
 * Crée le manageur demandé.
 */

namespace Blackfox\Factories;

use Blackfox\Exceptions\NoConnectionException;
use Blackfox\Factories\Enums\DatabaseAPI;

class ModelFactory
{
	/**
	 * Propriétes
	 */
	
	// L'api utilisé pour accéder à la base de données
	protected DatabaseAPI $api;
	// Le lien avec la base de données
	protected mixed $dao;
	// Tableau des modeles
	protected array $factories = [];
	
	/**
	 * Constructeur
	 * 
	 * @param DatabaseAPI $api
	 * L'api utilisé pour accéder à la base de données
	 * @param mixed $dao
	 * Le lien avec la base de données
	 */
	public function __construct(DatabaseAPI $api, mixed $dao)
	{
		$this->api = $api;
		$this->dao = $dao;
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
	public function create(string $model): mixed
	{
		if(is_null($this->dao)) {
			throw new NoConnectionException("Aucune connexion avec une base de données.");
		}

		if(empty($model)) {
			throw new \ValueError('Le modèle ne peut pas être une chaine de caractère vide.');
		}
		
		if(!isset($this->factories[$model])) {
			$factory = "Lib\Models\\" . $model . "Model" .  $this->api->value;
		
			if(!class_exists($factory)) {
				throw new \Exception("La classe usine $factory n'a pas peu être trouvée.");
			}
			
			$this->factories[$model] = new $factory($this->dao);
		}
		
		return $this->factories[$model];
	}

}
