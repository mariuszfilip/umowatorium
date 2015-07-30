<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Mariusz
 * Date: 10.02.14
 * Time: 11:55
 * To change this template use File | Settings | File Templates.
 */
namespace My\Dhtmlx;
class Menu{

    protected $aData = array();

    protected $oDom;

    protected $oParent;

    protected $link = '';

    public function __construct($aData = array(),$link){
        $this->aData = $aData;
        $this->oDom = new \DOMDocument('1.0', 'utf-8');
        $this->link = $link;
    }

    public function createXml(){
        $elementMenu = $this->oDom->createElement('menu');
        $this->oDom->appendChild($elementMenu);
        if(is_array($this->aData) && !empty($this->aData)){
            foreach($this->aData as $aItem){
                $item = $this->oDom->createElement('item');
                $item->setAttribute('id',$aItem['id']);
                $item->setAttribute('text',$aItem['nazwa']);
                $this->oParent = $elementMenu->appendChild($item);
                $href = $this->oDom->createElement('href',$this->link.$aItem['modul'].'/'.$aItem['kontroller'].'/'.$aItem['akcja']);
                $this->oParent->appendChild($href);
                if(isset($aItem['children'])){
                     $this->dodajElement($aItem['children']);
                }
            }
        }

        return $this->oDom->saveXML();
    }
    protected function dodajElement($aItems){
        if(is_array($aItems)){
            foreach($aItems as $aItem){
                $item = $this->oDom->createElement('item');
                $item->setAttribute('id',$aItem['id']);
                $item->setAttribute('text',$aItem['text']);
                $oHref = $this->oParent->appendChild($item);
                $href = $this->oDom->createElement('href',$this->link.'/'.$aItem['kontroller'].'/'.$aItem['akcja']);
                $oHref->appendChild($href);
                $temp = $this->oParent;
                if(isset($aItem['children'])){
                    $this->dodajElement($aItem['children']);
                }
                $this->oParent = $temp;
            }
        }
    }
}