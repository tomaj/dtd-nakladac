<?php

class Gramatika
{
	protected $termiSymboly;
	
	protected $koniec;
	
	protected $gramatika;

	public function __construct(array $temiSymboly, $koniec, array $gramatika)
	{
		$this->termiSymboly = $temiSymboly;
		$this->koniec = $koniec;
		$this->gramatika = $gramatika;
	}
	
	public function getTable()
	{
		$data = array();
		
		$data[] = array(new Symbol('S', Symbol::NETERMISymbol), new Symbol('x', Symbol::TERMISymbol), $this->temp_SymbolFromString('xSyy'), 2);
		$data[] = array(new Symbol('S', Symbol::NETERMISymbol), new Symbol('y', Symbol::TERMISymbol), $this->temp_SymbolFromString('yxXx'), 1);
		$data[] = array(new Symbol('X', Symbol::NETERMISymbol), new Symbol('x', Symbol::TERMISymbol), $this->temp_SymbolFromString('e'), 4);
		$data[] = array(new Symbol('X', Symbol::NETERMISymbol), new Symbol('y', Symbol::TERMISymbol), $this->temp_SymbolFromString('yYx'), 3);
		$data[] = array(new Symbol('X', Symbol::NETERMISymbol), new Symbol('z', Symbol::TERMISymbol), $this->temp_SymbolFromString('e'), 4);
		$data[] = array(new Symbol('X', Symbol::NETERMISymbol), new Symbol('e', Symbol::TERMISymbol), $this->temp_SymbolFromString('e'), 4);
		$data[] = array(new Symbol('Y', Symbol::NETERMISymbol), new Symbol('x', Symbol::TERMISymbol), $this->temp_SymbolFromString('xxXz'), 5);
		$data[] = array(new Symbol('Y', Symbol::NETERMISymbol), new Symbol('y', Symbol::TERMISymbol), $this->temp_SymbolFromString('yyYYy'), 6);
		
		return new Tabulka($data);
	}
	
	// temporarana metoda zatial koli temporrarne tabulke ktora je napevno
	protected function temp_SymbolFromString($input)
	{
		$result = array();
		for ($i = 0; $i < strlen($input); $i++)
		{
			$char = $input[$i];
			$type = Symbol::NETERMISymbol;
			if (in_array($char, $this->termiSymboly)) $type = Symbol::TERMISymbol;
			$result[] = new Symbol($char, $type);
		}
		return $result;
	}
}

?>