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
	protected string $table;
	
	/*
		Constructeur
		------------
	*/
	public function __construct(\PDO $dao)
	{
		$this->dao = $dao;
		$this->table = $this->deductTableName();
	}
	
	// Déduit le nom de la table en fonction du nom du model
	protected function deductTableName(): string
	{
		$array = explode('\\', get_class($this));

		return strtolower(str_replace("ModelPDO", "", $array[array_key_last($array)]));
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

	// Renvoie le nombre total d'enregistrement dans une table de la base de données
	public function count(): int
	{
		$sql =  "SELECT COUNT(*) FROM {$this->table}";
		
		$request = $this->execute($sql);
		$counter = $request->fetch()[0];
		$request->closeCursor();
		
		return $counter;
	}

	// Retourne tous les enregistrements d'une table sous la forme d'un tableau associatif
	// ou sous la forme d'une tableau d'objet
	public function all(bool $fetchClass = false): array
	{
		$sql = "SELECT * FROM {$this->table}";
		
		$request = $this->execute($sql);
		$fetchClass ? $request->setFetchMode(\PDO::FETCH_ASSOC) : $request->setFetchMode(\PDO::FETCH_CLASS);
		$datas = $request->fetch();
		$request->closeCursor();
		
		return $datas;
	}
}
