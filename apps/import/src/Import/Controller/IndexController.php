<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Import\Controller;

use Zend\View\Model\ViewModel;

use Zend\Http\Header;
use Zend\Http\Request;
use \My\Dhtmlx\Grid;
use \My\Dhtmlx\Form;

use Zend\Mvc\Router\RouteMatch;

class IndexController extends CrudController
{

    public function __construct(){
        $this->sNameServiceCreateUpdateTable = 'ContainerTable';
        $this->sNameServiceReadTable = 'ContainerView';
        $this->sSessionNameSpace = 'search_container';

        $sAction = $this->params('action');

        if($sAction  == 'add'){
            $this->iIdForm = 12;
        }else{
            $this->iIdForm = 3;
        }
        $this->bPaginator = true;
    }

    protected $oContainerHistoryTable;

    public function indexAction(){
    }


    public function searchAction(){
        if ( $this->getRequest()->isXmlHttpRequest() ) {
            $request = $this->getRequest();
            $sm = $this->getServiceLocator();
            $oFormModel = $sm->get('FormsElementTable');
            $aFormModel = $oFormModel->getPolaFormularza(2);
            if ($request->isPost()) {
                $oPost = $request->getPost();
                $aPost = $oPost->toArray();
                $container = new \Zend\Session\Container($this->sSessionNameSpace);

                foreach($aPost as $klucz => $wartosc){
                    $container->offsetSet($klucz, $wartosc);
                }
                $ind = $this->params()->fromQuery('orderby', 0);
                $direction =  $this->params()->fromQuery('direction', 'desc');
                $container->offsetSet('direction', $direction);
                $container->offsetSet('orderby', $ind);

                $xml = '<info>Ok</info>';
                $this->getResponse()->getHeaders()->addHeaders(array('Content-type' => 'text/xml'));
                return $this->getResponse()->setContent($xml);
            }
            $oForm = new Form();
            $oForm->setPokazPrzyciskZapisz(false);
            $xml = $oForm->getXMLForm($aFormModel);
            $this->getResponse()->getHeaders()->addHeaders(array('Content-type' => 'text/xml'));
            return $this->getResponse()->setContent($xml);
        }
    }

    public function manageAction(){
        $_SESSION['page_link'] = 'importedit';
        $id = $this->getEvent()->getRouteMatch()->getParam('id');
        return new ViewModel(array('id'=>$id));
    }

    public function infoAction(){
        $id = $this->getEvent()->getRouteMatch()->getParam('id');
        if ( $this->getRequest()->isXmlHttpRequest() ) {
            $sm = $this->getServiceLocator();
            $oFormModel = $sm->get('FormsElementTable');
            $aFormModel = $oFormModel->getPolaFormularza(3);
            $oForm = new Form();
            $oForm->setPokazPrzyciskZapisz(false);
            $oContainerTable = $sm->get('ContainerTable');
            $aData = $oContainerTable->getContainer($id);
            $aDataGrid =  Grid::polaczFromularzZPelnymDanymiDoGrida($aFormModel,$aData);
            $oGrid = new Grid($aDataGrid,'id');
            $xml = $oGrid->createXml();
            $this->getResponse()->getHeaders()->addHeaders(array('Content-type' => 'text/xml'));
            return $this->getResponse()->setContent($xml);
        }
    }

    public function locationAction(){
        if ( $this->getRequest()->isXmlHttpRequest() ) {
            $danexml = '<?xml version="1.0"?><items>';
            $danexml .= '<item type="fieldset"  name="loc" className="formstyle"  label="Locations" width="200">
					<item type="settings" position="label-right" inputWidth="50" labelWidth="100"  labelAlign="left"/>';

            $aLocation = \My\Tools\Lista::Lista('location');
            if (!empty($aLocation)) {
                foreach ($aLocation as $val) {
                    $danexml .= '<item type="checkbox" value="'.$val["id"].'"   name="'.$val["short"].'"
                label="'.$val["name"].'" />';
                }
            }
            $danexml .= '</item>';

            $danexml .= '</items>';
            $this->getResponse()->getHeaders()->addHeaders(array('Content-type' => 'text/xml'));
            return $this->getResponse()->setContent($danexml);
        }
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

    public function pdfAction(){
        require_once ROOT_DIR.'/vendor/My/grid-pdf-php/gridPdfGenerator.php';
        require_once ROOT_DIR.'/vendor/My/grid-pdf-php/tcpdf/tcpdf.php';
        require_once ROOT_DIR.'/vendor/My/grid-pdf-php/gridPdfWrapper.php';
        if($this->sSessionNameSpace != ''){
            $container = new \Zend\Session\Container($this->sSessionNameSpace);
            $this->getReadTable()->createWhere($container);
            if($container->offsetExists('orderby')){
                $this->getReadTable()->createOrder($container->offsetGet('orderby'),$container->offsetGet('direction'));
            }
        }
        $paginator = $this->getReadTable()->fetchAllPaginator();

        $iCount = (int)$this->params()->fromQuery('count', 20);
        $iStart = (int)$this->params()->fromQuery('posStart', 0);
        $iPage = round(($iStart/$iCount), 0, PHP_ROUND_HALF_UP)+1;

        $paginator->setCurrentPageNumber($iPage);
        $iCount = $paginator->getTotalItemCount();

        $paginator->setItemCountPerPage($iCount);
        $adapter = $paginator->getAdapter();
        $results = $adapter->getItems( $iStart, $iCount);
        $aHead =$this->prepareHeadGrid();
        $sFooter =implode(',',$this->getReadTable()->getColumnViewList());
        $oMyGrid = new Grid($results->toArray(),'id','',array_merge($aHead,array('footer'=>$this->getReadTable()->getColumnViewList())));
        $oMyGrid->setPagingGrid($iStart,$paginator->getTotalItemCount());
        $xml = $oMyGrid->createXml();
        ob_clean();
        $pdf = new \gridPdfGenerator();
        $xmlPDF = simplexml_load_string($xml);
        $pdf->printGrid($xmlPDF);
        exit();
    }

    protected function prepareHeadGrid(){
        $aHead = array();
        $aHead[0]['name'] = 'Location';
        $aHead[0]['width'] = '70';
        $aHead[0]['type'] = 'ro';
        $aHead[0]['align'] = 'left';
        $aHead[0]['sort'] = '';

        $aHead[1]['name'] = 'ISF';
        $aHead[1]['width'] = '50';
        $aHead[1]['type'] = 'ro';
        $aHead[1]['align'] = 'left';
        $aHead[1]['sort'] = '';

        $aHead[2]['name'] = 'Port';
        $aHead[2]['width'] = '70';
        $aHead[2]['type'] = 'ro';
        $aHead[2]['align'] = 'left';
        $aHead[2]['sort'] = '';

        $aHead[3]['name'] = 'Container';
        $aHead[3]['width'] = '190';
        $aHead[3]['type'] = 'ro';
        $aHead[3]['align'] = 'left';
        $aHead[3]['sort'] = '';

        $aHead[4]['name'] = 'Supplier';
        $aHead[4]['width'] = '150';
        $aHead[4]['type'] = 'ro';
        $aHead[4]['align'] = 'left';
        $aHead[4]['sort'] = '';

        $aHead[5]['name'] = 'Stone names';
        $aHead[5]['width'] = '150';
        $aHead[5]['type'] = 'ro';
        $aHead[5]['align'] = 'left';
        $aHead[5]['sort'] = '';

        $aHead[6]['name'] = 'ETA';
        $aHead[6]['width'] = '70';
        $aHead[6]['type'] = 'ro';
        $aHead[6]['align'] = 'left';
        $aHead[6]['sort'] = '';

        $aHead[7]['name'] = 'RCVD';
        $aHead[7]['width'] = '70';
        $aHead[7]['type'] = 'ro';
        $aHead[7]['align'] = 'left';
        $aHead[7]['sort'] = '';

        $aHead[8]['name'] = 'Arrival';
        $aHead[8]['width'] = '70';
        $aHead[8]['type'] = 'ro';
        $aHead[8]['align'] = 'left';
        $aHead[8]['sort'] = '';


        $aHead[9]['name'] = 'Docs';
        $aHead[9]['width'] = '50';
        $aHead[9]['type'] = 'ro';
        $aHead[9]['align'] = 'left';
        $aHead[9]['sort'] = '';


        $aHead[10]['name'] = 'MTRL';
        $aHead[10]['width'] = '130';
        $aHead[10]['type'] = 'ro';
        $aHead[10]['align'] = 'left';
        $aHead[10]['sort'] = '';


        $aHead[11]['name'] = 'OCF';
        $aHead[11]['width'] = '70';
        $aHead[11]['type'] = 'ro';
        $aHead[11]['align'] = 'left';
        $aHead[11]['sort'] = '';
        return $aHead;
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


