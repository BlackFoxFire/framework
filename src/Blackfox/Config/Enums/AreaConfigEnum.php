<?php

/**
 * AreaConfigEnum.php
 * @Auteur: Christophe Dufour
 */

namespace Blackfox\Config\Enums;

enum AreaConfigEnum: string
{
    case Database = "database";
    case Global = "global";
    case Backend = "backend";
    case Frontend = "frontend";
}
