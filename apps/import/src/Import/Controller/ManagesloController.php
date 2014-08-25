<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Import\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Http\Header;
use Zend\Http\Request;



class ManagesloController extends AbstractActionController
{



    public function listAction(){
        if ( $this->getRequest()->isXmlHttpRequest() ) {
            $aLista = array();
            $id_slownika = $this->getEvent()->getRouteMatch()->getParam('id_slownika');
            $aSlownik = \My\Tools\Lista::ListaWhere('import_slo','id',$id_slownika);
            $aSlownikListAvailable = explode(',',$aSlownik['column_list']);
            $aColumnNameSlo = explode(',',$aSlownik['column_mapping_list']);
            $aMetadata = \My\Tools\Lista::Kolumny($aSlownik['nazwa']);
            $aHeadFilter = array();
            $aHeadPaging = array();
            $i=0;
            foreach ($aMetadata  as $iKey => $column) {
                if(in_array($column->getName(),$aSlownikListAvailable)){
                    $aHead[$iKey]['name'] = $aColumnNameSlo[$i];
                    $aHead[$iKey]['width'] = '*';
                    $aHead[$iKey]['type'] = 'ro';
                    $aHead[$iKey]['align'] = 'left';
                    $aHead[$iKey]['sort'] = '';
                    $aHeadFilter[$iKey] = '#select_filter';
                    $i++;
                }
            }
            $aHead['filter'] = $aHeadFilter;
            $aHead['paging'] = '<div id="paging_slowniki" style="top:0px; left:-10px;border:0;width:101%;height:100%;margin: 0px; padding: 0px;" xmlns="http://www.w3.org/1999/html"><br><br></div>';
            $aData = \My\Tools\Lista::ListaWszystkieKolumny($aSlownik['nazwa']);
            $oMyGrid = new \My\Dhtmlx\Grid($aData,'id','',$aHead);
            $oMyGrid->setPagingGrid(0, count($aData));
            //$oMyGrid->setCzyKopiowacId(true);
            $xml = $oMyGrid->createXml();
            $this->getResponse()->getHeaders()->addHeaders(array('Content-type' => 'text/xml'));
            return $this->getResponse()->setContent($xml);
        }
    }

    public function editAction(){
        if ( $this->getRequest()->isXmlHttpRequest() ) {
            $sm = $this->getServiceLocator();
            $oFormModel = $sm->get('FormsElementTable');
            $oForm = new \My\Dhtmlx\Form();
            $request = $this->getRequest();
            $id_slownika = $this->getEvent()->getRouteMatch()->getParam('id_slownika');
            $id = $this->getEvent()->getRouteMatch()->getParam('id');
            $aSlownik = \My\Tools\Lista::ListaWhere('import_slo','id',$id_slownika);
            $aSlownikListAvailable = explode(',',$aSlownik['column_list']);

            if ($request->isPost()) {
                $oPost = $request->getPost();
                $bInsert = \My\Tools\Lista::Update($aSlownik['nazwa'], $oPost->toArray(),$id);
                if ($bInsert) {
                    $danexml = \My\Dhtmlx\Info::setXMLInfo(array('status' => 1, 'error' => ''));

                } else {
                    $danexml = \My\Dhtmlx\Info::setXMLInfo(array('status' => 0, 'error' => 'Błąd podczas dodania danych do słownika.'));
                }
            }else{
                $danexml = '<?xml version="1.0"?><items>';
                $label = "Edit";
                $danexml .= '<item type="fieldset"  name="krok1" className="formstyle"  label="' . $label . '" width="500">
					<item type="settings" position="label-left"  labelAlign="right"/>';

                $aKolumny = \My\Tools\Lista::Kolumny($aSlownik['nazwa']);
                $aWartosci = \My\Tools\Lista::ListaWhere($aSlownik['nazwa'],'id',$id);
                $aColumnNameSlo = explode(',',$aSlownik['column_mapping_list']);
                $width_pola = 250;
                $width_label = 150;
                $styl = $oForm->getStyle();
                $i = 0;
                foreach ($aKolumny as $sKey => $column) {
                    $sKolumna = $sKey;
                    if ($sKolumna != 'id' && in_array($column->getName(),$aSlownikListAvailable)) {

                        $danexml .= '<item type="input" required="1" name="' . $column->getName() . '" value="'.$aWartosci[ $column->getName()].'" label="' . $aColumnNameSlo[$i] . ':" inputWidth="' . $width_pola . '" labelWidth="' . $width_label . '" tolltip="tre" style="' . $styl . '">';
                        $danexml .= '<note>' . $column->getDataType() . ' ' . $column->getCharacterMaximumLength() . '</note>';
                        $danexml .= '</item>';
                        $i++;
                    }
                }
                $danexml .= '</item>';
                $danexml .= '<item type="button"  name="save" offsetLeft="350" width="150" value="Save"/>';
                $danexml .= '</items>';
            }
            $this->getResponse()->getHeaders()->addHeaders(array('Content-type' => 'text/xml'));
            return $this->getResponse()->setContent($danexml);
        }
    }

    public function addAction(){
        if ( $this->getRequest()->isXmlHttpRequest() ) {
            $sm = $this->getServiceLocator();
            $oFormModel = $sm->get('FormsElementTable');
            $oForm = new \My\Dhtmlx\Form();
            $request = $this->getRequest();
            $id_slownika = $this->getEvent()->getRouteMatch()->getParam('id_slownika');
            $aSlownik = \My\Tools\Lista::ListaWhere('import_slo','id',$id_slownika);
            if ($request->isPost()) {
                $oPost = $request->getPost();
                try{
                    $bInsert = \My\Tools\Lista::Insert($aSlownik['nazwa'], $oPost->toArray());
                    if ($bInsert) {
                        $danexml = \My\Dhtmlx\Info::setXMLInfo(array('status' => 1, 'error' => ''));

                    } else {
                        $danexml = \My\Dhtmlx\Info::setXMLInfo(array('status' => 0, 'error' => 'Błąd podczas dodania danych do słownika.'));
                    }
                }catch (Exception $e){
                    $danexml = \My\Dhtmlx\Info::setXMLInfo(array('status' => 0, 'error' => 'Błąd danych. Błąd '.$e->getMessage()));
                }
            }else{
                $danexml = '<?xml version="1.0"?><items>';
                $label = "Add";
                $danexml .= '<item type="fieldset"  name="krok1" className="formstyle"  label="' . $label . '" width="500">
					<item type="settings" position="label-left"  labelAlign="right"/>';
                $aKolumny = \My\Tools\Lista::Kolumny($aSlownik['nazwa']);
                $aSlownikListAvailable = explode(',',$aSlownik['column_list']);
                $width_pola = 250;
                $width_label = 150;
                $styl = $oForm->getStyle();
                $aColumnNameSlo = explode(',',$aSlownik['column_mapping_list']);
                $i=0;
                foreach ($aKolumny as $sKey => $column) {
                    $sKolumna = $sKey;
                    if ($sKolumna != 'id' && in_array($column->getName(),$aSlownikListAvailable)) {
                        $danexml .= '<item type="input" required="1" name="' . $column->getName() . '" value="" label="' .  $aColumnNameSlo[$i] . ':" inputWidth="' . $width_pola . '" labelWidth="' . $width_label . '" tolltip="tre" style="' . $styl . '">';
                        $danexml .= '<note>' . $column->getDataType() . ' ' . $column->getCharacterMaximumLength() . '</note>';
                        $danexml .= '</item>';
                        $i++;
                    }
                }
                $danexml .= '</item>';
                $danexml .= '<item type="button"  name="save" offsetLeft="350" width="150" value="Save"/>';
                $danexml .= '</items>';
            }
            $this->getResponse()->getHeaders()->addHeaders(array('Content-type' => 'text/xml'));
            return $this->getResponse()->setContent($danexml);
        }
    }
}


