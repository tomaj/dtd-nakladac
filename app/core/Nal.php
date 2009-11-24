<?php

class Nal
{
	const TERMINAL    = 1;
	const NETERMINAL  = 2;

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
			$input = new Nal($input);
		}
		
		return $this->__toString() == $input->__toString();
	}
	
	public function isEmptyTerminal()
	{
		$end = new Nal(AppConfig::get('koniec'), Nal::TERMINAL);
		return $this->equal($end);
	}
}

?>