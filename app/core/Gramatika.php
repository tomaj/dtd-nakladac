<?php

class Gramatika
{
	protected $terminaly;
	
	protected $emptySymbol;
	
	protected $gramatika;

	public function __construct(array $temiSymboly, $emptySymbol, array $gramatika)
	{
		$this->terminaly = $temiSymboly;
		$this->emptySymbol = $emptySymbol;
		$this->gramatika = $gramatika;
	}
	
	public function getTable()
	{
		$data = array();
		
	
		// vyberieme startovacie neterminaly a pridame k im ich pravidla
		$neterminaly = array();
		foreach ($this->gramatika as $line)
		{
			list($neterminal, $right) = explode(' -> ', $line);
			$rightItems = explode(' | ', $right);
			foreach ($rightItems as $item)
			{
				$neterminaly[$neterminal][] = $this->temp_SymbolFromString($item);
			}
		}
		
		
		
		// teraz hladame FIRST kazdeho pravidla a to pridavame do tabulky
		$counter = 0;
		foreach ($neterminaly as $neterminal => $pravidla)
		{
			foreach  ($pravidla as $pravidlo)
			{
				// vytvorime objekt symbol lebo v poli je to ako kluc len 'string'
				$neterminalSymbol = new Symbol($neterminal, Symbol::NETERMINAL);
				
				echo "getFirst(".$this->getTempPravidlo($pravidlo).", $neterminalSymbol)";
				$first = $this->getFirst($pravidlo, $neterminaly, $neterminalSymbol);
				
				
				//print_r($first);
				//echo "---------\n";
				
				// pridame do tabulky
				$position = $this->getPravidloNumber($pravidlo, $neterminaly);
				
				if (!is_array($first)) $first = array($first);
				
				//print_r($first);
				echo "=".$this->getTempPravidlo($first);
				echo "\n";
				
				
				foreach ($first as $item)
				{
					$data[] = array($neterminalSymbol, $item, $pravidlo, $position);
				}
				
				$counter++;
			}
		}
		
		print_r($data);
		
		die();
		
		
		// tmp table
		// len pre jednoduche pomocne vypisanie
		$tmpTable = array();
		foreach ($data as $row)
		{
			$first = $row[0];
			$second = $row[1];
			$tmpTable[$first->getRepresentation()][$second->getRepresentation()] = $row[3];
		}
		
		//print_R($tmpTable);

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
	
	protected function getTempPravidlo($pravidlo)
	{
		$result = "";
		foreach ($pravidlo as $a)
		{
			$result .= $a->getRepresentation() . " ";
		}
		return $result;
	}
	
	protected function getFirst($pravidlo, $neterminaly, $neterminal)
	{
		if (!is_array($pravidlo) || !$pravidlo[0])
		{
			throw new Exception('getFirst pre : ' . print_R($pravidlo, true) . ' sa neda spravit. nie je to pole, gramatika asi nie je LL1 ' . "\n");
		}
		if ($pravidlo[0]->isEmptySymbol())
		{
			return $this->getFollow($neterminaly, $neterminal);
		}
		if (!$pravidlo[0]->isTerminal())
		{
			return $this0<
		}
		return $pravidlo[0];
	}
	
	protected function getFollow($neterminaly, $neterminal)
	{
		$result = array();
		
//		echo "neterminal->$neterminal\n";
		
		foreach ($neterminaly as $neterminalKey => $pravidla)
		{
			foreach ($pravidla as $pravidlo)
			{
				for ($i = 0; $i < count($pravidlo); $i++)
				{
					//echo "{$pravidlo[$i]} == {$neterminal} \n";
				
					if ($pravidlo[$i]->equal($neterminal))
					{
						$slice = array_slice($pravidlo, $i+1);
						if (!$slice)
						{
							foreach ($neterminaly as $nt => $pp)
							{
								if ($neterminal == $nt)
								{
									foreach ($pp as $p)
									{
										$a = $this->getFirst($p, $neterminaly, $neterminalKey);
										$result[] = $a;
									}
								}
							}
						}
						//if (!$slice) throw new Exception("getFollow pre $neterminal sa neda spravit");
						$result[] = $this->getFirst($slice, $neterminaly, $neterminal);
					}
				}
			}
		}

		//print_r($result);
		
		//die();
		
		return $result;
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
		/*
		for ($i = 0; $i < strlen($input); $i++)
		{
			$char = $input[$i];
			$type = Symbol::NETERMINAL;
			if (in_array($char, $this->TERMINALy)) $type = Symbol::TERMINAL;
			$result[] = new Symbol($char, $type);
		}
		*/
		$parts = explode(' ', $input);
		foreach ($parts as $part)
		{
			$type = Symbol::NETERMINAL;
			if ($part[0] == "'" && $part[strlen($part)-1] == "'")
			{
				$type = Symbol::TERMINAL;
				$part = substr($part, 1, strlen($part)-2);
			}
			
			$result[] = new Symbol($part, $type);
		}
		return $result;
		
	}
}

?>