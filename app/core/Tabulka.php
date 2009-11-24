<?php

class Tabulka
{
	protected $data;
	
	protected $lastPosition = 0;
	
	public function __construct(array $data)
	{
		$this->data = $data;
	}
	
	public function get(Symbol $neterminal, Symbol $terminal)
	{
		foreach ($this->data as $tripple)
		{
			list($neterminalActual, $terminalActual, $result, $position) = $tripple;
			if ($neterminalActual == $neterminal && $terminalActual == $terminal)
			{
				$this->lastPosition = $position;
				return $result;
			}
		}
		
		throw new TableKeyNotFoundException("Nenasla sa hodnota pre kombinaciu [$neterminal][$terminal]");
	}
	
	public function getLastPosition()
	{
		return $this->lastPosition;
	}
}

?>