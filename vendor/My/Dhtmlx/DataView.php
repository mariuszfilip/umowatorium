<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Mariusz
 * Date: 25.02.14
 * Time: 12:44
 * To change this template use File | Settings | File Templates.
 */
namespace My\Dhtmlx;
class DataView{


	protected $xml='';
	public  function __construct($aDane){

		$oDom = new \DOMDocument('1.0', 'utf-8');
		$elementData= $oDom->createElement('data');
		$oDom->appendChild($elementData);
		  if(is_array($aDane) && count($aDane) > 0) {
			  foreach($aDane as  $record) {
//				  $xml .= '<item>';
				 $item= $oDom->createElement('item');
				  foreach ($record as $key=>$val){
					  if(!in_array($key,array(''))){
						$oVal=$oDom->createElement($key,$val);
						 $item->appendChild($oVal);
					  }
				  }
				$elementData->appendChild($item);
			  }
		  }
		$this->xml= $oDom->saveXML();
	}

	public function getXml()
	{
		return$this->xml;
	}
}