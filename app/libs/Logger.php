<?php

class Logger
{
	protected static $logger;

	protected $loggers = array();
	
	public static function getInstance()
	{
		if (self::$logger == null)
		{
			self::$logger = new Logger();
		}
		return self::$logger;
	}
	
	public static function log($message)
	{
		$logger = self::getInstance();
		if (!is_string($message)) $message = '' . $message;
		$logger->pushLog($message);
	}
	
	public function pushLog($message)
	{
		foreach ($this->loggers as $logger) $logger->log($message);
	}
	
	public function addLogger(ILogger $logger)
	{
		$this->loggers[] = $logger;
	}
}

?>