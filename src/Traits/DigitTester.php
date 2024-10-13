<?php

/*
*
* DigitTester.php
* @Auteur : Christophe Dufour
*
* Classe de test sur des chiffres
*
*/

namespace Blackfox\Mamba\Traits;

trait DigitTester
{
	/*
		Les méthodes
		------------
	*/
	
	// Retourne true si le chiffre à vérifier est bien un entier
	public function isInt(string $value): int|false
	{
		$pattern = "#^[\s]*[-+]?[0-9]+[\s]*$#";
	
		return preg_match($pattern, $value);
	}

	// Retourne true si une valeur est positive
	public function isPositive(int|float $value): bool
	{
		if($value > 0) {
			return true;
		}

		return false;
	}
	
	// Retourne true si la valeur de l'entier $value est bien compris enter $min et $max inclut
	public function isBetween(int|float $value, int|float $min, int|float $max): bool
	{
		if(($min <= $value) && ($value <= $max)) {
			return true;
		}
		
		return false;
	}
}
