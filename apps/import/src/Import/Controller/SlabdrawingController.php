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
use \My\Dhtmlx\DataView;

use Zend\File\Transfer\Adapter\Http;
use Zend\File\Transfer\Transfer;

use Zend\Validator\File\Size;
use Zend\Validator\File\Count;
use Zend\Validator\File\Extension;

use Zend\View\Model\JsonModel;


class SlabdrawingController extends AbstractActionController
{
    protected $id_slab = 0;

    protected $oContainerDrawingTable;

    public function uploadAction(){
        $id_slab = (int)$this->getEvent()->getRouteMatch()->getParam('id_slab');
        $aReturn = array();
        $aDataInsert = array();
        $sDestination =  ROOT_DIR .'/upload/slab/'.$id_slab.'/';
        (int)$id_user = $this->getAuthService()->getStorage()->read()->id;
        $aDataInsert['id_slab']=$id_slab;
        $aDataInsert['cr_date']=new \Zend\Db\Sql\Expression("getdate()");
        $aDataInsert['cr_user']=$id_user;
        if (!is_dir($sDestination)) {
            if (!@mkdir($sDestination, 0777, true)) {
                throw new \Exception("Unable to create destination: " . $sDestination);
            }
        }
        $size = new Size(array('min' => 10, "max" => 10000000)); //minimum bytes filesize
        $count = new Count(array("min" => 0, "max" => 1));
        $extension = new Extension(array("extension" => array("jpg", "png", "swf")));

        $adapter = new \Zend\File\Transfer\Adapter\Http();
        $file = $adapter->getFileInfo();
        $adapter->setValidators(array($size, $count, $extension), $file['file']['name']);
        $adapter->setDestination($sDestination);

        if ($adapter->isValid()) {

            if ($adapter->isUploaded()) {
                if ($adapter->receive()) {
                    $file = $adapter->getFileInfo();
                    $aDataInsert['photo'] = $file['file']['name'];
                    $aDataInsert['name'] = $file['file']['name'];
                    $this->getContainerDrawingTable()->save($aDataInsert);
                    $aReturn['state']=true;
                }
            } else {
                $aReturn['state']=false;
            }
        } else {
            $dataError = $adapter->getMessages();
            $error = array();
            foreach ($dataError as $key => $row) {
                $error[] = $row;
            }
            $aReturn['state']=false;

        }
        return new JsonModel($aReturn);

    }

    public function uploadedAction(){

        $id_slab = (int)$this->getEvent()->getRouteMatch()->getParam('id_slab');
        $aData = $this->getContainerDrawingTable()->getSlabDrawing($id_slab);
        $out = array();
        if (is_array($aData) && count($aData) > 0) {
            foreach ($aData as $key => $val) {
                $sFile = ROOT_DIR .'/upload/slab/'.$id_slab.'/'. $aData[$key]["photo"];
                if(file_exists($sFile)){
                    $aData[$key]["path"] = myURL .'/upload/slab/'.$id_slab.'/'. $aData[$key]["photo"];
                    //ToDo poprawic
                    $aData[$key]["u_name"]=$val['first_name'].' '.$val['last_name'];
                }else{
                    unset($aData[$key]);
                }

            }
            $out = $aData;
        }
        $oXML = new DataView($out);
        $xml = $oXML->getXml();
        $this->getResponse()->getHeaders()->addHeaders(array('Content-type' => 'text/xml'));
        return $this->getResponse()->setContent($xml);

    }


    public function getContainerDrawingTable(){
        if(!$this->oContainerDrawingTable){
            //ToDo throw instance of AbstractTable
            $sm = $this->getServiceLocator();
            $this->oContainerDrawingTable = $sm->get('SlabDrawingTable');
        }
        return $this->oContainerDrawingTable;
    }

    public function getAuthService(){
        $sm  = $this->getServiceLocator();
        $auth = $sm->get('AuthService');
        return $auth;
    }
}


