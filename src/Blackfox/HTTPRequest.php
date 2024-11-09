<?php

/**
 * HTTPRequest.php
 * @Auteur: Christophe Dufour
 * 
 * Classe modélidant une requête html
 */

namespace Blackfox;

class HTTPRequest extends ApplicationComponent
{
	/**
	 * Méthodes
	 */
	
	/**
	 * Méthodes sur la superglobal $_GET
	 */
	
	/**
	 * Vérifie si un élément existe dans le tableau $_GET
	 * 
	 * @param string $key
	 * La clé à analyser
	 * @return bool
	 * Retourne true si la clé existe dans le tableau, sinon false
	 */
	public function getKeyExists(string $key): bool
	{
		return isset($_GET[$key]);
	}
	
	/**
	 * Vérifie si un élément est vide dans $_GET
	 * 
	 * @param string $key
	 * La clé à analyser
	 * @return bool
	 * Retourne true si l'élément du tableau est une chaine de caractère vide, sinon false
	 */
	public function getKeyEmpty(string $key): bool
	{
		return empty($_GET[$key]);
	}
	
	/**
	 * Retourne la valeur d'un éléments de $_GET
	 * 
	 * @param string $key
	 * L'élément du tableau dont la valeur est à retourner
	 * @return string
	 * Retourne une chaine de caractère ou null si l'élément n'existe pas
	 */
	public function getFromGet(string $key): string
	{
		if(isset($_GET[$key])) {
			return $_GET[$key];
		}
		
		return null;
	}
	
	/**
	 * Méthodes sur la superglobal $_POST
	 */
	
	/**
	 * Vérifie si un élément existe dans le tableau $_POST
	 * 
	 * @param string $key
	 * La clé à analyser
	 * @return bool
	 * Retourne true si la clé existe dans le tableau, sinon false
	 */
	public function postKeyExists(string $key): bool
	{
		return isset($_POST[$key]);
	}
	
	/**
	 * Vérifie si un élément est vide dans $_POST
	 * 
	 * @param string $key
	 * La clé à analyser
	 * @return bool
	 * Retourne true si l'élément du tableau est une chaine de caractère vide, sinon false
	 */
	public function postKeyEmpty(string $key): bool
	{
		return empty($_POST[$key]);
	}
	
	/**
	 * Retourne la valeur d'un éléments de $_POST
	 * 
	 * @param string $key
	 * L'élément du tableau dont la valeur est à retourner
	 * @return string
	 * Retourne une chaine de caractère ou null si l'élément n'existe pas
	 */
	public function getFromPost(string $key): string
	{
		if(isset($_POST[$key])) {
			return $_POST[$key];
		}
		
		return null;
	}
	
	/**
	 * Teste si un formulaire a été envoyé
	 * 
	 * @return bool
	 * Retourne true si un formulmaire a été envoyé, sinon false
	 */
	public function formIsSubmit(): bool
	{
		if($_SERVER['REQUEST_METHOD'] == "POST" || isset($_POST['submit'])) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Méthodes sur la superglobal $_COOKIE
	 */
	
	/**
	 * Vérifie si un élément existe dans le tableau $_COOKIE
	 * 
	 * @param string $key
	 * La clé à analyser
	 * @return bool
	 * Retourne true si la clé existe dans le tableau, sinon false
	 */
	public function cookieExists(string $key): bool
	{
		return isset($_COOKIE[$key]);
	}
	
	/**
	 * Retourne la valeur d'un éléments de $_COOKIE
	 * 
	 * @param string $key
	 * L'élément du tableau dont la valeur est à retourner
	 * @return string
	 * Retourne une chaine de caractère ou null si l'élément n'existe pas
	 */
	public function getCookie(string $key): string
	{
		if(isset($_COOKIE[$key])) {
			return $_COOKIE[$key];
		}
		
		return null;
	}
	
	/**
	 * Autres méthodes
	 */
	
	/**
	 * Retourne la valeur de l'élément REQUEST_METHOD de la super global _SERVER
	 * 
	 * @return string
	 * Retourne une chaine de caractère
	 */
	public function requestMethod(): string
	{
		return $_SERVER['REQUEST_METHOD'];
	}
	
	/**
	 * Retourne la valeur de l'élément CONTEXT_PREFIX de la super global _SERVER
	 * 
	 * @return string
	 * Retourne une chaine de caractère ou '/' si CONTEXT_PREFIX est vide
	 */
	public function contextPrefix(): string
	{
		return empty($_SERVER['CONTEXT_PREFIX']) ? '' : $_SERVER['CONTEXT_PREFIX'];
	}
	
	/**
	 * Retourne la valeur de l'élément REQUEST_URI de la super global _SERVER
	 * 
	 * @return string
	 * Retourne une chaine de caractère
	 */
	public function requestURI(): string
	{
		return empty($_SERVER['CONTEXT_PREFIX']) ? $_SERVER['REQUEST_URI'] : str_replace($_SERVER['CONTEXT_PREFIX'], '', $_SERVER['REQUEST_URI']);
	}

	/**
	 * Retourne l'url du serveur
	 * 
	 * @return string
	 * Retourne une chaine de caractère
	 */
	public function host(): string
	{
		return (isset($_SERVER['HTTPS']) && !is_null($_SERVER['HTTPS']) ? 'https' : 'http') . "://" . $_SERVER['SERVER_NAME'] . $_SERVER['CONTEXT_PREFIX'];
	}
	
}
