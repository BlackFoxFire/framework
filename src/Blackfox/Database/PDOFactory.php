<?php

/**
 * PDOFactory.php
 * @Auteur: Christophe Dufour
 * 
 * Gére la connexion avec la base de données en utilisant PDO
 */

namespace Blackfox\Database;

use Blackfox\Database\Interfaces\CreateInstance;

class PDOFactory implements CreateInstance
{

	/**
	 * Propriétés
	 */

	// Une instance qui représente une connexion à la base de données
	private static \PDO $instance;

	/**
	 * Méthodes
	 */

	/**
	 * Etablit la connexion avec la base de données et retourne l'instance
     * 
     * @param string $dbname
	 * Nom de la base de données
	 * @param string $username
	 * Nom d'utilisateur pour la connexion à la base de données
	 * @param string $password
     * Mot de passe de l'utilisateur
     * @return mixed
	 * Retourne une instance de PDO
	 */
	public static function getInstance(string $dbname, string $username, string $password): mixed
	{
		if(is_null(self::$instance)) {
			self::$instance = new \PDO("mysql:host=localhost;dbname=" . $dbname, $username, $password);
			self::$instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		}

		return self::$instance;
	}
	
}
