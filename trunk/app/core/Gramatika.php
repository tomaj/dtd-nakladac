<?php

class Gramatika
{
	protected $TERMINALy;
	
	protected $emptySymbol;
	
	protected $gramatika;

	public function __construct(array $temiSymboly, $emptySymbol, array $gramatika)
	{
		$this->TERMINALy = $temiSymboly;
		$this->emptySymbol = $emptySymbol;
		$this->gramatika = $gramatika;
	}
	
	public function getTable()
	{
		$data = array();
		
		//print_r($this->gramatika);
		
		// vyberieme startovacie neterminaly a pridame k im ich pravidla
		$neterminaly = array();
		foreach ($this->gramatika as $line)
		{
			list($neterminal, $right) = explode(' -> ', $line);
			$neterminaly[$neterminal] = explode(' | ', $right);
		}
		
		//print_r($neterminaly);
		
		// teraz hladame FIRST kazdeho pravidla a to pridavame do tabulky
		$counter = 0;
		foreach ($neterminaly as $neterminal => $pravidla)
		{
			foreach  ($pravidla as $pravidlo)
			{
				$first = $this->getFirst($pravidlo, $neterminaly);
				
				// pridame do tabulky
				$position = $this->getPravidloNumber($pravidlo, $neterminaly);
				$data[] = array(new Symbol($neterminal, Symbol::NETERMINAL), new Symbol($first, Symbol::TERMINAL), $this->temp_SymbolFromString($pravidlo), $position);
				$counter++;
			}
			
		}
		
		print_r($data);
		
		return new Tabulka($data);		

		
		/*
		// staticka tabulka
		$data[] = array(new Symbol('S', Symbol::NETERMINAL), new Symbol('x', Symbol::TERMINAL), $this->temp_SymbolFromString('xSyy'), 2);
		$data[] = array(new Symbol('S', Symbol::NETERMINAL), new Symbol('y', Symbol::TERMINAL), $this->temp_SymbolFromString('yxXx'), 1);
		$data[] = array(new Symbol('X', Symbol::NETERMINAL), new Symbol('x', Symbol::TERMINAL), $this->temp_SymbolFromString('e'), 4);
		$data[] = array(new Symbol('X', Symbol::NETERMINAL), new Symbol('y', Symbol::TERMINAL), $this->temp_SymbolFromString('yYx'), 3);
		$data[] = array(new Symbol('X', Symbol::NETERMINAL), new Symbol('z', Symbol::TERMINAL), $this->temp_SymbolFromString('e'), 4);
		$data[] = array(new Symbol('X', Symbol::NETERMINAL), new Symbol('e', Symbol::TERMINAL), $this->temp_SymbolFromString('e'), 4);
		$data[] = array(new Symbol('Y', Symbol::NETERMINAL), new Symbol('x', Symbol::TERMINAL), $this->temp_SymbolFromString('xxXz'), 5);
		$data[] = array(new Symbol('Y', Symbol::NETERMINAL), new Symbol('y', Symbol::TERMINAL), $this->temp_SymbolFromString('yyYYy'), 6);
		
		return new Tabulka($data);
		*/
	}
	
	protected function getFirst($pravidlo, $neterminaly)
	{
		// toto nemoze byt len tak
		// @TODO Upravit
		return $pravidlo[0];
	}
	
	protected function getFollow($neterminal)
	{
		// @TODO implementova
	}
	
	protected function getPravidloNumber($pravidlo, $neterminaly)
	{
		$counter = 0;
		foreach ($neterminaly as $neterminal => $pravidla)
		{
			foreach ($pravidla as $actualPravidlo)
			{
				$counter++;
				if ($actualPravidlo == $pravidlo) return $counter;
			}
		}
		return -1;
	}
	
	// temporarana metoda zatial koli temporrarne tabulke ktora je napevno
	protected function temp_SymbolFromString($input)
	{
		$result = array();
		for ($i = 0; $i < strlen($input); $i++)
		{
			$char = $input[$i];
			$type = Symbol::NETERMINAL;
			if (in_array($char, $this->TERMINALy)) $type = Symbol::TERMINAL;
			$result[] = new Symbol($char, $type);
		}
		return $result;
	}
}

?>