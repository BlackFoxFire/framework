<?php

/**
 * AreaConfig.php
 * @Auteur: Christophe Dufour
 * 
 * Catégorie du fichier de configuration
 */

namespace Blackfox\Config\Enums;

enum AreaConfig: string
{
    case Database = "database";
    case Global = "global";
    case Backend = "backend";
    case Frontend = "frontend";
}
