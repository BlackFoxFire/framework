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
	/**
	 * Propriétes
	 */
	
	// Lien avec la base de données
	protected mixed $dao;
	
	/**
	 * Constructeur
	 * 
	 * @param mixed $dao
	 * le lien avec la base de données
	 */
	public function __construct(mixed $dao)
	{
		$this->dao = $dao;
		//$this->table = $this->deductTableName();
	}

	/**
	 * Méthodes
	 */

	/**
	 * Exécute une requête SQL
	 * 
	 * @param string $sql
	 * La requête SQL à préparer et à exécuter
	 * @param array $data
	 * [Optionnel]
	 * Ce tableau contient une ou plusieurs paires clé=>valeur pour définir les valeurs des attributs pour l'objet PDOStatement que cette méthode retourne
	 * @return PDOStatement|false
	 * Si la requête est un succès, un objet PDOStatement est retourné, sinon false.
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
	 * Le nom de la table
	 * @return int
	 * Retourne un entier qui représente le nombre total d'enregistrement dans une table
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
	 * Le nom de la table
	 * @param sbool $fetchClass
	 * [Optionnel]
	 * Si true on retourne un tableau d'objet. Sinon c'est un tableau associatif
	 * @return array
	 * Retourne un tableau associatif ou un tableu d'objet
	 */
	public function getAll(string $table, bool $fetchClass = false): array
	{
		$sql = "SELECT * FROM $table";
		
		$request = $this->execute($sql);
		$fetchClass ? $request->setFetchMode(\PDO::FETCH_ASSOC) : $request->setFetchMode(\PDO::FETCH_CLASS);
		$datas = $request->fetchAll();
		$request->closeCursor();
		
		return $datas;
	}
	
}
