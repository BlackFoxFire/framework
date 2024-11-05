<?php

/**
 * AbstractConfig.php
 * @Auteur : Christophe Dufour
 * 
 * Cette classe représentant un tableau avec des paramètres de configuration
 */

namespace Blackfox\Config;

use Blackfox\Application;
use Blackfox\ApplicationComponent;
use Blackfox\Exceptions\BadConfigParamException;
use Blackfox\Exceptions\BadConfigOperationException;

abstract class AbstractConfig extends ApplicationComponent implements \ArrayAccess
{

    /**
     * Propriétés
     */

    // Nom du fichier de configuration json
    protected string $filename;
    // Tableau des paramètres de configuration de l'application
    protected array $vars = [];

    /**
     * Constructeur
     * 
     * @param Application $app
     * Instance de l'application
     */
    protected function __construct(Application $app)
    {
        parent::__construct($app);
    }

    /**
     * Getters et Setters
     */

    /**
     * Retourne la valeur de $filename
     * 
     * @return string
     * Le nom du fichier de configuration
     */
    public function filename(): string
    {
        return $this->filename;
    }

    /**
     * Retourne la valeur de $vars
     * 
     * @return array|null
     * Retourne un tableau ou null si le tableau est vide
     */
    public function vars(): array|null
    {
        return !empty($this->vars) ? $this->vars : null;
    }

    /**
     * Méthodes
     */

    /**
     * Lit un fichier de configuration au format json
	 * 
	 * @return bool
     * Retourne true en cas de succès, sinon false
     */
	protected function load(): bool
	{
		if(empty($this->vars)) {
			if(is_file($this->filename)) {
            	$file = new \SplFileObject($this->filename);
            	$content = $file->fread($file->getSize());
            	$this->vars = json_decode($content, true);
				return true;
			}

			return false;
		}

		return true;
	}

	/**
	 * Crée la structure du tableau des paramètres
	 * 
     * @param array $vars
     * [Optional]
     * Tableau contenant les paramètres de configuration
	 * @return void
     * Ne retourne aucune valeur
	 */
	abstract public function create(array $vars = []): void;

    /**
	 * Ecrit un fichier de paramètres au format json
	 * 
	 * @return int|false
     * Retourne le nombre d'octets écrits, ou false si une erreur survient.
	 */
	public function write(): int|false
	{
		$file = new \SplFileObject($this->filename, "w");
		return $file->fwrite(json_encode($this->vars, JSON_PRETTY_PRINT));
	}

    /**
     * Vérifie l'existence d'une variable dans le tableau des paramètres
     * 
     * @param mixed $offset
     * La clé à analyser
     * @return bool
     * Retourne true si la variable existe, sinon false
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->vars[$offset]);
    }

    /**
     * Retourne la valeur d'une variable du tableau des paramètres
     * 
     * @param mixed $offset
     * La clé du tableau dont la valeur est à retourner
     * @return mixed
     * Retourne une valeur ou null si l'index du tableau n'existe pas
     * @throws BadConfigParamExecption
     * Lance une exection BadConfigParamExecption si une variable du tableau des paramètres n'existe pas
     */
    public function offsetGet(mixed $offset): mixed
    {
        if(!$this->offsetExists($offset)) {
            throw new BadConfigParamException("Paramètre de configuration inexistant.");
        }

        return $this->vars[$offset];
    }

    /**
     * Ajoute ou modifie une variable du tableau des paramètres
     * 
     * @param string $offset
     * La clé du tableau à modifier
     * @param mixed $value
     * La valeur à assigner
     * @return void
     * Ne retourne aucune valeur
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->vars[$offset] = $value;
    }

    /**
     * Supprime une variable du tableau des paramètres
     * 
     * @param mixed $offset
     * La clé du tableau à supprimer
     * @return void
     * Ne retourne pas de valeur
     */
    public function offsetUnset(mixed $offset): void
    {
        throw new BadConfigOperationException("Opération interdite! Vous ne pouvez pas effacer un paramètre de configuration.");
    }
    
}
