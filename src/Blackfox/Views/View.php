<?php

/**
 * BackController.php
 * @Auteur: Christophe Dufour
 * 
 * Controleur de base pour tous les controleurs d'une application.
 */

namespace Blackfox\Views;

use Blackfox\Application;
use Blackfox\ApplicationComponent;
use Blackfox\Config\Link;

class View extends ApplicationComponent
{
	/**
	 * Propriétes
	 */
	
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
	 * Instance de l'application
	 * @param string $controller
	 * Controlleur où le traitement a eu lieu
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

	/**
	 * Setters
	 */
	
	/**
	 * Modifie la valeur de $path
	 * 
	 * @param array $path
	 * Dossier où les templates sont à rechercher
	 * @return void
	 * Ne retourne aucune valeur
	 */
	public function setPath(array $path): void
	{
		if(empty($path)) {
			throw new \InvalidArgumentException("Le chemin spécifié est invalide.");
		}
		
		$this->path = $path;
	}
	
	/**
	 * Modifie la valeur $viewFile
	 * 
	 * @param string $viewFile
	 * La vue qu'il faudra afficher
	 * @return void
	 * Ne retourne aucune valeur
	 */
	public function setViewFile(string $viewFile): void
	{
		if(!is_string($viewFile) || empty($viewFile)) {
			throw new \InvalidArgumentException("La vue spécifiée est invalide.");
		}
		
		$this->viewFile = $viewFile;
	}
	
	/**
	 * Ajoute une variable de template
	 * 
	 * @param mixed $data
	 * La variable ou un tableeau de pair variable - valeur à ajouter au template
	 * @param mixed $value
	 * [Optional]
	 * La valeur de la variable à ajouter
	 * @return void
	 * Ne retourne aucune valeur
	 */
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
	
	/**
	 * Méthodes
	 */

	/**
	 * Retourne la vue à afficher
	 * 
	 * @return mixed
	 * Retourne divers types de valeur
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
		
		if (!$fileExists) {
			throw new \RuntimeException("La vue spécifiée n'existe pas.");
		}
		
		$this->data['user'] = $this->app->user();

		if($links = Link::getInstance($this->app())->vars()) {
			$this->setData($links);
		}
		
		$loader = new \Twig\Loader\FilesystemLoader($this->path);
		$twig = new \Twig\Environment($loader, array('cache' => false));
		
		return $twig->render($this->viewFile . $this->ext, $this->data);
	}
}
