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


class ContainerlogController extends CrudController
{

    public function __construct(){
        $this->sNameServiceReadTable = 'ContainerlogView';
        $this->sNameServiceCreateUpdateTable = 'ContainerLogTable';
        $this->sSessionNameSpace = 'container_log';
        $this->iIdForm = 5;

    }
    public function onDispatch( \Zend\Mvc\MvcEvent $e )
    {
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id_container');
        $container = new \Zend\Session\Container($this->sSessionNameSpace);
        $container->offsetSet('id_container', $id);
        $this->aAdditionalArray = array('id_container'=>$id);
        return parent::onDispatch( $e );
    }

}


