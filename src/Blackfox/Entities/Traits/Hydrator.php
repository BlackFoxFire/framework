<?php

/**
 * Hydratator.php
 * @Auteur: Christophe Dufour
 * 
 * Permet d'initialiser les différents attributs d'un objet
 */

namespace Blackfox\Entities\Traits;

trait Hydrator
{	
	/**
	 * Initialise un objet
	 * 
	 * @param array $data
	 * 
	 * @return void
	 */
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
