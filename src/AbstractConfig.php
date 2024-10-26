<?php

/**
 * AbstractConfig.php
 * @Auteur : Christophe Dufour
 * 
 * Cette classe mets en scène une configuration
 */

namespace Blackfox\Mamba;

abstract class AbstractConfig extends ApplicationComponent
{

    /**
     * Propriétés
     */

    // Nom du fihcier de configuration
    protected string $filename;
    // Tableau des paramètres de l'application
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
     */
    public function filename(): string
    {
        return $this->filename;
    }

    /**
     * Lit un fichier de configuration json
	 * 
	 * @return bool
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
	 * Retourne le nombre d'octets écrits, ou false si une erreur survient.
	 * 
	 *  @return int|false
	 */
	abstract public function create(array $vars = []): int|false;

    /**
	 * Ecrit un fichier de configuration json
	 * Retourne le nombre d'octets écrits, ou false si une erreur survient.
	 * 
	 * @return int|false
	 */
	public function write(): int|false
	{
		$file = new \SplFileObject($this->filename, "w");
		return $file->fwrite(json_encode($this->vars, JSON_PRETTY_PRINT));
	}

    /**
	 * Retourne une valeur du tableau de configuration ou le tableau en entier si celui-ci n'est pas vide
	 * 
	 * @param string $key = null, index du tableau dont la valeur est à retourner
	 * 
	 * @return mixed
	 */
	public function get(string $key = null): mixed
	{
		if($key === null) {
			if(!empty($this->vars)) {
				return $this->vars;
			}
		}
		else {
			if(isset($this->vars[$key])) {
				return $this->vars[$key];
			}
		}
		
		return null;
	}

	/**
	 * 
	 * 
	 * @param string $key = null, index du tableau dont la valeur est à retourner
	 * 
	 * @return bool
	 */
	public function set(string $key, mixed $value): bool
	{
		if(array_key_exists($key, $this->vars)) {
			$this->vars[$key] = $value;
			return true;
		}

		return false;
	}

}
