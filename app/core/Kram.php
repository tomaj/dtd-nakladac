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
	
	protected $error = false;
	
	public function __construct(Tabulka $table)
	{
		$this->table = $table;
	}
	
	public function validateInput($input, Symbol $start)
	{
		$this->input = $input;
		$this->zasobnik = new Zasobnik();
		
		//var_dump($this->input);
	
		//Logger::log("Idem validovat vstup '".implode(' ',$input)."'");
		//Logger::log("Zacitaocny symbol:   '$start'");
		
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
			$first = new Symbol($this->input[0]['w'], Symbol::TERMINAL);
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
					//Logger::log('ERROR: Nenasiel sa v tabulke prechod '. $prechod);
					//print_r($top);
					//print_r($first);
					
					$this->error = $this->input[0];		
					return false;

				}
			}
		
			//echo $this;
			$this->logState();
			Logger::log('----------------');
			
			$counter++;
		}
		
		return true;
	}
	
	public function getResult()
	{
		return $this->result;
	}
	
	public function __toString()
	{
		$r = array();
		foreach ($this->input as $a) $r[] = $a['w'];
		$result = '(' . implode(' ', $r) . ',' . $this->zasobnik . ',' . implode(' ', $this->path) . ')' . "\n";
		return $result;
	}
	
	protected function logState()
	{
		$r = array();
		foreach ($this->input as $a) $r[] = $a['w'];
		
		$this->result[] = array(
			'input' => implode(' ', $r),
			'stack' => $this->zasobnik->getOutput(),
			'path' => implode(' ', $this->path),
		);
	}
	
	public function getError()
	{
		return $this->error;
	}
}

?>