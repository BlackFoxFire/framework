<?php

/**
 * DBFactory.php
 * @Auteur: Christophe Dufour
 * 
 * Retourne une instance de base de données en fonctions d'une api
 */

namespace Blackfox\Factories;

use Blackfox\Factories\Enums\DatabaseAPI;

class DBFactory
{
    /**
     * Retourne l'instance de la base de données en fonction de l'api utilisée
     * 
     * @param DatabaseAPIEnum $api
     * L'api utilisé pour accéder à la base de données
     * @param string $dbname
	 * Nom de la base de données
	 * @param string $username
	 * Nom d'utilisateur pour la connexion à la base de données
	 * @param string $password
     * Mot de passe de l'utilisateur
     * @return \PDO
     * Retourne une instance de PDO
     */
    public function createDBConnection(DatabaseAPI $api, string $dbname, string $username, string $password): \PDO
    {
        $factoryName = "Blackfox\\Database\\" . $api->value . "Factory";
        return $factoryName::getInstance($dbname, $username, $password);
    }

}
