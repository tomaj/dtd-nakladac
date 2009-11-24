<?php

class Symbol
{
	const TERMISymbol    = 1;
	const NETERMISymbol  = 2;

	protected $type;

	protected $representation;

	public function __construct($representation, $type)
	{
		$this->representation = $representation;
		$this->type           = $type;
	}
	
	public function __toString()
	{
		return $this->representation.'['.$this->type.']';
	}
	
	public function equal($input)
	{
		if (is_string($input))
		{
			$input = new Symbol($input, Symbol::TERMISymbol);
		}
		
		return $this->__toString() == $input->__toString();
	}
	
	public function isEmptySymbol()
	{
		$end = new Symbol(AppConfig::get('empty_symbol'), Symbol::TERMISymbol);
		var_dump($end);
		return $this->equal($end);
	}
}

?>