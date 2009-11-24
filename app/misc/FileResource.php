<?php

/**
 * Implementuje subor ako "Resource" pre ResourceHandleri (@see AbstractResourceHandler).
 * Umoznuje precitat subor a prekodovat ho do potrebneho encodignu.
 * 
 * @author Tomas Majer <majer@monogram.sk>
 * @package global
 * @version $Id: FileResource.php 117 2008-12-14 23:01:01Z majer $
 */
class FileResource implements IResource
{
	/**
	 * Cesta k zdrojovemu suboru
	 *
	 * @var string
	 */
	protected $filePath;
	
	/**
	 * Kodovanie vstupu.
	 * Umoznuje zadavat kodovanie ktore sa daju pouzivat pri {@link PHP_MANUAL#iconv} 
	 *
	 * @var string
	 */
	protected $encoding = 'utf-8';
	
	/**
	 * Konstruktor pre resource.
	 *
	 * @param string $filePath	cesta k suboru
	 * @param string $encoding	kodovanie vstupu {@link PHP_MANUAL#iconv}
	 */
	public function __construct($filePath, $encoding = 'utf-8')
	{
		$this->filePath = $filePath;
		$this->encoding = $encoding;
	}
	
	/**
	 * Skontroluje a precita subor ktory nakoniec v pripade potreby prekonvertuje do pozadovaneho kodovania.
	 *
	 * @param string $outputEncoding vystupne kodovnie {@link PHP_MANUAL#iconv}
	 * @return string	vysledny obsah suboru v pozadovanom kodovani
	 * @throws FileNotFoundException	v pripade ze zdrojovy subor neexistuje
	 * @throws IOException				v pripade ze subor sa neda citat (nema prava)
	 * @throws ExtensionNotLoaded		v pripade ze modul 'iconv' nie je naloadovany
	 */
	public function fetch($outputEncoding = 'utf-8')
	{
		if (!file_exists($this->filePath)) throw new FileNotFoundException("File '{$this->filePath}' not found"); 
		if (!is_readable($this->filePath)) throw new IOException("File '{$this->filePath}' is not readable");
		
		$fileDescriptor = fopen($this->filePath, 'r');
		$content = fread($fileDescriptor, filesize($this->filePath));
		fclose($fileDescriptor);
		
		// prekoduju do vystupneho encodingu
		if ($outputEncoding != $this->encoding)
		{
			if (!extension_loaded('iconv')) throw new ExtensionNotLoaded("Extension 'iconv' not loaded");
			$content = iconv($this->encoding, $outputEncoding, $content);
		}
		
		return $content;
	}
}

?>