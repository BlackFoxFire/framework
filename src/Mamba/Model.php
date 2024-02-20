<?php

/*
*
* Model.php
* @Auteur : Christophe Dufour
*
* Classe de base pour tous les modeles de l'application.
*
*/

namespace Mamba;

abstract class Model
{
	/*
		Les attributs
		-------------
	*/
	
	// Lien avec la base de données
	protected \PDO $dao;
	
	/*
		Constructeur
		------------
	*/
	public function __construct(\PDO $dao)
	{
		$this->dao = $dao;
	}
	
	/*
		Les méthodes
		------------
	*/
	
	// Exécute une requête SQL
	public function execute(string $sql, array $data = []): \PDOStatement|false
	{
		if(empty($data)) {
			return $request = $this->dao->query($sql);
		}
		
		$request = $this->dao->prepare($sql);
		$request->execute($data);
		return $request;
	}
}
