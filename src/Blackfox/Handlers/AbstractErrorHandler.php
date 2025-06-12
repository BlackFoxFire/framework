<?php

/**
 * AbstractErrorHandler.php
 * @Auteur: Christophe Dufour
 * 
 * Classe de base pour les gestionnaires d'erreurs
 */

namespace Blackfox\Handlers;

use Blackfox\Application;
use Blackfox\ApplicationComponent;
use Blackfox\View\View;

abstract class AbstractErrorHandler extends ApplicationComponent
{
    // Instance de la classe
    protected static array $instance = [];

    /**
     * Crée et/ou retourne l'instance d'une classe qui hérite de AbstractErrorHandler
     * 
     * @param Application $app
     * 
     * @return AbstractErrorHandler
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
     * 
     * @return void
     */
    abstract public function errorHandler(\Throwable $exception): void;

    /**
     * Affiche une vue d'erreur
     * 
     * @param \Throwable $exception
     * 
     * @return void
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
     * 
     * @return ?callable
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
	 */
	public function displayErrors(?int $value = null): int|false
	{
		if(in_array($value, [0, 1])) {
			ini_set("display_errors", $value);
            return $value;
		}

        return ini_get("display_errors");
	}

    /**
     * Interdit l'utilisation de unserialize
     * 
     * @return void
     * 
     * @throws \Exception
     */
    final public function __wakeup(): void
    {
        throw new \Exception("Impossible de désérialiser un singleton");
    }

}
