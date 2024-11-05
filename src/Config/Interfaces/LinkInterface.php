<?php

/**
 * LinkInterface.php
 * @Auteur: Christophe Dufour
 * 
 * Interface pour la classe Link
 */

namespace Blackfox\Mamba\Config\Interfaces;

interface LinkInterface
{
    /**
     * Ajoute ou modifie une variable du tableau des paramètres
     * 
     * @param string $key
     * La vlé du tableau à modifier
     * @param mixed $value
     * La valeur à assigner
     * @return void
     * Ne retourne aucune valeur
     */
    public function set(string $key, mixed $value): void;

    /**
     * Vérifie l'existence d'une variable dans le tableau des paramètres
     * 
     * @param string $key
     * La clé à analyser
     * 
     * @return bool
     * Retourne true en cas de succès, sinon false
     */
    public function exists(string $key): bool;

    /**
	 * Retourne la valeur d'une variable du tableau des paramètres
	 * 
	 * @param string $key
     * La clé du tableau dont la valeur est à retourner
	 * @return mixed
     * Retourne une valeur
	 */
    public function get(string $key): mixed;

}
