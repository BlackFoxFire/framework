<?php

/**
 * BackController.php
 * @Auteur: Christophe Dufour
 * 
 * Controleur de base pour tous les controleurs d'une application.
 */

namespace Blackfox\View;

use Blackfox\Application;
use Blackfox\ApplicationComponent;

class View extends ApplicationComponent
{
	// Tableau des chemins ou sont stoqués les templates
	protected array $path = [];
	// Le nom du fichier vue
	protected string $viewFile;
	// L'extention des fichiers vue
	protected string $ext = ".html";
	// Les données à remplacer dans les templates
	protected array $data = [];
	
	/**
	 * Constructeur
	 * 
	 * @param Application $application
	 * 
	 * @param string $controller
	 * [Optionnel]
	 * Controlleur où le traitement a eu lieu
	 */
	public function __construct(Application $application, string $controller = "")
	{
		parent::__construct($application);
		
		$data[] = $this->app->rootDir() . str_replace("/", DIRECTORY_SEPARATOR , "/html/templates/");
		$data[] = $this->app->rootDir() . str_replace("/", DIRECTORY_SEPARATOR , "/html/errors/");
		
		if(!empty($controller)) {
			$data[] = $this->app->appDir() . str_replace("/", DIRECTORY_SEPARATOR, "/Controllers/$controller/views/");
		}
		
		$this->path = $data;
	}
	
	/**
	 * Modifie la valeur $viewFile
	 * 
	 * @param string $viewFile
	 * 
	 * @return void
	 * 
	 * @throws ValueError
	 */
	public function setViewFile(string $viewFile): void
	{
		if(empty($viewFile)) {
			throw new \ValueError("La vue spécifiée est invalide");
		}
		
		$this->viewFile = $viewFile;
	}
	
	/**
	 * Ajoute une variable de template
	 * 
	 * @param mixed $data
	 * 
	 * @param mixed $value
	 * [Optionnel]
	 * La valeur de la variable à ajouter
	 * @return void
	 * 
	 * @throws ValueError
	 * 
	 * @throws InvalidArgumentException
	 */
	public function setData(mixed $data, mixed $value = null): void
	{
		if(is_array($data)) {
			if(empty($data)) {
				throw new \ValueError("Le tableau des variables ne doit pas être nul");
			}
			
			$this->data = array_merge($this->data, $data);
		}
		elseif(is_string($data)) {
			if(empty($data)) {
				throw new \ValueError('Le nom de la variable ne doit pas être une chaine de caractères vide');
			}
			
			$this->data[$data] = $value;
		}
		else {
			throw new \InvalidArgumentException('Le nom de la variable doit être une chaine de caractères valide');
		}
	}

	/**
	 * Retourne la vue à afficher
	 * 
	 * @return mixed
	 * 
	 * @throws RuntimeException
	 */
	public function render(): mixed
	{
		$fileExists = false;
		
		foreach($this->path as $path) {
			if(file_exists($path . $this->viewFile . $this->ext)) {
				$fileExists = true;
				break;
			}
		}
		
		if(!$fileExists) {
			throw new \RuntimeException("La vue spécifiée n'existe pas.");
		}
		
		$this->data['user'] = $this->app->user();

		if($links = $this->app()->link()->vars()) {
			$this->setData($links);
		}
		
		$loader = new \Twig\Loader\FilesystemLoader($this->path);
		$twig = new \Twig\Environment($loader, array('cache' => false));
		
		return $twig->render($this->viewFile . $this->ext, $this->data);
	}
	
}
