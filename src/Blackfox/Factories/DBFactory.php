<?php

/**
 * DBFactory.php
 * @Auteur: Christophe Dufour
 * 
 * Retourne une instance de base de données en fonctions d'une api
 */

namespace Blackfox\Factories;

use Blackfox\Exceptions\FactoryErrorException;
use Blackfox\Factories\Enums\DatabaseAPI;

class DBFactory
{
    /**
     * Propriétes
     */

    // API utilisée pour l'accès à la base de données
    protected DatabaseAPI $api;
    // Nom de la base de données
    protected string $dbname;
    // Nom d'utilisateur pour la connexion à la base de données
    protected string $username;
    // Mot de passe de l'utilisateur
    protected string $password;
    // Instance de la base de données
    protected mixed $dbInstance = null;

    /**
     * Constructeur
     * 
     * @param DatabaseAPI $api
     * API utilisée pour l'accès à la base de données
     * @param string $dbname
	 * Nom de la base de données
	 * @param string $username
	 * Nom d'utilisateur pour la connexion à la base de données
	 * @param string $password
     * Mot de passe de l'utilisateur
     */
    public function __construct(DatabaseAPI $api,string $dbname, string $username, string $password)
    {
        $this->api = $api;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Méthodes
     */

    /**
     * Retourne l'instance de la base de données en fonction de l'api utilisée
     * 
     * @return mixed
     * Retourne une instance de base de données ou null si une connexion est impossible
     */
    public function get(): mixed
    {
        if(is_null($this->dbInstance)) {
            if(empty($this->dbname) || empty($this->username)) {
                return null;
            }
            
            $factory = "Blackfox\\Database\\" . $this->api->value . "Factory";

            if(!class_exists($factory)) {
                throw new FactoryErrorException("La classe usine $factory n'a pas peu être trouvée.");
            }
            
            $this->dbInstance = $factory::getInstance($this->dbname, $this->username, $this->password);
        }
        
        return $this->dbInstance;
    }

}
