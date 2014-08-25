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

use \My\Dhtmlx\Form;

class SupplierController extends CrudController
{

    public function __construct(){
        $this->sNameServiceReadTable = 'SupplierView';
        $this->sNameServiceCreateUpdateTable = 'SupplierTable';
        $this->sSessionNameSpace = 'supplier_search';
        $this->iIdForm = 7;
        $this->bPaginator = true;
    }

    public function searchAction(){
        if ( $this->getRequest()->isXmlHttpRequest() ) {
            $request = $this->getRequest();
            $sm = $this->getServiceLocator();
            $oFormModel = $sm->get('FormsElementTable');
            $aFormModel = $oFormModel->getPolaFormularza(8);
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

}


