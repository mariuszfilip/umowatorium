<?php
/**
 * Created by JetBrains PhpStorm.
 * User: admin
 * Date: 24.05.13
 * Time: 14:00
 * To change this template use File | Settings | File Templates.
 */
namespace My\Dhtmlx;
class Info{



    public static function setXMLInfo($aInfo){
        /*
         * $tab w formacie $tab[klucz] = wartosć
         * w xml to bedzie wyglądało
         * <klucz> wartosc </klucz>
         */
        $oDom = new \DOMDocument('1.0', 'utf-8');
        $elementInfo= $oDom->createElement('info');
        $oDom->appendChild($elementInfo);
        if(is_array($aInfo)&& count($aInfo)>0){
            foreach($aInfo as $key=>$val){
                $row = $oDom->createElement($key,htmlspecialchars($val));
                $elementInfo->appendChild($row);
            }
        }
        return $oDom->saveXML();
    }
}