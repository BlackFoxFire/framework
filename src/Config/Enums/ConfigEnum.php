<?php

/**
 * ConfigValue.php
 * @Auteur: Christophe Dufour
 */

namespace Blackfox\Mamba\Config\Enums;

enum ConfigEnum: string
{
    case Database = "database";
    case Global = "global";
    case Backend = "backend";
    case Frontend = "frontend";
}
