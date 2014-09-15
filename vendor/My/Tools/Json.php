<?php
namespace My\Tools;
class Json{


	public static function arrayToJsonString($aDane){

		ob_start();
		echo \Zend\Json\Json::encode($aDane);

		$sOut=ob_get_clean();
		return $sOut;
	}
}