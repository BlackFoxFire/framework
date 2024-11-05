<?php

/*
*
* HTTPResponse.php
* @Auteur : Christophe Dufour
*
* Classe modélidant une réponce html.
*
*/

namespace Blackfox\Mamba;

use Blackfox\Mamba\Views\View;

class HTTPResponse extends ApplicationComponent
{
	/*
		Les attributs
		-------------
	*/
	
	// La vue à afficher
	protected View $view;
	
	/*
		Les setters
		-----------
	*/
	
	// Modifie la valeur de l'attribut $view
	public function setView(View $view): void
	{
		$this->view = $view;
	}
	
	/*
		Les méthodes
		------------
	*/
	
	// Permet d'ajouter un entête html
	public function addHeader(string $header): void
	{
		header($header);
	}
	
	// Redirige vers une autre page html
	public function redirect(string $location): void
	{
		header("Location: " . $location);
		exit;
	}
	
	// Redigie vers la page d'erreur 404
	public function redirect404(): void
	{
		$this->view = new View($this->app);
		$this->view->setViewFile("404");
		
		$this->addHeader('HTTP/1.0 404 Not Found');
		$this->render();
	}
	
	// Affiche la réponce au client
	public function render(): void
	{
		exit($this->view->render());
	}
	
	// Ajoute un cookie
	public function setCookie(string $name, mixed $value = "", $options = []): bool
	{
		return setcookie($name, $value, $options);
	}
}
