<?php

/**
 * CreateInstance.php
 * @Auteur: Christophe Dufour
 * 
 * Interface pour les classes de connexion à une base de données
 */

namespace Blackfox\Database\Interfaces;

interface CreateInstance
{
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
    public static function getInstance(string $dbname, string $username, string $password): mixed;

}
