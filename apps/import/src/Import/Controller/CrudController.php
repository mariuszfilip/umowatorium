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
use Zend\Http\Header;
use Zend\Http\Request;
use \My\Dhtmlx\Grid;
use \My\Dhtmlx\FormTest;
use \My\Dhtmlx\Info;


class CrudController extends AbstractActionController
{
    protected $sNameServiceReadTable;

    protected $sNameServiceCreateUpdateTable;

    protected $oReadTable;

    protected $oCreateUpdateTable;

    protected $iIdForm;

    protected $oForm;

    protected $bPaginator = false;

    protected $sSessionNameSpace = '';

    protected $aAdditionalArray = array();

    public function listAction(){
        if ( $this->getRequest()->isXmlHttpRequest() ) {

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
            if(!$this->bPaginator){
                $iCount = $paginator->getTotalItemCount();
            }
            $paginator->setItemCountPerPage($iCount);
            $adapter = $paginator->getAdapter();
            $results = $adapter->getItems( $iStart, $iCount);

            $oMyGrid = new Grid($results->toArray(),'id');
            $oMyGrid->setPagingGrid($iStart,$paginator->getTotalItemCount());
            $xml = $oMyGrid->createXml();
            $this->getResponse()->getHeaders()->addHeaders(array('Content-type' => 'text/xml'));
            return $this->getResponse()->setContent($xml);
        }
    }

    public function editAction(){
        $id = $this->getEvent()->getRouteMatch()->getParam('id');
        if ( $this->getRequest()->isXmlHttpRequest() ) {
            $request = $this->getRequest();
            if ($request->isPost()) {
                $oPost = $request->getPost();
                $aPost = $oPost->toArray();
                if ($this->getForm()->isValid($aPost)) {

                    (int)$id_user = $this->getAuthService()->getStorage()->read()->id;
                    $aUpdate = array_merge($aPost,$this->aAdditionalArray);
                    $id = $this->getCreateUpdateTable()->save($aUpdate , $id);
                    $xml = Info::setXMLInfo(array('newid'=>$id,'status' => 1, 'error' => $this->getForm()->getError()));
                }else{
                    $xml = Info::setXMLInfo(array('newid'=>0,'status' => 0, 'error' => $this->getForm()->getError()));
                }
                $this->getResponse()->getHeaders()->addHeaders(array('Content-type' => 'text/xml'));
                return $this->getResponse()->setContent($xml);
            }

            $aRow = $this->getCreateUpdateTable()->getRow($id);


            $this->getForm()->setPokazPrzyciskZapisz(false);
            $xml = $this->getForm()->getXMLForm($aRow);
            $this->getResponse()->getHeaders()->addHeaders(array('Content-type' => 'text/xml'));
            return $this->getResponse()->setContent($xml);
        }
    }

    public function addAction(){
        if ( $this->getRequest()->isXmlHttpRequest() ) {
            $request = $this->getRequest();
            if ($request->isPost()) {
                $oPost = $request->getPost();
                $aPost = $oPost->toArray();
                if ($this->getForm()->isValid($aPost)) {
                    (int)$id_user = $this->getAuthService()->getStorage()->read()->id;
                    $aInsert = array_merge($aPost,$this->aAdditionalArray,array('cr_user'=>$id_user,'cr_date'=> new \Zend\Db\Sql\Expression("getdate()")));
                    $id = $this->getCreateUpdateTable()->save($aInsert);
                    $xml = Info::setXMLInfo(array('newid'=>$id,'status' => 1, 'error' => $this->getForm()->getError()));
                }else{
                    $xml = Info::setXMLInfo(array('newid'=>0,'status' => 0, 'error' => $this->getForm()->getError()));
                }
                $this->getResponse()->getHeaders()->addHeaders(array('Content-type' => 'text/xml'));
                return $this->getResponse()->setContent($xml);
            }
            $aRow = $this->getCreateUpdateTable()->getColumnsArray();

            $this->getForm()->setPokazPrzyciskZapisz(false);
            $xml = $this->getForm()->getXMLForm($aRow);
            $this->getResponse()->getHeaders()->addHeaders(array('Content-type' => 'text/xml'));
            return $this->getResponse()->setContent($xml);
        }
    }

    public function deleteAction(){
        $id = $this->getEvent()->getRouteMatch()->getParam('id');
        if ( $this->getRequest()->isXmlHttpRequest() ) {

            $aRow = $this->getCreateUpdateTable()->getRow($id);
            if($aRow){
                $this->getCreateUpdateTable()->deleteRow($id);
            }
            $xml = Info::setXMLInfo(array('status' => 1, 'error' => 'ok'));
            $this->getResponse()->getHeaders()->addHeaders(array('Content-type' => 'text/xml'));
            return $this->getResponse()->setContent($xml);
        }
    }

    public function getForm(){
        if(!$this->oForm){
            $sm = $this->getServiceLocator();
            $oFormModel = $sm->get('FormsElementTable');
            $aFormModel = $oFormModel->getPolaFormularza($this->iIdForm);
            $this->oForm = new FormTest($aFormModel);
        }
        return $this->oForm;
    }

    public function getReadTable(){
        if(!$this->oReadTable){
            //ToDo throw instance of AbstractTable
            $sm = $this->getServiceLocator();
            $this->oReadTable = $sm->get($this->sNameServiceReadTable);
        }
        return $this->oReadTable;
    }

    public function getCreateUpdateTable(){
        if(!$this->oCreateUpdateTable){
            //ToDo throw instance of AbstractTable
            $sm = $this->getServiceLocator();
            $this->oCreateUpdateTable = $sm->get($this->sNameServiceCreateUpdateTable);
        }
        return $this->oCreateUpdateTable;
    }

    public function getAuthService(){
         $sm  = $this->getServiceLocator();

         $auth = $sm->get('AuthService');
         return $auth;
    }

}


