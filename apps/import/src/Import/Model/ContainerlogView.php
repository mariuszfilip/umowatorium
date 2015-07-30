<?php

namespace Import\Model;

use Zend\Db\Adapter\Adapter;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

use Import\Model\AbstractTable;

class ContainerlogView extends AbstractTable
{
    protected $table ='v_container_log_list';

    protected $aColumnList = array('description','cr_date','user_name','id');

    protected $sort;

    public function __construct(Adapter $adapter){
        $this->adapter = $adapter;
        $this->initialize();
    }


    public function createWhere(\Zend\Session\Container $container){

        $this->createWhereDefault($container);
    }


}