<?php

namespace Import\Model;

use Zend\Db\Adapter\Adapter;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Paginator\Paginator;

use Import\Model\AbstractTable;

class SupplierView extends AbstractTable
{
    protected $table ='v_supplier_list';

    protected $aColumnList = array('name','address','town','country','state','zip','phone','fax','cell','description','contact','is_quary','id');

    protected $sort;

    public function __construct(Adapter $adapter){
        $this->adapter = $adapter;
        $this->initialize();
    }


    public function createWhere(\Zend\Session\Container $container){

        $this->createWhereDefault($container);
    }


}