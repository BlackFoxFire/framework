<?php

/**
 * ConfigValue.php
 * @Auteur: Christophe Dufour
 */

namespace Blackfox\Mamba\Enums;

enum ConfigValue: string
{
    case Database = "database";
    case Global = "global";
    case Backend = "backend";
    case Frontend = "frontend";
}
