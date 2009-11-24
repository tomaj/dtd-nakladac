<?php

class Gramatika
{
	protected $terminaly;
	
	protected $koniec;
	
	protected $gramatika;

	public function __construct(array $teminaly, $koniec, array $gramatika)
	{
		$this->terminaly = $teminaly;
		$this->koniec = $koniec;
		$this->gramatika = $gramatika;
	}
	
	public function getTable()
	{
		$data = array();
		
		$data[] = array(new Nal('S', Nal::NETERMINAL), new Nal('x', Nal::TERMINAL), $this->temp_NalFromString('xSyy'), 2);
		$data[] = array(new Nal('S', Nal::NETERMINAL), new Nal('y', Nal::TERMINAL), $this->temp_NalFromString('yxXx'), 1);
		$data[] = array(new Nal('X', Nal::NETERMINAL), new Nal('x', Nal::TERMINAL), $this->temp_NalFromString('e'), 4);
		$data[] = array(new Nal('X', Nal::NETERMINAL), new Nal('y', Nal::TERMINAL), $this->temp_NalFromString('yYx'), 3);
		$data[] = array(new Nal('X', Nal::NETERMINAL), new Nal('z', Nal::TERMINAL), $this->temp_NalFromString('e'), 4);
		$data[] = array(new Nal('X', Nal::NETERMINAL), new Nal('e', Nal::TERMINAL), $this->temp_NalFromString('e'), 4);
		$data[] = array(new Nal('Y', Nal::NETERMINAL), new Nal('x', Nal::TERMINAL), $this->temp_NalFromString('xxXz'), 5);
		$data[] = array(new Nal('Y', Nal::NETERMINAL), new Nal('y', Nal::TERMINAL), $this->temp_NalFromString('yyYYy'), 6);
		
		return new Tabulka($data);
	}
	
	// temporarana metoda zatial koli temporrarne tabulke ktora je napevno
	protected function temp_NalFromString($input)
	{
		$result = array();
		for ($i = 0; $i < strlen($input); $i++)
		{
			$char = $input[$i];
			$type = Nal::NETERMINAL;
			if (in_array($char, $this->terminaly)) $type = Nal::TERMINAL;
			$result[] = new Nal($char, $type);
		}
		return $result;
	}
}

?>