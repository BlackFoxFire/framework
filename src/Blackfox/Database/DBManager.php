<?php

/**
 * DBManager.php
 * @Auteur: Christophe Dufour
 * 
 * Retourne une instance de base de données en fonctions d'une api
 */

namespace Blackfox\Database;

class DBManager
{
    /**
     * Retourne l'instance de la base de données en fonction de l'api utilisée
     * 
     * @param string $api
     * L'api utilisé pour accéder à la base de données
     * @param string $dbname
	 * Nom de la base de données
	 * @param string $username
	 * Nom d'utilisateur pour la connexion à la base de données
	 * @param string $password
     * Mot de passe de l'utilisateur
     * @return mixed
     * Retourne une instance qui représente une connexion à la base de données ou null si une connexion est impossible
     */
    public static function get(string $api, string $dbname, string $username, string $password): mixed
    {
        if(empty($api)) {
            return null;
        }

        $factoryName = "Blackfox\\Database\\" . $api . "Factory";
        return $factoryName::getInstance($dbname, $username, $password);
    }

}
