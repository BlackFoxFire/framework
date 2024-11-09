<?php

/**
 * Entity.php
 * @Auteur: Christophe Dufour
 * 
 * Classe de base pour toutes les entités.
 */

namespace Blackfox\Entities;

use Blackfox\Entities\Traits\Hydrator;

abstract class Entity
{
	// Utilisation du trait Hydrator
	use Hydrator;
	
	/**
	 * Propriétes
	 */
	
	// Identifiant unique de l'entité
	protected int $id;
	// Tableau des éventuelles erreurs
	protected array $errors = [];
	
	/**
	 * Constructeur
	 * 
	 * @param array $data
	 * [Optionnel]
	 * Un tableau associatif avec les nom des propriétés et leurs valeurs
	 */
	public function __construct(array $data = [])
	{
		if(!empty($data)) {
			$this->hydrate($data);
		}
	}
	
	/**
	 * Getters
	 */
	
	/**
	 * Retourne la valeur de $id
	 * 
	 * @return int
	 * Retourne un entier positif non null
	 */
	public function id(): int
	{
		return $this->id;
	}
	
	/**
	 * Retourne la valeur de $errors
	 * 
	 * @return array
	 * Retourne un tableau
	 */
	public function errors(): array
	{
		return $this->errors;
	}
	
	/**
	 * Setters
	 */
	
	/**
	 * Modifie la valeur de $id
	 * 
	 * @param int $value
	 * Identifiant unique de l'entité
	 * @return void
	 * Ne retourne aucune valeur
	 */
	public function setId(int $id): void
	{
		if($id > 0) {
			$this->id = $id;
		}
	}
	
	/**
	 * Méthodes
	 */
	
	/**
	 * Vérifie s'il y a des erreurs dans le tableau $errors
	 * 
	 * @return bool
	 * Retourne true si il y a des erreurs, sinon false
	 */
	public function hasErrors(): bool
	{
		return !empty($this->errors);
	}
	
	/**
	 * Vérifie si c'est un nouvel objet
	 * 
	 * @return bool
	 * Retourne true si c'est un nouvel objet, sinon false
	 */
	public function isNew(): bool
	{
		return empty($this->id);
	}
	
	/**
	 * Vérifie si l'objet est valide
	 * 
	 * @return bool
	 * Retourne true si l'objet est valide, sinon false
	 */
	abstract public function isValid(): bool;
	
}
