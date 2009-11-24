<?php

/**
 * Vynimka pre chybne formaty vstupov
 *
 */
class InvalidResourceFormat extends Exception {}

/**
 * Interfejs pre vsetky zdroje.
 * Kazdy zdroj musi implementovat metodu fetch() pre ziskanie dat zo zdroja.
 *
 * @author Tomas Majer <majer@monogram.sk>
 * @package global
 * @version $Id: IResource.php 117 2008-12-14 23:01:01Z majer $
 */
interface IResource
{
	/**
	 * Metoda pre stiahnute obsahu zdroja.
	 *
	 * @param string $outputEncoding kodovanie v ktorom chceme vystup 
	 */
	public function fetch($outputEncoding = 'utf-8');
}

?>