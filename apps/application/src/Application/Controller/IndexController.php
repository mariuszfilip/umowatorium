<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use My\Dhtmlx\Menu;
use Zend\Http\Header;
use Zend\Http\Request;



class IndexController extends AbstractActionController
{
    protected $dbAdapter;

    public function setDbAdapter($db)
    {
        $this->dbAdapter = $db;
    }

    public function indexAction() {

        if ( $this->getRequest()->isXmlHttpRequest() ) {
            $sm = $this->getServiceLocator();
            $oFormModel = $sm->get('FormsElementTable');
            $aFormModel = $oFormModel->getPolaFormularza(1);
            $oForm = new \My\Dhtmlx\Form();
            $xml = $oForm->getXMLForm($aFormModel);
            $this->getResponse()->getHeaders()->addHeaders(array('Content-type' => 'text/xml'));
            return $this->getResponse()->setContent($xml);
        }
    }

    public function menuAction(){

        $sm = $this->getServiceLocator();
        $oFormModel = $sm->get('UserTable');

        $oUser = $oFormModel->getUser(1);
        echo $oUser->getName();
        exit();
        if ( $this->getRequest()->isXmlHttpRequest() ) {
            $aMenu = array(array('id'=>1,'text'=>'Admin','nazwa'=>'Admin','modul'=>'application','akcja'=>'menu','kontroller'=>'test'));
            $oMenu = new Menu($aMenu,'');
            $xml = $oMenu->createXml();

            $this->getResponse()->getHeaders()->addHeaders(array('Content-type' => 'text/xml'));
            return $this->getResponse()->setContent($xml);
        }
        return false;
    }

    public function submenuAction(){
        if ( $this->getRequest()->isXmlHttpRequest() ) {
            $aMenu = array(array('id'=>1,'text'=>'Admin','nazwa'=>'Admin','modul'=>'application','akcja'=>'menu','kontroller'=>'test'));
            $oMenu = new \My\Dhtmlx\Toolbar($aMenu);
            $xml = $oMenu->createXml();

            $this->getResponse()->getHeaders()->addHeaders(array('Content-type' => 'text/xml'));
            return $this->getResponse()->setContent($xml);
        }
        return false;

    }

    public function testAction(){
        echo 'asd';
    }
}
