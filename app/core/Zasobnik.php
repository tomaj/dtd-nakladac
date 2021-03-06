<?php

class Zasobnik
{
	protected $data = array();

	public function push($items)
	{
		if (is_array($items))
		{
			if (count($items) > 0)
			{
				$items = array_reverse($items);
				
				foreach ($items as $item) $this->push($item);
				return;
			}
			else
			{
				$items = $items[0];
			}
		}
		
		// ukoncovaci prazdny TERMINAL nebudeme vkladat do zasobnika
		if ($items->isEmptySymbol()) return;
		
		Logger::log("Vkladam na vrch zasobnika '$items'");
		
		$this->data[] = $items;
	}
	
	public function removeTop()
	{
		$item = array_pop($this->data);
		return $item;
	}
	
	public function getTop()
	{
		return end($this->data);
	}
	
	public function isEmpty()
	{
		return count($this->data) == 0;
	}
	
	public function __toString()
	{
		$result = '';
		foreach ($this->data as $item)
		{
			$result = $item . $result; // vypisujeme opacne, na lavo je vrch zasobnika
		}
		return $result;
	}
	
	public function getOutput()
	{
		$result = '';
		foreach ($this->data as $item)
		{
			if ($item->isTerminal()) $result = '<span class="term">'.htmlspecialchars($item->getRepresentation()).'</span> ' . $result;
			else $result .= '<span class="neterm">'.htmlspecialchars($item->getRepresentation()).'</span> ' . $result;
		}
		return $result;
	}
}

?>