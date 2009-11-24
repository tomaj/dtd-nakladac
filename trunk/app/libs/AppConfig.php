<?php

class AppConfig
{
	protected static $file = 'config/app.yml';

	protected static $data = null;

	protected static function init()
	{
		$handler = new YamlResourceHandler(new FileResource(BASE_DIR . '/' . self::$file));
		self::$data = $handler->handle();
	}

	public static function get($key)
	{
		if (self::$data == null) self::init();

		if (isset(self::$data[$key]))
		{
			return self::$data[$key];
		}
		throw new Exception('Nenasiel sa zadany kluc configu');
	}

	public static function getAll()
	{
		if (self::$data == null) self::init();
		return self::$data;
	}
}

?>