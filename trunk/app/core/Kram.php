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
	
	public function __construct(Tabulka $table)
	{
		$this->table = $table;
	}
	
	public function validateInput($input, Nal $start)
	{
		$this->input = $input;
		$this->zasobnik = new Zasobnik();
	
		Logger::log("Idem validovat vstup '$input'");
		Logger::log("Zacitaocny symbol:   '$start'");
		
		echo $this;
		
		// vlozime zaciatocny symbol na vrch zasobnika
		$this->zasobnik->push($start);
		
		echo $this;
		
		$counter = 0;
		
		while ($counter < $this->maxIteration)
		{
			// overime ci sme u nespracovali cely vstup
			if (strlen($this->input) == 0)
			{
				Logger::log('ZPRACOVANE: OK');
				break;
			}
		
			// vybereiem vrch zasobnika
			$top = $this->zasobnik->removeTop();
			//Logger::log('Top: ' . $top);
			
			// vyberieme zaciatok vstupu
			$first = new Nal($this->input[0], Nal::TERMINAL);
			//Logger::log('Prvy: ' . $first);
			
			// ak je na vrchu zasobniku terminal tak porovname so vstupom
			// ak sa zhoduju tak ich zrusime
			if ($top->equal($first))
			{
				// vyhodime zaciatok vstupu
				$this->input = substr($this->input, 1);
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
				catch (TableKeyNotFoundException $tknfe) {
					Logger::log('ERROR: Nenasiel sa v tabulke prechod');
					break;
				}
			}
		
			echo $this;
			Logger::log('----------------');
			
			$counter++;
		}
	}
	
	public function __toString()
	{
		$result = '(' . $this->input . ',' . $this->zasobnik . ',' . implode(' ', $this->path) . ')' . "\n";
		return $result;		
	}
}

?>