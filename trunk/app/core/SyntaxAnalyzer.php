<?php

class CodeAnalyzer
{
	protected $code;

	public function __construct($code)
	{
		$this->code = $code;
	}
	
	public function getAnalyzedCode()
	{
		return $this->code;
	}
}

?>