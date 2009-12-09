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
		$result = array();
	
		$terminaly = AppConfig::get('terminaly');
		
		$code = $this->addSpaces($this->code);
		
		$codeLength = strlen($code);
		$buffer = '';
		for ($i = 0; $i < $codeLength; $i++)
		{
			$char = $code[$i];
			//echo $char;
			
			if (in_array($char, array(" ", "\n", "\t", "\r")))
			{
				if ($buffer == '') continue;
				if (in_array($buffer, $terminaly))
				{
					//echo "terminal: " . $buffer . "\n";
					$result[] = $buffer;
				}
				else
				{
					//echo "nezname: " . $buffer . "\n";
					for ($k = 0; $k < strlen($buffer); $k++)
					{
						$result[] = $buffer[$k];
					}
				}
				$buffer = '';
			}
			else
			{
				$buffer .= $code[$i];
			}
		}
		if (in_array($buffer, $terminaly))
		{
			//echo "terminal: " . $buffer . "\n";
			$result[] = $buffer;
		}
		else
		{
			//echo "nezname: " . $buffer . "\n";
			for ($k = 0; $k < strlen($buffer); $k++)
			{
				$result[] = $buffer[$k];
			}
		}
		
		
		
	//print_r($result);
	//die();
	
		return $result;
		//return implode(' ', $result);
		//return $this->code;
	}
	
	protected function addSpaces($code)
	{
		$code = str_replace('>', ' >', $code);
		$code = str_replace(',', ' , ', $code);
		$code = str_replace('|', ' | ', $code);
		$code = str_replace('(', ' ( ', $code);
		$code = str_replace(')', ' ) ', $code);
		// vratime spet jednu zmenu
		$code = str_replace('] >', ']>', $code);
		$code = str_replace('( #PCDATA )', '(#PCDATA)', $code);
		
		$code = str_replace('  ', ' ', $code);
		return $code;
	}
}

?>