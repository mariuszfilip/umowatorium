<?php

namespace Import\Model;


use Zend\Db\Adapter\Adapter;
use \Import\Model\AbstractTable;

class ContainerlogTable extends AbstractTable
{
    protected $table ='container_log';

    protected $aColumnList = array('id','description','cr_date','cr_user');

    public function __construct(Adapter $adapter){
        $this->adapter = $adapter;
        $this->initialize();
    }
    public function createWhere(\Zend\Session\Container $container){

        $oWhere = $this->createWhereDefault($container);
        return $oWhere;
    }



}