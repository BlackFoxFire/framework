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
	// Une instance qui représente une connexion à la base de données
	private static ?\PDO $instance = null;

	/**
	 * Etablit la connexion avec la base de données et retourne l'instance
     * 
     * @param string $dbname
	 * 
	 * @param string $username
	 * 
	 * @param string $password
     * 
     * @return mixed
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
