<?php

/**
 * PDOFactory.php
 * @Auteur: Christophe Dufour
 * 
 * Gére la connexion avec la base de données.
 */

namespace Blackfox\Database;

use Blackfox\Database\DBFactory;

class PDOFactory extends DBFactory
{

	/**
	 * Méthodes
	 */

	/**
	 * Etablit la connexion avec la base de données
     * 
     * @param string $dbname
	 * Nom de la base de données
	 * @param string $username
	 * Nom d'utilisateur pour la connexion à la base de données
	 * @param string $password
     * Mot de passe de l'utilisateur
     * @return void
	 * Ne retourne aucune valeur
     */
	protected static function createInstance(string $dbname, string $username, string $password): void
	{
		self::$instance = new \PDO("mysql:host=localhost;dbname=" . $dbname, $username, $password);
		self::$instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}
	
}
