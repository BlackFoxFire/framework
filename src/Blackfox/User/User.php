<?php

/**
 * User.php
 * @Auteur: Christophe Dufour
 * 
 * Classe modélidant un utilisateur
 */

namespace Blackfox\User;

use Blackfox\Application;
use Blackfox\ApplicationComponent;

class User extends ApplicationComponent
{
	/**
	 * Constructeur
	 * 
	 * @param Application $application
	 * Instance de l'application
	 */
	public function __construct(Application $application)
	{
		parent::__construct($application);

		$cookieParams = array(
			'lifetime' 	=> 0,
			'path' 		=> $this->app->httpRequest()->contextPrefix(),
			'domain' 	=> "",
			'secure' 	=> true,
			'httponly' 	=> true,
			'samesite' 	=> 'Strict'
		);
		
		session_set_cookie_params($cookieParams);
		session_start();
	}

	/**
	 * Méthodes
	 */
	
	/**
	 * Ajoute ou modifie une variable de session
	 * 
	 * @param string $key
	 * La variable à ajouter ou modifier
	 * @param mixed $value
	 * [Optionnel]
	 * La valeur à affecter à la variable
	 * @return void
	 * Ne retoune aucune valeur
	 */
	public function set(string $key, mixed $value = ""): void
	{
		$_SESSION[$key] = $value;
	}

	/**
	 * Vérifie l'existence d'une variable de session
	 * 
	 * @param string $key
	 * La variable à analyser
	 * @return bool
	 * Retourne true si la variable existe, sinon false
	 */
	public function exists(string $key): bool
	{
		return isset($_SESSION[$key]);
	}
	
	/**
	 * Retourne la valeur d'une variable de session
	 * 
	 * @param string $key
	 * La variable à retouner
	 * @param mixed $returnValue
	 * [Optionnel]
	 * Valeur de retour personnalisée en cas d'erreur
	 * @return mixed
	 * Peut retourner tout type de valeur.
	 */
	public function get(string $key, mixed $returnValue = null): mixed
	{
		if(isset($_SESSION[$key]))
			return $_SESSION[$key];
		
		return $returnValue;
	}
	
	/**
	 * Supprime une variable de session
	 * 
	 * @param string $key
	 * La variable à supprimer
	 * @return void
	 * Ne retourne aucune valeur
	 */
	public function delete(string $key): void
	{
		if(isset($_SESSION[$key]))
			unset($_SESSION[$key]);
	}
	
	/**
	 * Vérifie si un utilisateur est authentifié
	 * 
	 * @return bool
	 * Retourne true si l'utilisateur s'est authentifié. Sinon false
	 */
	public function isAuthenticated(): bool
	{
		if(isset($_SESSION['isAuthenticated']) && $_SESSION['isAuthenticated'] === true)
			return true;
		
		return false;
	}
	
	/**
	 * Modifie le statut d'authentification de l'utilisateur
	 * 
	 * @param bool $value
	 * [Optionnel]
	 * Si true, l'utilisateur s'est authentifié. False si c'est pas le cas.
	 * @return void
	 * Ne retourne aucune valeur
	 * @throws InvalidArgumentException
	 * Lance une exception InvalidArgumentException le paramètre passé à cette méthode n'est pas un booléen
	 */
	public function setAuthenticated(bool $value = true): void
	{
		if(!is_bool($value)) {
			throw new \InvalidArgumentException("La valeur spécifiée à la méthode User::setAuthenticated() doit être un boolean");
		}
		
		$_SESSION['isAuthenticated'] = $value;
	}
	
	/**
	 * Définit un message pour l'utilisateur
	 * 
	 * @param string $value
	 * Le message à l'attention de l'utilisateur
	 * @param string bool $errorMessage
	 * [Optionnel]
	 * Si true, le message est un message d'erreur. False si c'est pas le cas.
	 * @return void
	 * Ne retourne aucune valeur
	 */
	public function setMessage(string $value, bool $errorMessage = false): void
	{
		if(!empty($value) && is_string($value)) {
			$_SESSION['hasMessage'] = $value;
			$_SESSION['errorMessage'] = $errorMessage;
		}
	}
	
	/**
	 * Retourne un message destiné à l'utilisateur si celui-ci existe
	 * 
	 * @return string
	 * Le message à l'attention de l'utilisateur
	 */
	public function getMessage(): string
	{
		if(isset($_SESSION['hasMessage'])) {
			$message = $_SESSION['hasMessage'];
			unset($_SESSION['hasMessage']);
			unset($_SESSION['errorMessage']);
				
			return $message;
		}
	}
	
	/**
	 * Vérifie si un message est présent pour l'utilisateur
	 * 
	 * @return bool
	 * Retourne true si un message est présent. Sinon false
	 */
	public function hasMessage(): bool
	{
		if(isset($_SESSION['hasMessage']))
			return true;
		
		return false;
	}

	/**
	 * Vérifie si c'est un message d'erreur
	 * 
	 * @return bool
	 * Retourne true si un message d'erreur est présent. Sinon false
	 */
	public function errorMessage(): bool
	{
		return $_SESSION['errorMessage'];
	}
	
	/**
	 * Détruit une session utilisateur
	 * 
	 * @return void
	 * Ne retourne aucune valeur
	 */
	public function destroy(): void
	{
		session_destroy();
	}

	/**
	 * Méthode magique appellée lorque que l'on appelle une méthode qui n'existe pas.
	 * Renvoie la valeur d'une variable de session utilisateur.
	 * 
	 * @param string $method
	 * La méthode appelée
	 * @param array $arguments
	 * Les paralètres de la méthode
	 * @return mixed
	 * Peut retourner tout type de données
	 */
	public function __call(string $method, array $arguments): mixed
	{
		if(isset($_SESSION[$method]))
			return $_SESSION[$method];

		return null;
	}
	
}
