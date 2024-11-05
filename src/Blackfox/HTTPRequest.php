<?php

/*
*
* HTTPRequest.php
* @Auteur : Christophe Dufour
*
* Classe modélidant une requête html.
*
*/

namespace Blackfox;

class HTTPRequest extends ApplicationComponent
{
	/*
		Les méthodes
		------------
	*/
	
	/*
		Fonction sur la superglobal $_GET
	*/
	
	// Vérifie si un élément existe dans le tableau $_GET
	public function getKeyExists(string $key): bool
	{
		return isset($_GET[$key]);
	}
	
	// Vérifie si un élément est vide dans $_GET
	public function getKeyEmpty(string $key): bool
	{
		return empty($_GET[$key]);
	}
	
	// Retourne la valeur d'un des éléments de $_GET
	public function getFromGet(string $key, mixed $returnValue = null): mixed
	{
		if(isset($_GET[$key])) {
			return $_GET[$key];
		}
		
		return $returnValue;
	}
	
	/*
		Fonction sur la superglobal $_POST
	*/
	
	// Vérifie si un élément existe dans le tableau $_POST
	public function postKeyExists(string $key): bool
	{
		return isset($_POST[$key]);
	}
	
	// Vérifie si un élément est vide dans $_POST
	public function postKeyEmpty(string $key): bool
	{
		return empty($_POST[$key]);
	}
	
	// Retourne la valeur d'un des éléments de $_POST
	public function getFromPost(string $key, mixed $returnValue = null): mixed
	{
		if(isset($_POST[$key])) {
			return $_POST[$key];
		}
		
		return $returnValue;
	}
	
	// Teste si un formulaire a été envoyé
	// Retourne true si c'est le cas, sinon false
	public function formIsSubmit(): bool
	{
		if($_SERVER['REQUEST_METHOD'] == "POST" || isset($_POST['submit'])) {
			return true;
		}
		
		return false;
	}
	
	/*
		Fonction sur la superglobal $_COOKIE
	*/
	
	// Vérifie si un élément existe dans le tableau $_COOKIE
	public function cookieExists(string $key): bool
	{
		return isset($_COOKIE[$key]);
	}
	
	// Retourne la valeur d'un des éléments de $_COOKIE
	public function getCookie(string $key, mixed $returnValue = null): mixed
	{
		if(isset($_COOKIE[$key])) {
			return $_COOKIE[$key];
		}
		
		return $returnValue;
	}
	
	/*
		Autres
	*/
	
	// Retourne la valeur de l'élément REQUEST_METHOD de la super global _SERVER
	public function requestMethod(): string
	{
		return $_SERVER['REQUEST_METHOD'];
	}
	
	// Retourne la valeur de l'élément CONTEXT_PREFIX de la super global _SERVER
	// Si CONTEXT_PREFIX est vide, retourne /
	public function contextPrefix(): string
	{
		return empty($_SERVER['CONTEXT_PREFIX']) ? '' : $_SERVER['CONTEXT_PREFIX'];
	}
	
	// Retourne la valeur de l'élément REQUEST_URI de la super global _SERVER
	public function requestURI(): string
	{
		return empty($_SERVER['CONTEXT_PREFIX']) ? $_SERVER['REQUEST_URI'] : str_replace($_SERVER['CONTEXT_PREFIX'], '', $_SERVER['REQUEST_URI']);
	}

	// Retourne l'url du serveur
	public function host(): string
	{
		return (isset($_SERVER['HTTPS']) && !is_null($_SERVER['HTTPS']) ? 'https' : 'http') . "://" . $_SERVER['SERVER_NAME'] . $_SERVER['CONTEXT_PREFIX'];
	}
}
