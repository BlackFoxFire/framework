<?php

/*
*
* DigitTester.php
* @Auteur : Christophe Dufour
*
* Classe de test sur des chiffres
*
*/

namespace Mamba\Trait;

trait DigitTester
{
	/*
		Les méthodes
		------------
	*/
	
	// Retourne true si le chiffre à vérifier est bien un entier
	public function isInt(mixed $value): int|false
	{
		$pattern = "#^[\s]*[-+]?[0-9]+[\s]*$#";
	
		return preg_match($pattern, $value);
	}
	
	// Retourne true si une valeur est positive
	public function intPos(mixed $value): bool
	{
		if($this->isInt($value) && (int)$value > 0) {
			return true;
		}
		
		return false;
	}
	
	// Retourne true si la valeur de l'entier $value est bien compris enter $min et $max inclut
	public function isBetween(mixed $value, mixed $min, mixed $max): bool
	{
		if(!$this->isInt($value) || !$this->isInt($min) || !$this->isInt($max)) {
			throw new \InvalidArgumentException("La fonction isBetween n'accepte que des nombres entier !");
		}
		
		if(((int) $min <= (int) $value) && ((int) $value <= (int) $max)) {
			return true;
		}
		
		return false;
	}
}
