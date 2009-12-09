<?php

class Kram
{
	/**
	 *	@var Tabulka
	 */
	protected $table;

	protected $input;

	/**
	 *	@var Zasobnik
	 */
	protected $zasobnik;
	
	protected $path = array();
	
	protected $maxIteration = 1000;
	
	protected $result = array();
	
	public function __construct(Tabulka $table)
	{
		$this->table = $table;
	}
	
	public function validateInput($input, Symbol $start)
	{
		$this->input = $input;
		$this->zasobnik = new Zasobnik();
	
		Logger::log("Idem validovat vstup '".implode(' ',$input)."'");
		Logger::log("Zacitaocny symbol:   '$start'");
		
		//echo $this;
		$this->logState();
		
		// vlozime zaciatocny symbol na vrch zasobnika
		$this->zasobnik->push($start);
		
		//echo $this;
		$this->logState();
		
		$counter = 0;
		
		while ($counter < $this->maxIteration)
		{
			// overime ci sme u nespracovali cely vstup
			if (count($this->input) == 0)
			{
				Logger::log('ZPRACOVANE: OK');
				break;
			}
		
			// vybereiem vrch zasobnika
			$top = $this->zasobnik->removeTop();
			//Logger::log('Top: ' . $top);
			
			// vyberieme zaciatok vstupu
			$first = new Symbol($this->input[0], Symbol::TERMINAL);
			//Logger::log('Prvy: ' . $first);
			
			// ak je na vrchu zasobniku TERMINAL tak porovname so vstupom
			// ak sa zhoduju tak ich zrusime
			if ($top->equal($first))
			{
				// vyhodime zaciatok vstupu
				array_shift($this->input);
				//$this->input = substr($this->input, 1);
			}
			// ak sa nezhoduju tak pozrieme do tabulky prechodov
			// obsah vlozime na vrch zasobnika
			else
			{
				try
				{
					// najdeme prechod v tabulke ktoreho hodnota sa vlozit na vrch zasobnika
					$prechod = $this->table->get($top, $first);
					$this->zasobnik->push($prechod);
					$this->path[] = $this->table->getLastPosition();
				}
				catch (TableKeyNotFoundException $tknfe)
				{
					Logger::log('ERROR: Nenasiel sa v tabulke prechod '. $prechod);
					//print_r($top);
					//print_r($first);
					break;
				}
			}
		
			//echo $this;
			$this->logState();
			Logger::log('----------------');
			
			$counter++;
		}
		
		return $this->result;
	}
	
	public function __toString()
	{
		$result = '(' . implode(' ',$this->input) . ',' . $this->zasobnik . ',' . implode(' ', $this->path) . ')' . "\n";
		return $result;
	}
	
	protected function logState()
	{
		$this->result[] = array(
			'input' => implode(' ', $this->input),
			'stack' => $this->zasobnik->__toString(),
			'path' => implode(' ', $this->path),
		);
	}
}

?>