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
	/**
	 * Propriétes
	 */
	
	// La vue à afficher
	protected View $view;
	
	/**
	 * Setters
	 */
	
	/**
	 * Modifie la valeur de $view
	 * 
	 * @param View $view
	 * Un object View représentant une vue à afficher
	 * @return void
	 * Ne retourne aucune valeur
	 */
	public function setView(View $view): void
	{
		$this->view = $view;
	}
	
	/**
	 * Méthodes
	 */
	
	/**
	 * Permet d'ajouter un entête html
	 * 
	 * @param string $header
	 * Une chaine de caractère représentant une entête html
	 * @return void
	 * Ne retourne aucune valeur
	 */
	public function addHeader(string $header): void
	{
		header($header);
	}
	
	/**
	 * Redirige vers une autre page html
	 * 
	 * @param string $location
	 * Le lien vers une page html
	 * @return void
	 * Ne retourne aucune valeur
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
	 * Ne retourne aucune valeur
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
	 * Ne retourne aucune valeur
	 */
	public function render(): void
	{
		exit($this->view->render());
	}

	/**
	 * Ajoute un cookie
	 * 
	 * @param string $name
	 * Le nom du cookie
	 * @param mixed $value
	 * [Optionnel]
	 * La valeur du cookie
	 * @param array $options
	 * [Optionnel]
	 * Un tableau associatif qui peut avoir comme clés expires, path, domain, secure, httponly et samesite
	 * @return bool
	 * Retourne true si le cookie réussi, sinon false
	 */
	public function setCookie(string $name, mixed $value = "", array $options = []): bool
	{
		return setcookie($name, $value, $options);
	}
	
}
