<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Mariusz
 * Date: 11.02.14
 * Time: 15:47
 * To change this template use File | Settings | File Templates.
 */
namespace My\Dhtmlx;
class Toolbar{

    protected $aData = array();
    protected $oDom;

    public function __construct($aData = array()){
        $this->aData = $aData;
        $this->oDom = new \DOMDocument('1.0', 'utf-8');
    }
    /*
     * Array => key => array
     */
    public function createXmlSubMenu(){
        $elementRows = $this->oDom->createElement('toolbar');
        $this->oDom->appendChild($elementRows);

        if(is_array($this->aData) && !empty($this->aData)){
            foreach($this->aData as $key => $aRow){

                $oZasobyDhtmlx = new Application_Model_Zasoby_Dhtmlx();
                $aZasoby = $oZasobyDhtmlx->getZasobyDhtmlx($aRow['id']);
                    foreach($aZasoby as $aZasob){
                        $row = $this->oDom->createElement('item');
                        $row->setAttribute('id',$aRow['id']);
                        $row->setAttribute('text',$aRow['nazwa']);
                        $row->setAttribute('img',$aZasob['ikonka']);
                        $row->setAttribute('type',$aZasob['typ']);
                        $elementRows->appendChild($row);

                        $elementRowsAction = $this->oDom->createElement('action');
                        $oAction = $elementRows->appendChild($elementRowsAction);
                        $row = $this->oDom->createElement('obiekt', $aZasob['obiekt']);
                        $oAction->appendChild($row);
                        $row = $this->oDom->createElement('id',$aRow['id']);
                        $oAction->appendChild($row);
                    }
            }
        }
        return $this->oDom->saveXML();
    }
    /*
     * Array => key => array
     */
    public function createXml(){
        $elementRows = $this->oDom->createElement('toolbar');
        $this->oDom->appendChild($elementRows);

        if(is_array($this->aData) && !empty($this->aData)){
            foreach($this->aData as $key => $aRow){
                    $row = $this->oDom->createElement('item');
                    $row->setAttribute('id',$aRow['id']);
                    $row->setAttribute('text',$aRow['nazwa']);
                    $row->setAttribute('img',$aRow['ikonka']);
                    $row->setAttribute('type',$aRow['typ']);
                    $elementRows->appendChild($row);
            }
        }
        return $this->oDom->saveXML();
    }

}