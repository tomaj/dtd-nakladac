<?php

/**
 * Abstraktna definicia ResourceHandleru.
 * Triedy odvodene budu implementovat metodu handle kde budu spracovavat data ziskane z Resourcu.
 * 
 * @author Tomas Majer
 * @package global
 * @see IResource
 * @version $Id: AbstractResourceHandler.php 117 2008-12-14 23:01:01Z majer $
 */
abstract class AbstractResourceHandler
{
	/**
	 * Zadany resource z ktoreho bude brat obsah
	 *
	 * @var IResource
	 */
	protected $resource;
	
	/**
	 * Obsah stiahnuty z resourcu
	 *
	 * @var string
	 */
	protected $data;
	
	/**
	 * Vygenerovany vystup z resourcu.
	 * Moze byt v lubovolnom formate. Podla toho co handle() z toho vyrobi a aky zdroj ide.
	 *
	 * @var mixed
	 */
	protected $output;
	
	/**
	 * Vystupne kodovanie
	 *
	 * @var string
	 */
	protected $outputEncoding = 'utf-8';
	
	/**
	 * Abstraktna metoda kde bude implementovanei spracovanie kontentu zo zadaneho resoursu
	 */
	abstract public function handle();
	
	/**
	 * Konstruktor.
	 * Ako parameter potreubje nejaky konkretny resource z ktoreho si bude brat data
	 *
	 * @param IResource $resource	zdroj z ktoreho berie data - @see IResource
	 */
	public function __construct(IResource $resource)
	{
		$this->resource = $resource;
		$this->data = $this->resource->fetch($this->outputEncoding);
		$this->output = $this->data;
	}
}

?>