<?php

namespace Import\Model;

use Zend\Db\Adapter\Adapter;
use \Import\Model\AbstractTable;

class SlabTable extends AbstractTable
{
    protected $table ='slab';

    public function __construct(Adapter $adapter){
        $this->adapter = $adapter;
        $this->initialize();
        $this->bHistorySave = true;
        $this->sHistoryTableName = 'container_history';
    }

    public function createWhere(\Zend\Session\Container $container){

        $oWhere = $this->createWhereDefault($container);
        return $oWhere;
    }


}