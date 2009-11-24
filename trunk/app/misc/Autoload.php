<?php

class Autoload
{
	protected $keyMap = null;
	
	protected $ignoreDirs = array('.svn');
	
	protected $extensions = array('php');
	
	/**
	 * @var Autoload
	 */
	protected static $autoload = null;
	
	/**
	 * @var Autoload
	 */
	protected function getInstance()
	{
		if (self::$autoload == null)
		{
			self::$autoload = new Autoload();
		}
		return self::$autoload;
	}

	public static function autoloadFunction($className)
	{
		if (class_exists($className)) return;
		
		$autoload = Autoload::getInstance();
		$autoload->init();
		
		if (isset($autoload->keyMap[$className]))
		{
			require_once($autoload->keyMap[$className]);
		}
	}
	
	protected function init()
	{
		if ($this->keyMap == null)
		{
			$this->scanDir(BASE_DIR);
		}
	}

	protected function scanDir($directory)
	{
		$dir = dir($directory);
		if (!$dir) return;
		while (false !== ($entry = $dir->read()))
		{
			if (in_array($entry, array('.', '..'))) continue;
			if (in_array($entry, $this->ignoreDirs)) continue;
			
			$fullPath = $directory . '/' . 	$entry;
			if (is_dir($fullPath))
			{
				$this->scanDir($fullPath);
			}

			if ($entry[0] >= 'A' && $entry[0] <= 'Z')
			{
				$ext = end(explode('.', $entry));
				if (in_array($ext, $this->extensions))
				{
					$className = str_replace('.' . $ext, '', $entry);
					$this->keyMap[$className] = $fullPath;
				}
			}
		}
		$dir->close();
	} 	
	
}

spl_autoload_register('Autoload::autoloadFunction');

?>