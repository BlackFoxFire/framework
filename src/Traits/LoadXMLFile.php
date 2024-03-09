<?php

/*
*
* LoadXMLFile.php
* @Auteur : Christophe Dufour
*
* Lit un fichier de configuration XML
*
*/

namespace Mamba\Traits;

trait LoadXMLFile
{
    /*
		Les méthodes
		------------
	*/

    // Lit un fichier de configuration XML et le retourne sous forme de tableau
    protected static function loadXmlFile(string $file): array
    {
        $xml = new \DOMDocument;
        $xml->load($file);
        
        $elements = $xml->getElementsByTagName("define");
        
        foreach($elements as $element) {
            $vars[$element->getAttribute("var")] = $element->getAttribute("value");
        }

        return $vars;
    }
}
