<?php

class FileLogger implements ILogger
{
	protected $filePath;

	public function __construct($file)
	{
		$this->filePath = $file;
	}

	public function log($message)
	{
		$f = fopen($this->filePath);
		fwrite($f, $message . "\n");
		fclose($f);
	}
}


?>