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
		$terminaly = AppConfig::get('terminaly');
		
		$codeLength = strlen($this->code);
		$buffer = '';
		for ($i = 0; $i < $codeLength; $i++)
		{
			echo $this->code[$i];
		}
	
	
		die();
		return $this->code;
	}
}

?>