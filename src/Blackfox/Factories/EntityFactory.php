<?php

/**
 * EntityFactory.php
 * @Auteur: Christophe Dufour
 * 
 * Créateur pour les entités
 */

namespace Blackfox\Factories;

use Blackfox\Entities\Entity;
use Blackfox\Exceptions\EntityErrorException;

class EntityFactory
{
    /**
     * 
     * @param string $entity
     * Le nom de l'entité à créer
     * @param array $datas
     * Un tableau de paramètre à passer au constructeur de l'entité
     * @return Entity
     * Retourne une classe qui hérite de la classe Entity
     */
    public function create(string $entity, array $datas = []): Entity
    {
        if(empty($entity)) {
			throw new \ValueError('L\'entité ne peut pas être une chaine de caractère vide.');
		}

        $entity = "Lib\Entities\\" . $entity;

        if(!class_exists($entity)) {
            throw new EntityErrorException("L'entité $entity n'a pas peu être trouvée.");
        }
        
        return new $entity($datas);
    }

}
