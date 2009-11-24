<?php

// naincluduje kniznicu SPYC pre parsovanie YAML suborov
// http://spyc.sourceforge.net/
require_once(dirname(__FILE__).'/spyc.php');

/**
 * Handler pre YAML zdroje
 *
 * @author Tomas Majer <majer@monogram.sk>
 * @package global
 * @version $Id: YamlResourceHandler.php 117 2008-12-14 23:01:01Z majer $
 */
class YamlResourceHandler extends AbstractResourceHandler
{
	/**
	 * Spracuvava yaml subor.
	 * Pouziva na sparsovanie parser SPYC.
	 *
	 * @see http://spyc.sourceforge.net/
	 * @return array
	 */
	public function handle()
	{
		$yamlData = $this->data;
		
		$result = Spyc::YAMLLoad($yamlData);

		$this->output = $result;

		return $result;
	}
}


?>