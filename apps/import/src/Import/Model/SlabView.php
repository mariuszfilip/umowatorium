<?php

namespace Import\Model;

use Zend\Db\Adapter\Adapter;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Paginator\Paginator;

use Import\Model\AbstractTable;

class SlabView extends AbstractTable
{
    protected $table ='v_slab_list';

    protected $aColumnList = array('name','stone_type_name','thickness','slab_price','slab_nbr','sqft_price','width','length','id');

    protected $sort;

    public function __construct(Adapter $adapter){
        $this->adapter = $adapter;
        $this->initialize();
    }


    public function createWhere(\Zend\Session\Container $container){

        $this->createWhereDefault($container);
    }


}