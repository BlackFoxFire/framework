<?php

/*
*
* DBFactory.php
* @Auteur : Christophe Dufour
*
* Gére la connexion avec la base de données.
*
*/

namespace Blackfox\Mamba\Database;

abstract class DBFactory
{
	/**
	 * Propriétes
	 */

	 // Instance de la base de données
	protected static mixed $instance = null;

	/**
	 * Méthodes
	 */

	 /**
     * Retourne ou crée l'instance de la base de données
     * 
     * @param string $dbname
	 * Nom de la base de données
	 * @param string $username
	 * Nom d'utilisateur pour la connexion à la base de données
	 * @param string $password
     * Mot de passe de l'utilisateur
     * @return mixed
	 * Retourne une instance qui représente une connexion à la base de données ou null si une connexion est impossible
     */
	public static function dbConnection(string $dbname, string $username, string $password): mixed
	{
		if(self::checkConnectionDatas($dbname, $username)) {
			if(is_null(self::$instance)) {
				static::createInstance($dbname, $username, $password);
			}

			return self::$instance;
		}

		return null;
	}

	/**
	 * Vérifie les données de connexion à la base de données
	 * 
	 * @param string $dbname
	 * Nom de la base de données
	 * @param string $username
	 * Nom d'utilisateur pour la connexion à la base de données
	 * @return bool
	 * Retourne true si les données sont valide, sinon false
	 */
	protected static function checkConnectionDatas(string $dbname, string $username): bool
	{
		if(empty($dbname) || empty($username)) {
			return false;
		}

		return true;
	}

	/**
     * Crée une instance pour faire le lien avec la base de données
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
	abstract protected static function createInstance(string $dbname, string $username, string $password): void;
	
}
