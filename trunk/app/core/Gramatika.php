<?php

class Gramatika
{
	protected $terminaly;
	
	protected $emptySymbol;
	
	protected $gramatika;

	protected $firsts = array();
	
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
				
				//echo "getFirst(".$this->getTempPravidlo($pravidlo).", $neterminalSymbol)";
				$first = $this->getFirst($neterminalSymbol, $pravidlo, $neterminaly);
				
				// pridame do tabulky
				$position = $this->getPravidloNumber($pravidlo, $neterminaly);
				
				foreach ($first as $item)
				{
					$k = $item;
					if ($k == NULL) die('visiele null');
					$data[] = array($neterminalSymbol, $k, $pravidlo, $position);
				}
				
				$counter++;

			}
		}

				
		// tmp table
		// len pre jednoduche pomocne vypisanie
		/*
		$tmpTable = array();
		foreach ($data as $row)
		{
			$first = $row[0];
			$second = $row[1];
			$tmpTable[$first->getRepresentation()][$second->getRepresentation()] = $row[3];
		}
		*/
		//print_R($tmpTable);

		return new Tabulka($data);		
	}
	
	protected function getTempPravidlo($pravidlo)
	{
		$result = "";
		
		foreach ($pravidlo as $a)
		{
			if (!$a instanceof Symbol)
			{
				echo "symbol:";
				var_dump($a);
				//print_r(debug_backtrace());
				die("chybaaaa");
			}
			
			$result .= $a->getRepresentation() . " ";
		}
		return $result;
	}
	
	protected function getFirst(Symbol $neterminal, array $pravidlo, array $neterminaly, $level = 0)
	{
		//for ($i=0; $i<$level; $i++) echo " ";
		//echo "getFirst(" . $neterminal->getRepresentation() . ",".$this->getTempPravidlo($pravidlo).")\n";
		
		$rep = $neterminal->getRepresentation();

		$result = array();
		
		$first = $pravidlo[0];
		
		if ($first->isTerminal())
		{
			if ($first->isEmptySymbol())
			{
				$r = $this->getFollow($neterminal, $neterminaly, $level);
				
				$result = array_merge($result, $r);
			}
			else
			{
				$result[] = $first;
			}
		}
		else
		{
			// najdeme pravidlo ktore vedi z toho neterminalu
			foreach ($neterminaly as $nt => $data)
			{
				if ($nt == $first->getRepresentation())
				{
					foreach ($data as $newPravidlo)
					{
						$getFirst = $this->getFirst($first, $newPravidlo, $neterminaly, $level+1);
						
						$result = array_merge($result, $getFirst);
					}
				}
			}
		}
		
		/*
		$tmpResult = array();
		$tmpResult = $this->getTempPravidlo($result);
		if ($tmpResult)
		{
			for ($i=0; $i<$level; $i++) echo " ";
			echo "=={".$tmpResult."}\n";
		}
		*/

		
		if ($level == 0)
		{
			if (!isset($this->firsts[$rep])) $this->firsts[$rep] = array();
			$this->firsts[$rep] = array_merge($this->firsts[$rep], $result);
		}
		
		return $result;
	}
	
	protected $foolows = array();
	
	protected function getFollow(Symbol $neterminal, array $neterminaly, $level = 1)
	{
		$rep = $neterminal->getRepresentation();
		if (isset($this->follows[$rep])) return $this->follows[$rep];
		
	//	for ($i=0; $i<$level; $i++) echo " ";
	//	echo "getFollow(" . $neterminal->getRepresentation() . ")\n";
		
		if ($neterminal->equal(new Symbol('S', Symbol::NETERMINAL))) return array(new Symbol(AppConfig::get('empty_symbol'), Symbol::TERMINAL));
		
		$result = array();
		foreach ($neterminaly as $neterminalyKey => $pravidla)
		{
			if ($neterminalyKey == $neterminal->getRepresentation()) continue;
			
			foreach ($pravidla as $pravidlo)
			{
				for ($i = 0; $i < count($pravidlo); $i++)
				{
					if ($pravidlo[$i]->equal($neterminal))
					{
						$slice = array_slice($pravidlo, $i+1);
						if ($i < count($pravidlo)-1)
						{
							foreach ($neterminaly as $nt => $pp)
							{
								if ($neterminal->getRepresentation() == $nt)
								{
									foreach ($pp as $p)
									{
										$a = $this->getFirst(new Symbol($nt, Symbol::NETERMINAL), $slice, $neterminaly, $level+1);
										$result = array_merge($result, $a);
									}
								}
							}
						}
						else
						{
							if ($neterminalyKey != $rep)
							{
								$a = $this->getFollow(new Symbol($neterminalyKey, Symbol::NETERMINAL), $neterminaly, $level+1);
								$this->follows[$neterminalyKey] = $a;
								$result = array_merge($result, $a);
							}
							
							return $result;
						}
					}
				}
			}
		}
		
		$this->follows[$rep] = $result;
		

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
	
	public function getFirsts()
	{
		$result = array();
		//print_r($this->firsts);
		foreach ($this->firsts as $key => $values)
		{
			foreach ($values as $value)
			{
				$v = $value->getRepresentation();
				if (!isset($result[$key]) || !in_array($v, $result[$key]))
				{
					$result[$key][] = $v;
				}
			}
		}
		return $result;
	}
}

?>