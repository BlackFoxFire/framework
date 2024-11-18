<?php

/**
 * CreateInstance.php
 * @Auteur: Christophe Dufour
 * 
 * 
 */

namespace Blackfox\Database\Interfaces;

interface CreateInstance
{
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
	 * Peut retourner plusieurs types d'instance
     */
    public static function getInstance(string $dbname, string $username, string $password): mixed;

}
