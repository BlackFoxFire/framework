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
	 * Vérifie si un élément existe dans le tableau $_GET
	 * 
	 * @param string $key
	 * 
	 * @return bool
	 */
	public function getKeyExists(string $key): bool
	{
		return isset($_GET[$key]);
	}
	
	/**
	 * Vérifie si un élément est vide dans $_GET
	 * 
	 * @param string $key
	 * 
	 * @return bool
	 */
	public function getKeyEmpty(string $key): bool
	{
		return empty($_GET[$key]);
	}
	
	/**
	 * Retourne la valeur d'un éléments de $_GET
	 * 
	 * @param string $key
	 * 
	 * @return string|null
	 */
	public function getFromGet(string $key): string|null
	{
		if(isset($_GET[$key])) {
			return $_GET[$key];
		}
		
		return null;
	}
	
	/**
	 * Vérifie si un élément existe dans le tableau $_POST
	 * 
	 * @param string $key
	 * 
	 * @return bool
	 */
	public function postKeyExists(string $key): bool
	{
		return isset($_POST[$key]);
	}
	
	/**
	 * Vérifie si un élément est vide dans $_POST
	 * 
	 * @param string $key
	 * 
	 * @return bool
	 */
	public function postKeyEmpty(string $key): bool
	{
		return empty($_POST[$key]);
	}
	
	/**
	 * Retourne la valeur d'un éléments de $_POST
	 * 
	 * @param string $key
	 * 
	 * @return string|null
	 */
	public function getFromPost(string $key): string|null
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
	 * Vérifie si un élément existe dans le tableau $_COOKIE
	 * 
	 * @param string $key
	 * 
	 * @return bool
	 */
	public function cookieExists(string $key): bool
	{
		return isset($_COOKIE[$key]);
	}
	
	/**
	 * Retourne la valeur d'un éléments de $_COOKIE
	 * 
	 * @param string $key
	 * 
	 * @return string|null
	 */
	public function getCookie(string $key): string|null
	{
		if(isset($_COOKIE[$key])) {
			return $_COOKIE[$key];
		}
		
		return null;
	}
	
	/**
	 * Retourne la valeur de l'élément REQUEST_METHOD de la super global _SERVER
	 * 
	 * @return string
	 */
	public function requestMethod(): string
	{
		return $_SERVER['REQUEST_METHOD'];
	}
	
	/**
	 * Retourne la valeur de l'élément CONTEXT_PREFIX de la super global _SERVER
	 * 
	 * @return string
	 */
	public function contextPrefix(): string
	{
		return empty($_SERVER['CONTEXT_PREFIX']) ? '' : $_SERVER['CONTEXT_PREFIX'];
	}
	
	/**
	 * Retourne la valeur de l'élément REQUEST_URI de la super global _SERVER
	 * 
	 * @return string
	 */
	public function requestURI(): string
	{
		return empty($_SERVER['CONTEXT_PREFIX']) ? $_SERVER['REQUEST_URI'] : str_replace($_SERVER['CONTEXT_PREFIX'], '', $_SERVER['REQUEST_URI']);
	}

	/**
	 * Retourne l'url du serveur
	 * 
	 * @return string
	 */
	public function host(): string
	{
		return (isset($_SERVER['HTTPS']) && !is_null($_SERVER['HTTPS']) ? 'https' : 'http') . "://" . $_SERVER['SERVER_NAME'] . $_SERVER['CONTEXT_PREFIX'];
	}
	
}
