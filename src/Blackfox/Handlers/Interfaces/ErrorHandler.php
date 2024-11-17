<?php

/**
 * ErrorHandler.php
 * @Auteur: Christophe Dufour
 * 
 */

namespace Blackfox\Handlers\Interfaces;

interface ErrorHandler
{
     /**
     * Intercepte les exeptions lancées
     * 
     * @param \Throwable $exception
     * Un execption implémentent l'interface Throwable
     * @return void
     * Ne retourne aucune valeur
     */
    public function errorHandler(\Throwable $exception): void;
}
