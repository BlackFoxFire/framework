<?php

/*
*
* BackController.php
* @Auteur : Christophe Dufour
*
* Controleur de base pour tous les controleurs d'une application.
*
*/

namespace Blackfox\Mamba;

class View extends ApplicationComponent
{
	/*
		Les attributs
		-------------
	*/
	
	// Tableau des chemins ou sont stoqués les templates
	protected array $path = [];
	// Le nom du fichier vue
	protected string $viewFile;
	// L'extention des fichiers vue
	protected string $ext = ".html";
	// Les données à remplacer dans les templates
	protected array $data = [];
	
	/*
		Constructeur
		------------
	*/
	public function __construct(Application $application, string $controller = null)
	{
		parent::__construct($application);
		
		$data[] = $this->app->rootDir() . DIRECTORY_SEPARATOR . "html" . DIRECTORY_SEPARATOR . "templates" . DIRECTORY_SEPARATOR;
		
		if(!empty($controller)) {
			if(is_string($controller)) {
				$data[] = $this->app->appDir() . "App" . DIRECTORY_SEPARATOR . $this->app->name() . DIRECTORY_SEPARATOR . "Modules" . DIRECTORY_SEPARATOR . 
							$controller . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR;
			}
			else {
				throw new \InvalidArgumentException("Le controller doit être une chaine de caractères valide");
			}
		}
		
		$data[] = $this->app->rootDir() . DIRECTORY_SEPARATOR . "html" . DIRECTORY_SEPARATOR . "errors" . DIRECTORY_SEPARATOR;
		$this->path = $data;
	}
	
	/*
		Les setters
		-----------
	*/
	
	// Modifie la valeur de l'attribut $path
	public function setPath(array $path): void
	{
		if(empty($path)) {
			throw new \InvalidArgumentException("Le chemin spécifié est invalide.");
		}
		
		$this->path = $path;
	}
	
	// Modifie la valeur de l'attribut $viewFile
	public function setViewFile(string $viewFile): void
	{
		if(!is_string($viewFile) || empty($viewFile)) {
			throw new \InvalidArgumentException("La vue spécifiée est invalide.");
		}
		
		$this->viewFile = $viewFile;
	}
	
	// Ajoute un variable de page
	public function setData(mixed $data, mixed $value = null): void
	{
		if(is_array($data)) {
			if(empty($data)) {
				throw new \InvalidArgumentException("Le tableau des variables doit être non nul.");
			}
			
			$this->data = array_merge($this->data, $data);
		}
		else {
			if(!is_string($data) || empty($data)) {
				throw new \InvalidArgumentException(
									'Le nom de la variable doit être une chaine de caractères non nulle');
			}
			
			$this->data[$data] = $value;
		}
	}
	
	/*
		Les méthodes
		------------
	*/

	// Retourne la vue à afficher
	public function render(): mixed
	{
		$fileExists = false;
		
		foreach($this->path as $path) {
			if(file_exists($path . $this->viewFile . $this->ext)) {
				$fileExists = true;
				break;
			}
		}
		
		if (!$fileExists) {
			throw new \RuntimeException("La vue spécifiée n'existe pas.");
		}
		
		$this->data['user'] = $this->app->user();

		if($this->app->link()->get()) {
			$this->setData($this->app->link()->get());
		}
		
		$loader = new \Twig\Loader\FilesystemLoader($this->path);
		$twig = new \Twig\Environment($loader, array('cache' => false));
		
		return $twig->render($this->viewFile . $this->ext, $this->data);
	}
}
