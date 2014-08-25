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
use \My\Dhtmlx\FormTest;
use \My\Dhtmlx\Info;

class SlabController extends CrudController
{
    protected $oContainerHistoryTable;

    public function __construct(){
        $this->sNameServiceReadTable = 'SlabView';
        $this->sNameServiceCreateUpdateTable = 'SlabTable';
        $this->iIdForm = 10;
        $this->sSessionNameSpace = 'slab_search';
    }

    public function onDispatch( \Zend\Mvc\MvcEvent $e )
    {
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id_container');
        $container = new \Zend\Session\Container($this->sSessionNameSpace);
        $container->offsetSet('id_container', $id);
        $this->aAdditionalArray = array('id_container'=>$id);
        return parent::onDispatch( $e );
    }


    public function historyAction(){
        if ( $this->getRequest()->isXmlHttpRequest() ) {
            $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
            $aHistoria = $this->getContainerHistoryTable()->getHistory($this->getCreateUpdateTable()->getTable(), $id);
            $aHistoriaWynik = array();
            foreach ($aHistoria as $aRekord) {
                $op = $aRekord["operation"] . '--' . 'mariusz' . '--' . $aRekord['cr_date'];
                $aHistoriaWynik[$aRekord['row_id']][$op][$aRekord['column_name']]['old_value'] = $aRekord['old_value'];
                $aHistoriaWynik[$aRekord['row_id']][$op][$aRekord['column_name']]['new_value'] = $aRekord['new_value'];
            }
            $oGrid = new \My\Dhtmlx\Tree();
            $img = array(1 => "", "top.gif", "Text16.png");
            $img2 = array("I" => "Check16.png", "U" => "Refresh16.png", "D" => "Delete16.png");
            $xml = $oGrid->TreeGridXMLHistory($aHistoriaWynik, $img, $img2, '');

            $this->getResponse()->getHeaders()->addHeaders(array('Content-type' => 'text/xml'));
            return $this->getResponse()->setContent($xml);
        }
    }

    public function calculeAction(){
        $fOneMeterToInch = 39.37;
        $fMeterToInch = 10.76;
        $lenghtInch = $this->getEvent()->getRouteMatch()->getParam('lenght');
        $widthInch= $this->getEvent()->getRouteMatch()->getParam('width');
        $sqft_price = $this->getEvent()->getRouteMatch()->getParam('sqft_price');
        if ( $this->getRequest()->isXmlHttpRequest() ) {
            $request = $this->getRequest();
            if ($request->isPost()) {
                //post przelicza metry z forma calcule
                $oPost = $request->getPost();
                $aPost = $oPost->toArray();
                $lenghtPost = number_format($aPost['lenght']*$fOneMeterToInch,2);
                $widthPost = number_format($aPost['width']*$fOneMeterToInch,2);
                $fSquareMeterPrice = $aPost['square_metre_price'];
                $fNewSqftPrice =  round((($fSquareMeterPrice/$fMeterToInch)*100)/100,2);
                $fSlabPrice =  round($fNewSqftPrice*$fMeterToInch*($aPost['lenght']*$aPost['width']),2);
                $xml = Info::setXMLInfo(
                        array(
                            'status' => 1,
                            'length'=>$lenghtPost,
                            'width'=>$widthPost,
                            'sqft_price'=>$fNewSqftPrice,
                            'slab_price'=>$fSlabPrice,
                        )
                );

                $this->getResponse()->getHeaders()->addHeaders(array('Content-type' => 'text/xml'));
                return $this->getResponse()->setContent($xml);
            }
            $fSquareMetrePrice = round((($sqft_price*$fMeterToInch)*100)/100,2);
            $aRow = array('lenght'=>$lenghtInch/$fOneMeterToInch,'width'=>$widthInch/$fOneMeterToInch,'square_metre_price'=>$fSquareMetrePrice);

            $sm = $this->getServiceLocator();
            $oFormModel = $sm->get('FormsElementTable');
            $aFormModel = $oFormModel->getPolaFormularza(11);
            $oForm = new FormTest($aFormModel);
            $oForm->setPokazPrzyciskZapisz(false);
            $xml = $oForm->getXMLForm($aRow);
            $this->getResponse()->getHeaders()->addHeaders(array('Content-type' => 'text/xml'));
            return $this->getResponse()->setContent($xml);
        }
    }


    public function calculeslabAction(){
        $fOneMeterToInch = 39.37;
        $fMeterToInch = 10.76;
        if ( $this->getRequest()->isXmlHttpRequest() ) {
                $lenghtInch = $this->getEvent()->getRouteMatch()->getParam('lenght');
                $widthInch= $this->getEvent()->getRouteMatch()->getParam('width');
                $fSqftPrice = $this->getEvent()->getRouteMatch()->getParam('sqft_price');
                //post przelicza metry z forma calcule
                $lenghtMeter = number_format($lenghtInch/$fOneMeterToInch,2) ;
                $widthMeter = number_format($widthInch/$fOneMeterToInch,2) ;
                $fSlabPrice =  round($fSqftPrice*$fMeterToInch*($lenghtMeter*$widthMeter),2);
                $xml = Info::setXMLInfo(
                    array(
                        'status' => 1,
                        'length'=>$lenghtInch,
                        'width'=>$widthInch,
                        'sqft_price'=>$fSqftPrice,
                        'slab_price'=>$fSlabPrice,
                    )
                );



            $this->getResponse()->getHeaders()->addHeaders(array('Content-type' => 'text/xml'));
            return $this->getResponse()->setContent($xml);
        }
    }

    public function getContainerHistoryTable(){
        if(!$this->oContainerHistoryTable){
            //ToDo throw instance of AbstractTable
            $sm = $this->getServiceLocator();
            $this->oContainerHistoryTable = $sm->get('ContainerHistoryTable');
        }
        return $this->oContainerHistoryTable;
    }

}


