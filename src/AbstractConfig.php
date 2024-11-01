<?php

/**
 * AbstractConfig.php
 * @Auteur : Christophe Dufour
 * 
 * Cette classe mets en scène une configuration
 */

namespace Blackfox\Mamba;

use Blackfox\Mamba\Enums\ConfigValue;

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
     */
    public function __construct(Application $app)
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
	 * Crée la structure du tableau des paramètres de configuration et l'enregistre dans un fichier json
	 * 
     * @param array $vars
     * [Optional]
     * Tableau contenant les paramètres de configuration
     * 
	 * @return int|false
     * Retourne le nombre d'octets écrits, ou false si une erreur survient
	 */
	abstract public function create(array $vars = []): int|false;

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
     * Vérifie si une clé existe dans le tableau des paramètres
     * 
     * @param string $key
     * La clé à analyser
     * @param ConfigValue $index
     * [Optional]
     * Sous tableau où l'analyse doit se faire
     * 
     * @return bool
     * Retourne true en cas de succès, sinon false
     */
    public function exists(string $key, ConfigValue $index = ConfigValue::Frontend): bool
    {
        return array_key_exists($key, $this->vars[$index->value]);
    }

    /**
	 * Retourne une valeur du tableau des paramètres
	 * 
	 * @param string $key
     * La clé du tableau dont la valeur est à retourner
	 * @return mixed
     * Retourne une valeur
	 */
    abstract public function get(string $key): mixed;

    /**
     * Modifie une valeur du tableau des paramètres
     * 
     * @param string $key
     * La vlé du tableau à modifier
     * @param mixed $value
     * La valeur à assigner
     * @return void
     * Ne retourne aucune valeur
     */
    abstract public function set(string $key, mixed $value): void;

	/**
     * Vérifie si une clé existe dans le tableau des paramètres
     * 
     * @param mixed $offset
     * La clé à analyser
     * @return bool
     * Cette fonction retourne true en cas de succès ou false si une erreur survient.
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->vars[$offset]);
    }

	/**
     * Retourne la valeur d'un index dans le tableau des paramètres
     * 
     * @param mixed $offset
     * La clé du tableau dont la valeur est à retourner
     * @return mixed
     * Retourne une valeur ou null si l'index du tableau n'existe pas.
     */
    public function offsetGet(mixed $offset): mixed
    {
       return $this->offsetExists($offset) ? $this->vars[$offset] : null;
    }

	/**
     * Ajoute ou modifie la valeur d'un index dans le tableau des paramètres
     * 
     * @param mixed $offset
     * La clé du tableau dont la valeur est à modifier
     * @param mixed $value
     * La valeur à assigner
     * @return void
     * Ne retourne pas de valeur
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->vars[$offset] = $value;
    }

	/**
     * Supprime une variable dans le tableau des paramètres
     * 
     * @param mixed $offset
     * La clé du tableau qui est à supprimer
     * @return void
     * Ne retourne pas de valeur
     */
    public function offsetUnset(mixed $offset): void
    {
        //throw new \ErrorException("");
        // unset($this->vars[$offset]);
    }

}
