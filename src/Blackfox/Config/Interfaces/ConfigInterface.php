<?php

/**
 * ConfigInterface.php
 * @Auteur: Christophe Dufour
 * 
 * Interface pour la classe Config
 */

namespace Blackfox\Config\Interfaces;

use Blackfox\Config\Enums\ConfigEnum;

interface ConfigInterface
{
    /**
	 * Ajoute ou modifie une variable du tableau des paramètres
	 * 
	 * @param string $key
     * La vlé du tableau à modifier
     * @param mixed $value
     * La valeur à assigner
     * @param ConfigEnum
     * [Optional]
     * Sous tableau où l'analyse doit se faire
	 * @return void
     * Ne retourne aucune valeur
	 */
    public function set(string $key, mixed $value, ConfigEnum $index = ConfigEnum::Frontend): void;

    /**
     * Vérifie l'existence d'une variable dans le tableau des paramètres
     * 
     * @param string $key
     * La clé à analyser
     * @param ConfigEnum $index
     * [Optional]
     * Sous tableau où l'analyse doit se faire
     * @return bool
     * Retourne true en cas de succès, sinon false
     */
    public function exists(string $key, ConfigEnum $index = ConfigEnum::Frontend): bool;

    /**
	 * Retourne la valeur d'une variable du tableau des paramètres
	 * 
	 * @param string $key
     * La clé du tableau dont la valeur est à retourner
     * @param ConfigEnum $index
     * [Optional]
     * Sous tableau où l'analyse doit se faire
	 * @return mixed
     * Retourne un tableau ou une valeur en cas de succès.
	 */
    public function get(string $key, ConfigEnum $index = ConfigEnum::Frontend): mixed;

}
