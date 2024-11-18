<?php

/**
 * AbstractErrorHandler.php
 * @Auteur: Christophe Dufour
 * 
 */

namespace Blackfox\Handlers;

use Blackfox\Application;
use Blackfox\ApplicationComponent;
use Blackfox\View\View;

abstract class AbstractErrorHandler extends ApplicationComponent
{
    /**
	 * Propriétes
	 */
    
    // Instance de la classe
    protected static array $instance = [];

    /**
     * Initialise l'object et retourne l'instance de classe
     * 
     * @param Application $app
     * Instance de l'application
     * @return AbstractErrorHandler
     * Retourne une instance de AbstractErrorHandler
     */
    public static function init(Application $app): AbstractErrorHandler
    {
        $class = static::class;

        if(!isset(self::$instance[$class])) {
            self::$instance[$class] = new static($app);
        }

        return self::$instance[$class];
    }

    /**
     * Intercepte les exeptions lancées
     * 
     * @param \Throwable $exception
     * Une execption qui implémentente l'interface Throwable
     * @return void
     * Ne retourne aucune valeur
     */
    abstract public function errorHandler(\Throwable $exception): void;

    /**
     * Affiche une vue d'erreur
     * 
     * @param \Throwable $exception
     * Un execption implémentent l'interface Throwable
     * @return void
	 * Ne retourne aucune valeur
     */
    protected function display(\Throwable $exception): void
    {
        $view = new View($this->app);

        if($this->displayErrors()) {
            $view->setViewFile("detectedError");
            $view->setData('error', $exception);
        }
        else {
            $view->setViewFile("unknownError");
        }

        $this->app->httpResponse()->setView($view);
        $this->app->httpResponse()->render();
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
