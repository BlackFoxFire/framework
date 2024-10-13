<?php

/*
*
* User.php
* @Auteur : Christophe Dufour
*
* Classe modélidant un utilisateur de l'application.
*
*/

namespace Blackfox\Mamba;

class User extends ApplicationComponent
{
	/*
		Constructeur
		------------
	*/
	public function __construct(Application $application)
	{
		parent::__construct($application);
		
		session_set_cookie_params(0, $this->app->httpRequest()->contextPrefix(), "", false, true);
		session_start();
	}
	
	/*
		Les méthodes
		------------
	*/
	
	// Ajoute ou modifie une variable de session utilisateur
	public function set(string $key, mixed $value = ""): void
	{
		$_SESSION[$key] = $value;
	}
	
	// Renvoie la valeur d'une variable de session utilisateur
	// Si celui ci n'existe pas, c'est returnValue qui est renvoyé
	public function get(string $key, mixed $returnValue = null): mixed
	{
		if(isset($_SESSION[$key]))
			return $_SESSION[$key];
		
		return $returnValue;
	}
	
	// Supprime une variable de session
	public function delete(string $key): void
	{
		if(isset($_SESSION[$key]))
			unset($_SESSION[$key]);
	}
	
	// Teste si une variable de session utilisateur existe
	// Renvoie true s'il existe, sinon false
	public function exists(string $key): bool
	{
		return isset($_SESSION[$key]);
	}
	
	// Méthode magique appellée lorque que l'on appelle une méthode qui n'existe pas
	// Renvoie la valeur d'une variable de session utilisateur
	public function __call(string $method, array $arguments): mixed
	{
		if(isset($_SESSION[$method]))
			return $_SESSION[$method];

		return null;
	}
	
	// Vérifie si un utilisateur est authentifié
	// Retourne true si oui. Sinon false
	public function isAuthenticated(): bool
	{
		if(isset($_SESSION['isAuthenticated']) && $_SESSION['isAuthenticated'] === true)
			return true;
		
		return false;
	}
	
	// Modifie le statut d'authentifié
	public function setAuthenticated(bool $value = true): void
	{
		if(!is_bool($value)) {
			throw new \InvalidArgumentException(
				"La valeur spécifiée à la méthode User::setAuthenticated() doit être un boolean");
		}
		
		$_SESSION['isAuthenticated'] = $value;
	}
	
	// Définit un message pour l'utilisateur
	public function setMessage(string $value, bool $errorMessage = false): void
	{
		if(!empty($value) && is_string($value)) {
			$_SESSION['hasMessage'] = $value;
			$_SESSION['errorMessage'] = $errorMessage;
		}
	}
	
	// Retourne un message utilisateur si celui-ci existe
	public function getMessage(): string
	{
		if(isset($_SESSION['hasMessage'])) {
			$message = $_SESSION['hasMessage'];
			unset($_SESSION['hasMessage']);
			unset($_SESSION['errorMessage']);
				
			return $message;
		}
	}
	
	// Vérifie si un message est présent
	// Renvoie true si oui. Sinon false
	public function hasMessage(): bool
	{
		if(isset($_SESSION['hasMessage']))
			return true;
		
		return false;
	}

	// Vérifie si c'est un message d'erreur
	public function errorMessage(): bool
	{
		return $_SESSION['errorMessage'];
	}
	
	// Détruit la session de l'utilisateur actuelle
	public function destroy(): void
	{
		session_destroy();
	}
}
