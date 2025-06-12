<?php

/**
 * ErrorHandler.php
 * @Auteur: Christophe Dufour
 * 
 * Gère les erreurs du frameword et les enregistre
 */

namespace Blackfox\Handlers;

use Blackfox\Application;

class ErrorHandler extends AbstractErrorHandler
{
    // Le nom du fichier de log
    private string $filename;

    /**
     * Constructeur
     * 
     * @param Application $app
     */
    protected function __construct(Application $app)
    {
        parent::__construct($app);

        $this->filename = $this->app->rootDir() . str_replace("/", DIRECTORY_SEPARATOR, "/log/errors.log");
        $this->setExceptionHandler([$this, 'errorHandler']);
    }

    /**
     * Intercepte les exeptions lancées
     * 
     * @param \Throwable $exception
     * 
     * @return void
     */
    public function errorHandler(\Throwable $exception): void
    {
        $time = new \DateTime();

        $datas = array(
            'time' => $time->format("[Y-m-d à H:i]"),
            'errorType' => get_class($exception),
            'file' => basename($exception->getFile()),
            'line' => $exception->getLine(),
            'message' => $exception->getMessage()
        );

        $this->write($datas);
        $this->display($exception);
    }

    /**
     * Ecrit les erreurs détectées dans un fichier
     * 
     * @return void
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
     * INTERDIT l'appel à l'opérateur "clone" 
     */
    private function __clone():void {}
    
}
