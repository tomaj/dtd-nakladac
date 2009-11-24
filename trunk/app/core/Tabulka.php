<?php

class Tabulka
{
	protected $data;
	
	protected $lastPosition = 0;
	
	public function __construct(array $data)
	{
		$this->data = $data;
	}
	
	public function get(Symbol $netermiSymbol, Symbol $termiSymbol)
	{
		foreach ($this->data as $tripple)
		{
			list($netermiSymbolActual, $termiSymbolActual, $result, $position) = $tripple;
			if ($netermiSymbolActual == $netermiSymbol && $termiSymbolActual == $termiSymbol)
			{
				$this->lastPosition = $position;
				return $result;
			}
		}
		
		throw new TableKeyNotFoundException("Nenasla sa hodnota pre kombinaciu [$netermiSymbol][$termiSymbol]");
	}
	
	public function getLastPosition()
	{
		return $this->lastPosition;
	}
}

?>