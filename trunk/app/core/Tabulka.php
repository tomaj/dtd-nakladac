<?php

class Tabulka
{
	protected $data;
	
	protected $lastPosition = 0;
	
	public function __construct(array $data)
	{
		$this->data = $data;
	}
	
	public function get(Symbol $neterminal, Symbol $terminal)
	{
		foreach ($this->data as $tripple)
		{
			list($neterminalActual, $terminalActual, $result, $position) = $tripple;
			if ($neterminalActual == $neterminal && $terminalActual == $terminal)
			{
				$this->lastPosition = $position;
				return $result;
			}
		}
		
		throw new TableKeyNotFoundException("Nenasla sa hodnota pre kombinaciu [$neterminal][$terminal]");
	}
	
	public function getHTMLTable()
	{
		$tmpTable = array();
		foreach ($this->data as $row)
		{
			$first = $row[0];
			$second = $row[1];
			echo $first,"\n";
			echo $second,"\n";
			$tmpTable[$first->getRepresentation()][$second->getRepresentation()] = $row[3];
		}
		echo "<pre>";
		print_r($tmpTable);
		echo "</pre>";
		
		$content = '<html><body>';
		$content .= '<table BORDER>';
		
		$content .= '<thead><tr><td></td>';
		$terminaly = AppConfig::get('terminaly');
		foreach ($terminaly as $terminal)
		{
			$content .= '<td>' . htmlspecialchars($terminal) . '</td>';
		}
		$content .= '</tr></thead>';
		$content .= '<tbody>';
		
		foreach ($tmpTable as $neterminal => $data)
		{
			$content .= '<tr>';
			$content .= '<td>' . htmlspecialchars($neterminal) . '</td>';
			foreach ($terminaly as $terminal)
			{
				$content .= '<td>';
				$found = false;
				foreach ($tmpTable as $kNeterminal => $kData)
				{
					if ($kNeterminal == $neterminal)
					{
						foreach ($kData as $term => $prechod)
						{
							if ($term == $terminal)
							{
								$found = $prechod;
							}
						}
					}
				}
				if ($found) $content .= $found;
				else $content .= '-';
				$content .= '</td>';
			}

			$content .= '</tr>';
		}
		
		$content .= '</tbody>';
		$content .= '</table>';
		
		
		
		$content .= '</html>';
		//print_r($this->data);
		return $content;
	}
	
	public function getLastPosition()
	{
		return $this->lastPosition;
	}
}

?>