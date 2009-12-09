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
		
		$code = str_replace("\r", "\n", $code);
		$code = str_replace("\n\n", "\n", $code);
		
		
		$codeLength = strlen($code);
		$buffer = '';
		$line = 0;
		$word = 0;
		for ($i = 0; $i < $codeLength; $i++)
		{
			$char = $code[$i];
			//echo $char;
			
			if (in_array($char, array(" ", "\n", "\t")))
			{
				
				if ($char == "\n")
				{
					$line++;
					$word = 1;
					
				}
				
				if ($buffer == '') continue;
				
				if (in_array($buffer, $terminaly))
				{
					$result[] = array("w" => $buffer, "line" => $line, "word" => $word);
				}
				else
				{
					for ($k = 0; $k < strlen($buffer); $k++)
					{
						$result[] = array("w" => $buffer[$k], "line" => $line, "word" => $word);
					}
				}
				
				
				$buffer = '';
				$word++;
				
				if ($char == "\n") $word = 1;
				
			}
			else
			{
				$buffer .= $code[$i];
			}
		}
		if (in_array($buffer, $terminaly))
		{
			$result[] = array("w" => $buffer, "line" => $line, "word" => $word);
		}
		else
		{
			for ($k = 0; $k < strlen($buffer); $k++)
			{
				$result[] = array("w" => $buffer[$k], "line" => $line, "word" => $word);;
			}
		}
	
	
		return $result;

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