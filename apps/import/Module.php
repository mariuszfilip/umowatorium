<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Import;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use My\Dhtmlx\Models\FormElements;
use Import\Model\ContainerTable;
use Import\Model\ContainerView;
use Import\Model\ContainerHistoryTable;

use Import\Model\StoneView;
use Import\Model\StoneTable;

use Import\Model\SupplierTable;
use Import\Model\SupplierView;

use Import\Model\SlabTable;
use Import\Model\SlabView;

use Import\Model\ContainerlogTable;
use Import\Model\ContainerlogView;

use Zend\Db\ResultSet\ResultSet;


use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;


use Zend\Authentication\Storage\Session;

class Module
{
    protected $whitelist = array('auth');

    public function onBootstrap(MvcEvent $e)
    {

        $eventManager = $e->getApplication()->getEventManager();


        // dzieki temu mozemy ustawic trigger na okreslony obiekt klasy
        $sharedEventManager = $eventManager->getSharedManager();

        $sharedEventManager->attach('Import\Model\AbstractTable', 'saveHistoryChange', function($e) {
            $params = json_encode($e->getParams());

            $aNoweDane = (is_array($params['new'])?$params['new']:array());
            $aStareDane = (is_array($params['old'])?$params['old']:array());
            $iId = $params['id'];
            $auth = Zend_Auth::getInstance()->getStorage()->read();
            $iIdUzytkownika = isset($auth['user_id'])?$auth['user_id']:0;
            $aPomijaneKolumny = array();
            foreach($aNoweDane as $sNazwaKolumny => $sNowaWartosc){
                if(!is_integer($sNazwaKolumny)){
                    if(!in_array($sNazwaKolumny, $aPomijaneKolumny)){
                        $sStaraWartosc = isset($aStareDane[$sNazwaKolumny]) ? $aStareDane[$sNazwaKolumny] : '';
                        if($sNowaWartosc != $sStaraWartosc){
                            $aData = array();
                            if(empty($aStareDane)){
                                $aData['operacja']='I';
                            }else{
                                $aData['operacja']='U';
                            }
                            $aData['tabela']=$this->_name;
                            $aData['kolumna']=$sNazwaKolumny;
                            $aData['nowedane']=$sNowaWartosc;
                            $aData['staredane']=$aStareDane[$sNazwaKolumny];
                            $aData['id_uzytkownika']=$iIdUzytkownika;
                            $aData['row_id']=$iId;
                            $aData['data_dodania']=$oData->format('Y-m-d H:i:s');
                            My_Tools_Lista::Insert($this->_name_history_table, $aData);

                        }
                    }
                }
            }
            return true;
        });

    }


    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'FormsElementTable' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table     = new FormElements($dbAdapter);
                    return $table;
                },
                'ContainerTable' => function ($sm) {
                    $dbAdapter= $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new ContainerTable($dbAdapter);
                    return $table;
                },
                'ContainerView' => function ($sm) {
                    $dbAdapter= $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new ContainerView($dbAdapter);
                    return $table;
                },
                'ContainerDrawingTable' => function ($sm) {
                    $dbAdapter= $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new \Import\Model\ContainerdrawingTable($dbAdapter);
                    return $table;
                },
                'StoneView' => function ($sm) {
                    $dbAdapter= $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new StoneView($dbAdapter);
                    return $table;
                },
                'StoneTable' => function ($sm) {
                    $dbAdapter= $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new StoneTable($dbAdapter);
                    return $table;
                },
                'SlabView' => function ($sm) {
                    $dbAdapter= $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new SlabView($dbAdapter);
                    return $table;
                },
                'SlabTable' => function ($sm) {
                    $dbAdapter= $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new SlabTable($dbAdapter);
                    return $table;
                },
                'SlabDrawingTable' => function ($sm) {
                    $dbAdapter= $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new \Import\Model\SlabDrawingTable($dbAdapter);
                    return $table;
                },
                'ContainerLogTable' => function ($sm) {
                    $dbAdapter= $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new ContainerlogTable($dbAdapter);
                    return $table;
                },
                'ContainerLogView' => function ($sm) {
                    $dbAdapter= $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new ContainerlogView($dbAdapter);
                    return $table;
                },
                'Auth\Model\MyAuthStorage' =>  function($sm) {
                    return new \Zend\Authentication\Storage\Session('someNamespace');
                },
                'AuthService' => function ($sm) {
                    $dbAdapter           = $sm->get('Zend\Db\Adapter\Adapter');
                    $dbTableAuthAdapter  = new DbTableAuthAdapter($dbAdapter,
                        'user','login','password', 'is_active = 1');

                    $authService = new AuthenticationService();
                    $authService->setAdapter($dbTableAuthAdapter);
                    $authService->setStorage($sm->get('Auth\Model\MyAuthStorage'));

                    return $authService;
                },
                'SupplierView' => function ($sm) {
                    $dbAdapter= $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new SupplierView($dbAdapter);
                    return $table;
                },
                'SupplierTable' => function ($sm) {
                    $dbAdapter= $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new SupplierTable($dbAdapter);
                    return $table;
                },
                'ContainerHistoryTable' => function ($sm) {
                    $dbAdapter= $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new ContainerHistoryTable($dbAdapter);
                    return $table;
                },
            ),
        );
    }

}
