<?php

/**
 * DatabaseAPI.php
 * @Auteur: Christophe Dufour
 * 
 * API disponible pour une conneion à une base de données
 */
namespace Blackfox\Factories\Enums;

enum DatabaseAPI: string
{
    case PDO = "PDO";
}
