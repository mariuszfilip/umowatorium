<?php

namespace Import\Model;

use Zend\Db\Adapter\Adapter;
use \Import\Model\AbstractTable;

class StoneTable extends AbstractTable
{
    protected $table ='stone';

    public function __construct(Adapter $adapter){
        $this->adapter = $adapter;
        $this->initialize();
    }

    public function createWhere(\Zend\Session\Container $container){

        $oWhere = $this->createWhereDefault($container);
        return $oWhere;
    }


}