<?php

/*
*
* Hydratator.php
* @Auteur : Christophe Dufour
*
* Permet d'initialiser les différents attributs d'un objet.
*
*/

namespace Blackfox\Mamba\Entities\Traits;

trait Hydrator
{
	/*
		Les méthodes
		------------
	*/
	
	// Initialise un objet
	public function hydrate(array $data): void
	{
		foreach($data as $key => $value) {
			$method = "set" . ucfirst($key);
			
			if(is_callable(array($this, $method))) {
				$this->$method($value);
			}
		}
	}
}
