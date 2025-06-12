<?php

/**
 * Model.php
 * @Auteur: Christophe Dufour
 * 
 * Classe de base pour tous les modèles de l'application
 */

namespace Blackfox\Database;

abstract class Model
{
	// Lien avec la base de données
	protected mixed $dao;
	
	/**
	 * Constructeur
	 * 
	 * @param mixed $dao
	 */
	public function __construct(mixed $dao)
	{
		$this->dao = $dao;
	}

	/**
	 * Exécute une requête SQL
	 * 
	 * @param string $sql
	 * 
	 * @param array $data
	 * [Optionnel]
	 * Ce tableau contient une ou plusieurs paires clé=>valeur pour définir les valeurs des attributs pour l'objet PDOStatement que cette méthode retourne
	 * @return PDOStatement|false
	 */
	public function execute(string $sql, array $data = []): \PDOStatement|false
	{
		if(empty($data)) {
			return $request = $this->dao->query($sql);
		}
		
		$request = $this->dao->prepare($sql);
		$request->execute($data);
		return $request;
	}

	/**
	 * Renvoie le nombre total d'enregistrement dans une table
	 * 
	 * @param string $table
	 * 
	 * @return int
	 */
	public function count(string $table): int
	{
		$sql =  "SELECT COUNT(*) FROM $table";
		
		$request = $this->execute($sql);
		$counter = $request->fetch()[0];
		$request->closeCursor();
		
		return $counter;
	}

	/**
	 * Retourne tous les enregistrements d'une table
	 * 
	 * @param string $table
	 * 
	 * @param bool $fetchClass
	 * [Optionnel]
	 * Si true on retourne un tableau d'objet. Sinon c'est un tableau associatif
	 * @param string $class
	 * [Optionnel]
	 * Si le paramètre $fetchClass est égal à true, $class doit contenir le nom de la classe utiliser
	 * @return array
	 */
	public function getAll(string $table, bool $fetchClass = false, string $class = ""): array
	{
		$sql = "SELECT * FROM $table";
		
		$request = $this->execute($sql);
		$fetchClass ? $request->setFetchMode(\PDO::FETCH_ASSOC) : $request->setFetchMode(\PDO::FETCH_CLASS, $class);
		$datas = $request->fetchAll();
		$request->closeCursor();
		
		return $datas;
	}
	
}
