<?php

/**
 * HTTPResponse.php
 * @Auteur: Christophe Dufour
 * 
 * Classe modélidant une réponce html
 */

namespace Blackfox;

use Blackfox\View\View;

class HTTPResponse extends ApplicationComponent
{
	// La vue à afficher
	protected View $view;
	
	/**
	 * Modifie la valeur de $view
	 * 
	 * @param View $view
	 * 
	 * @return void
	 */
	public function setView(View $view): void
	{
		$this->view = $view;
	}

	/**
	 * Permet d'ajouter un entête html
	 * 
	 * @param string $header
	 * 
	 * @return void
	 */
	public function addHeader(string $header): void
	{
		header($header);
	}
	
	/**
	 * Redirige vers une autre page html
	 * 
	 * @param string $location
	 * 
	 * @return void
	 */
	public function redirect(string $location): void
	{
		header("Location: " . $location);
		exit;
	}
	
	/**
	 * Redigie vers la page d'erreur 404
	 * 
	 * @return void
	 */
	public function redirect404(): void
	{
		$this->view = new View($this->app);
		$this->view->setViewFile("404Error");
		
		$this->addHeader('HTTP/1.0 404 Not Found');
		$this->render();
	}
	
	/**
	 * Affiche la réponce au client
	 * 
	 * @return void
	 */
	public function render(): void
	{
		exit($this->view->render());
	}

	/**
	 * Ajoute un cookie
	 * 
	 * @param string $name
	 * 
	 * @param mixed $value
	 * [Optionnel]
	 * La valeur du cookie
	 * @param array $options
	 * [Optionnel]
	 * Un tableau associatif qui peut avoir comme clés expires, path, domain, secure, httponly et samesite
	 * @return bool
	 */
	public function setCookie(string $name, mixed $value = "", array $options = []): bool
	{
		return setcookie($name, $value, $options);
	}
	
}
