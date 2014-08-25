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
use \My\Dhtmlx\Grid;
use \My\Dhtmlx\Form;


class ImporteditController extends AbstractActionController
{

    public function indexAction(){
    }

    public function editAction(){
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
            $xml = $oForm->getXMLForm($aFormModel);
            $this->getResponse()->getHeaders()->addHeaders(array('Content-type' => 'text/xml'));
            return $this->getResponse()->setContent($xml);
        }
    }

}


