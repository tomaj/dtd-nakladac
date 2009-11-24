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
		
		// ukoncovaci prazdny termiSymbol nebudeme vkladat do zasobnika
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
			$result .= $item;
		}
		return $result;
	}
}

?>