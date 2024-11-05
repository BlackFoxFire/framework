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
	
	/*
		Les attributs
		-------------
	*/
	
	// Identifiant unique de l'entité
	protected int|string $id;
	
	// Tableau des éventuelles erreurs
	protected array $errors = [];
	
	/*
		Constructeur
		------------
	*/
	public function __construct(array $data = [])
	{
		if(!empty($data)) {
			$this->hydrate($data);
		}
	}
	
	/*
		Les getters
		-----------
	*/
	
	// Retourne la valeur de l'attribut $id
	public function id(): int
	{
		return $this->id;
	}
	
	// Retourne la valeur de l'attribut $errors
	public function errors(): array
	{
		return $this->errors;
	}
	
	/*
		Les setters
		-----------
	*/
	
	// Modifie la valeur de l'attribut $id
	public function setId(int $value): void
	{
		if(is_numeric($value) && (int) $value > 0) {
			$this->id = (int) $value;
		}
	}
	
	/*
		Les méthodes
		------------
	*/
	
	// Retourne true si il y a des erreurs. Sinon false
	public function hasErrors(): bool
	{
		return !empty($this->errors);
	}
	
	// Retourne true si c'est un nouvel objet. Sinon false
	public function isNew(): bool
	{
		return empty($this->id);
	}
	
	// Retourne true si l'objet est valide
	abstract public function isValid(): bool;
	
}
