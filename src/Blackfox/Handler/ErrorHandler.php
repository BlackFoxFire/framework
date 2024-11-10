<?php

/**
 * ErrorHandler.php
 * @Auteur: Christophe Dufour
 * 
 */

namespace Blackfox\Handler;

use Blackfox\Application;
use Blackfox\ApplicationComponent;
use Blackfox\View\View;

class ErrorHandler extends ApplicationComponent
{
    /**
	 * Propriétes
	 */
    
     // Instance de la classe
    private static ?self $instance = null;
    // Le nom du fichier de log
    private string $filename;

    /**
     * Initialise l'object et retourne l'instance de classe
     * 
     * @param Application $app
     * Instance de l'application
     * @return ErrorHandler
     * Retourne une instance de classe ErrorHandler
     */
    public static function init(Application $app): self
    {
        if(is_null(self::$instance)) {
            self::$instance = new self($app);
        }
        
        return self::$instance;
    }

    /**
     * Retourne l'instance de cette classe
     * 
     * @return ErrorHandler
     * Retourne une instance de classe ErrorHandler
     * @throws RuntimeException
     * Lance une exception RuntimeException si une variable du tableau des paramètres n'existe pas
     */
    public static function get(): self
    {
        if(is_null(self::$instance)) {
            throw new \RuntimeException("Le gestionnaire doit être initialisé");
        }

        return self::$instance;
    }

    /**
     * Constructeur
     * 
     * @param Application $app
     * Instance de l'application
     */
    private function __construct(Application $app)
    {
        parent::__construct($app);

        $this->filename = $this->app->rootDir() . str_replace("/", DIRECTORY_SEPARATOR, "/log/errors.log");
        $this->setExceptionHandler([$this, 'errorHandler']);
    }

    /**
     * Définit une fonction pour la gestion d'exceptions 
     * 
     * @param ?callable $callback
     * La fonction à appeler quand une exception non attrapée se produit.
     * Ou null pour ré-initialiser ce gestionnaire en son statut initial.
     * @return ?callable
     * Retourne le gestionnaire précédemment défini ou null en cas d'erreur.
     * Si aucun gestionnaire n'a été précédemment défini, null est également retourné. 
     */
    public function setExceptionHandler(?callable $callback): ?callable
    {
        return set_exception_handler($callback);
    }

    /**
     * Intercepte les exeptions lancées
     * 
     * @param Throwable $e
     * Un execption implémentent l'interface Throwable
     * @return void
     * Ne retourne aucune valeur
     */
    public function errorHandler(\Throwable $e): void
    {
        $time = new \DateTime();

        $datas = array(
            'time' => $time->format("[Y-m-d à H:i]"),
            'errorType' => get_class($e),
            'file' => basename($e->getFile()),
            'line' => $e->getLine(),
            'message' => $e->getMessage()
        );

        $this->write($datas);
        $this->display($e);
    }

    /**
     * Ecrit les erreurs détectées dans un fichier
     * 
     * @return void
     * Ne retourne aucune valeur
     */
    protected function write(array $datas)
    {
		$file = new \SplFileObject($this->filename, "a+");

        $string = $datas['time'] . " [" . $datas['errorType'] . "] Fichier: " . 
                $datas['file'] . " (Ligne " . $datas['line'] . ")" . PHP_EOL . 
                "Message: " . $datas['message'] . PHP_EOL;

        $file->fwrite($string);
    }

    /**
     * Affiche une vue d'erreur
     * 
     * @param Throwable $e
     * Un execption implémentent l'interface Throwable
     * @return void
	 * Ne retourne aucune valeur
     */
    protected function display(\Throwable $e): void
    {
        $view = new View($this->app);

        if($this->displayErrors()) {
            $view->setViewFile("detectedError");
            $view->setData('error', $e);
        }
        else {
            $view->setViewFile("unknownError");
        }

        $this->app->httpResponse()->setView($view);
        $this->app->httpResponse()->render();
    }

    /**
	 * Fixe le niveau de rapport d'erreurs PHP
	 * 
	 * @param int $error_level
	 * [Optionnel]
	 * Le niveau de rapport d'erreurs
	 * @return int
	 * Retourne le niveau d'error_reporting, avnt qu'il ne soit changé en error_level
	 */
	public function errorReporting(?int $error_level = null): int
	{
		return error_reporting($error_level);
	}

    /**
	 * Active ou désactive les messages d'erreur
	 * 
	 * @param int $value
     * [Optionnel]
	 * Un entier, 0 pour ne pas afficher les erreurs, 1 pour les afficher
	 * @return int
	 * Retourne la valeur de display_errors
	 */
	public function displayErrors(?int $value = null): int|false
	{
        if(is_null($value)) {
            return ini_get("display_errors");
        }

		if(in_array($value, [0, 1])) {
			ini_set("display_errors", $value);
            return $value;
		}
	}

}
