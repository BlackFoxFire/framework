<?php

/*
*
* Managers.php
* @Auteur : Christophe Dufour
*
* Crée le manageur demandé.
*
*/

namespace Mamba;

class Managers
{
	/*
		Les attributs
		-------------
	*/
	
	// L'api utilisé pour accéder à la base de données
	protected string $api;
	// Le lien avec la base de données
	protected \PDO|null $dao;
	// Nom de l'application, du namespace et du dossier de l'application
	protected string $appName = "";
	// Tableau des modeles
	protected array $managers = [];
	
	/*
		Constructeur
		------------
	*/
	public function __construct(string $api, \PDO|null $dao, string $appName)
	{
		$this->api = $api;
		$this->dao = $dao;
		$this->appName = $appName;
	}
	
	/*
		Les méthodes
		------------
	*/
	
	// Retourne le modele demandé
	public function getManagerOf(string $controller): mixed
	{
		if(!is_string($controller) || empty($controller)) {
			throw new \InvalidArgumentException('Le module spécifié est invalide');
		}
		
		if(!isset($this->managers[$controller])) {
			$manager = '\\' . $this->appName . "\\Lib\\Models\\" . $controller . "Model" .  $this->api;
			
			$this->managers[$controller] = new $manager($this->dao);
		}
		
		return $this->managers[$controller];
	}
}
