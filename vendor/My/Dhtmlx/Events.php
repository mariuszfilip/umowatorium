<?php
/*
 *
 * Klasa do Kalendarza
 */


namespace My\Dhtmlx;
class Events{

/*<data>
<event id="2">
<start_date><![CDATA[2014-06-24 09:00:00]]></start_date>
<end_date><![CDATA[2014-06-24 12:15:00]]></end_date>
<text><![CDATA[test]]></text>
<details><![CDATA[]]></details>
</event>
</data>*/

	public static function getXML($aEvents){
		$oDom = new \DOMDocument('1.0', 'utf-8');
		$elementData= $oDom->createElement('data');
		$oDom->appendChild($elementData);
		if(is_array($aEvents)&& count($aEvents)>0){
			foreach($aEvents as $key){
				$eventXml=$oDom->createElement('event');
				$eventXml->setAttribute('id',$key['id']);
				foreach($key as $e=>$event){
					$childEvent=$oDom->createElement($e,$event);
					$eventXml->appendChild($childEvent);
				}
			$elementData->appendChild($eventXml);

			}
		}
		return $oDom->saveXML();
	}


}