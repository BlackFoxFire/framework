<?php

/*
*
* PDOFactory.php
* @Auteur : Christophe Dufour
*
* Gére la connexion avec la base de données.
*
*/

namespace Blackfox\Mamba;

class PDOFactory
{
	/*
		Les attributs
		-------------
	*/

	// Instance de la base de données
	private static $instance;

	/*
		Les méthodes
		------------
	*/

	// Etablit la connexion avec la base de données
	public static function mysqlConnexion(array $data): null|\PDO
	{
		if(empty($data['db']) || empty($data['login']))
			return null;

		if(is_null(self::$instance)) {
			self::$instance = new \PDO("mysql:host=localhost;dbname=" . $data['db'], $data['login'], $data['password']);
			self::$instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		}
		
		return self::$instance;
	}
	
}
