<?php

class ConsoleLogger implements ILogger
{
	public function log($message)
	{
		echo $message . "\n";
	}
}

?>