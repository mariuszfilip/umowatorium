<?php

namespace Import\Model;

use Zend\Db\Adapter\Adapter;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Paginator\Paginator;

use Import\Model\AbstractTable;

class StoneView extends AbstractTable
{
    protected $table ='v_stone_list';

    protected $aColumnList = array('name','stone_type_name','waste','severity','pickup_profit','h_price','m_quality','profit','id');

    protected $sort;

    public function __construct(Adapter $adapter){
        $this->adapter = $adapter;
        $this->initialize();
    }


    public function createWhere(\Zend\Session\Container $container){

        $this->createWhereDefault($container);
    }


}