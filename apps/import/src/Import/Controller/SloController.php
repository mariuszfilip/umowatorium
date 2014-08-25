<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Import\Controller;

use Zend\Http\Header;
use Zend\Http\Request;



class SloController extends CrudController
{

    public function indexAction(){
        if ( $this->getRequest()->isXmlHttpRequest() ) {

            $aData = \My\Tools\Lista::ListaOgraniczoneKolumny('import_slo',array('id','description'));
            $oMyGrid = new \My\Dhtmlx\Grid($aData,'id','');
            $oMyGrid->setPagingGrid(0, count($aData));
            $oMyGrid->setCzyKopiowacId(true);
            $xml = $oMyGrid->createXml();
            $this->getResponse()->getHeaders()->addHeaders(array('Content-type' => 'text/xml'));
            return $this->getResponse()->setContent($xml);
        }
    }

    public function listAction(){
        if ( $this->getRequest()->isXmlHttpRequest() ) {
            $id_slownika = $this->getEvent()->getRouteMatch()->getParam('id_slownika');
            $aSlownik = \My\Tools\Lista::ListaWhere('import_slo','id',$id_slownika);
            if($aSlownik['column_list'] != ''){
                $aSlownikListAvailable = explode(',',$aSlownik['column_list']);
            }else{
                $aSlownikListAvailable = array();
            }
            $aColumnNameSlo = explode(',',$aSlownik['column_mapping_list']);
            $aMetadata = \My\Tools\Lista::Kolumny($aSlownik['nazwa']);
            $aHeadFilter = array();
            $i=0;
            foreach ($aMetadata  as $iKey => $column) {
                if(in_array($column->getName(),$aSlownikListAvailable) || empty($aSlownikListAvailable)){
                    $aHead[$iKey]['name'] = $aColumnNameSlo[$i];
                    $aHead[$iKey]['width'] = '*';
                    $aHead[$iKey]['type'] = 'ro';
                    $aHead[$iKey]['align'] = 'left';
                    $aHead[$iKey]['sort'] = '';
                    $aHeadFilter[$iKey] = '#select_filter';
                    $i++;
                }
            }
            $iCount = (int)$this->params()->fromQuery('count', 10);
            $iStart = (int)$this->params()->fromQuery('posStart', 0);
            $iPage = round(($iStart/$iCount), 0, PHP_ROUND_HALF_UP)+1;

           // $aHead['filter'] = $aHeadFilter;
            //$aHead['paging'] = '<div id="paging_slowniki" style="top:0px; left:-10px;border:0;width:101%;height:100%;margin: 0px; padding: 0px;" xmlns="http://www.w3.org/1999/html"><br><br></div>';
            $aData = \My\Tools\Lista::ListaWszystkieKolumnyLimit($aSlownik['nazwa']);
           // $aDataLimit = \My\Tools\Lista::ListaWszystkieKolumnyLimit($aSlownik['nazwa'],$iCount,$iStart);
            $oMyGrid = new \My\Dhtmlx\Grid($aData,'id','',$aHead);


            //$oMyGrid->setPagingGrid($iStart, count($aData));
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
                $aKolumny = \My\Tools\Lista::Kolumny($aSlownik['nazwa']);
                $aWartosci = \My\Tools\Lista::ListaWhere($aSlownik['nazwa'],'id',$id);

                if($aSlownik['id_form'] != 0){
                    $this->iIdForm = $aSlownik['id_form'];
                    $danexml = $this->getForm()->getXMLForm($aWartosci);
                }else{
                    $danexml = '<?xml version="1.0"?><items>';
                    $label = "Edit";
                    $danexml .= '<item type="fieldset"  name="krok1" className="formstyle"  label="' . $label . '" width="500">
                        <item type="settings" position="label-left"  labelAlign="right"/>';

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
            }
            $this->getResponse()->getHeaders()->addHeaders(array('Content-type' => 'text/xml'));
            return $this->getResponse()->setContent($danexml);
        }
    }

    public function addAction(){
        if ( $this->getRequest()->isXmlHttpRequest() ) {
            $id_slownika = $this->getEvent()->getRouteMatch()->getParam('id_slownika');
            $aSlownik = \My\Tools\Lista::ListaWhere('import_slo','id',$id_slownika);
            $sm = $this->getServiceLocator();
            $oFormModel = $sm->get('FormsElementTable');
            $oForm = new \My\Dhtmlx\Form();
            $request = $this->getRequest();

            if ($request->isPost()) {
                $oPost = $request->getPost();
                try{
                    (int)$id_user = $this->getAuthService()->getStorage()->read()->id;
                    $aDataDefault = array('cr_date'=>new \Zend\Db\Sql\Expression("getdate()"),'cr_user'=>$id_user,'deleted'=>0,'is_active'=>0);
                    $bInsert = \My\Tools\Lista::Insert($aSlownik['nazwa'], array_merge($oPost->toArray(),$aDataDefault));
                    if ($bInsert) {
                        $danexml = \My\Dhtmlx\Info::setXMLInfo(array('status' => 1, 'error' => ''));

                    } else {
                        $danexml = \My\Dhtmlx\Info::setXMLInfo(array('status' => 0, 'error' => 'Błąd podczas dodania danych do słownika.'));
                    }
                }catch (Exception $e){
                    $danexml = \My\Dhtmlx\Info::setXMLInfo(array('status' => 0, 'error' => 'Błąd danych. Błąd '.$e->getMessage()));
                }
            }else{
                if($aSlownik['id_form'] != 0){
                    $this->iIdForm = $aSlownik['id_form'];
                    $aKolumny = \My\Tools\Lista::Kolumny($aSlownik['nazwa']);
                    $danexml = $this->getForm()->getXMLForm($aKolumny);
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
            }
            $this->getResponse()->getHeaders()->addHeaders(array('Content-type' => 'text/xml'));
            return $this->getResponse()->setContent($danexml);
        }
    }

    public function deleteAction(){
        if ( $this->getRequest()->isXmlHttpRequest() ) {
            $id_slownika = $this->getEvent()->getRouteMatch()->getParam('id_slownika');
            $id = $this->getEvent()->getRouteMatch()->getParam('id');
            $aSlownik = \My\Tools\Lista::ListaWhere('import_slo','id',$id_slownika);
            if($aSlownik){
                $aWartosci = \My\Tools\Lista::ListaWhere($aSlownik['nazwa'],'id',$id);
                if($aWartosci){
                    $bUpdate = \My\Tools\Lista::Update($aSlownik['nazwa'], array('deleted'=>1),$id);

                    if ($bUpdate) {
                        $danexml = \My\Dhtmlx\Info::setXMLInfo(array('status' => 1, 'error' => ''));

                    } else {
                        $danexml = \My\Dhtmlx\Info::setXMLInfo(array('status' => 0, 'error' => 'Błąd podczas dodania danych do słownika.'));
                    }
                }

            }
            $this->getResponse()->getHeaders()->addHeaders(array('Content-type' => 'text/xml'));
            return $this->getResponse()->setContent($danexml);
        }
    }

    public function historyAction(){

    }
}


