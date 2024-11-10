<?php

/**
 * Launcher.php
 * @Auteur : Christophe Dufour
 * 
 * Gére les événements de Composer
 */

namespace Blackfox\Scripts;

use Composer\Script\Event;

class Launcher
{
    /**
     * Gére l'événement post-create-project-cmd de Composer
     * 
     * @param Event $event
     * Un événement de Composer
     * @return void
     * Ne retourne aucune valeur
     */
    public static function postCreateProject(Event $event): void
    {
        $composer = $event->getComposer();
        $vendorDir = $composer->getConfig()->get('vendor-dir');
        $rootDir = dirname($vendorDir);
        
        CreateProject::createFolders($rootDir);
    }

}
