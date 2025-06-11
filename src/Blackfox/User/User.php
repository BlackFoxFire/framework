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
	 * Ajoute ou modifie une variable de session
	 * 
	 * @param string $name
	 * 
	 * @param mixed $value
	 * 
	 * @return void
	 */
	public function set(string $name, mixed $value): void
	{
		$_SESSION[$name] = $value;
	}

	/**
	 * Ajoute ou modifie une variable de session
	 * 
	 * @param string $name
	 * 
	 * @param mixed $value
	 * 
	 * @return void
	 */
	public function __set(string $name, mixed $value): void
	{
		$_SESSION[$name] = $value;
	}

	/**
	 * Vérifie l'existence d'une variable de session
	 * 
	 * @param string $name
	 * 
	 * @return bool
	 * Retourne true si la variable existe, sinon false
	 */
	public function exists(string $name): bool
	{
		return isset($_SESSION[$name]);
	}
	
	/**
	 * Vérifie l'existence d'une variable de session
	 * 
	 * @param string $name
	 * 
	 * @return bool
	 * Retourne true si la variable existe, sinon false
	 */
	public function __isset(string $name): bool
	{
		return isset($_SESSION[$name]);
	}

	/**
	 * Retourne la valeur d'une variable de session
	 * 
	 * @param string $name
	 * 
	 * @return mixed
	 */
	public function get(string $name): mixed
	{
		if(isset($_SESSION[$name])) {
			return $_SESSION[$name];
		}
		
		return null;
	}

	/**
	 * Retourne la valeur d'une variable de session
	 * 
	 * @param string $name
	 * 
	 * @return mixed
	 */
	public function __get(string $name): mixed
	{
		if(isset($_SESSION[$name])) {
			return $_SESSION[$name];
		}

		return null;
	}
	
	/**
	 * Supprime une variable de session
	 * 
	 * @param string $name
	 * 
	 * @return void
	 */
	public function unset(string $name): void
	{
		if(isset($_SESSION[$name])) {
			unset($_SESSION[$name]);
		}
	}

	/**
	 * Supprime une variable de session
	 * 
	 * @param string $name
	 * 
	 * @return void
	 */
	public function __unset($name): void
	{
		if(isset($_SESSION[$name])) {
			unset($_SESSION[$name]);
		}
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
	 * Si true, l'utilisateur s'est authentifié. False si ce n'est pas le cas.
	 * @return void
	 * 
	 * @throws InvalidArgumentException
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
	 * @param string $message
	 * 
	 * @param string bool $errorMessage
	 * [Optionnel]
	 * Si true, le message est un message d'erreur
	 * @return void
	 */
	public function setMessage(string $message, bool $errorMessage = false): void
	{
		if(!empty($message) && is_string($message)) {
			$_SESSION['hasMessage'] = $message;
			$_SESSION['errorMessage'] = $errorMessage;
		}
	}
	
	/**
	 * Retourne un message destiné à l'utilisateur si celui-ci existe
	 * 
	 * @return string
	 */
	public function getMessage(): string|null
	{
		if(isset($_SESSION['hasMessage'])) {
			$message = $_SESSION['hasMessage'];
			unset($_SESSION['hasMessage']);
			unset($_SESSION['errorMessage']);
				
			return $message;
		}

		return null;
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
	 */
	public function destroy(): void
	{
		session_destroy();
	}
	
}
