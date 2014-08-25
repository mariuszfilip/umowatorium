<?php

namespace Import\Model;

use Zend\Db\Adapter\Adapter;
use \Import\Model\AbstractTable;
use Zend\Db\Sql\Select;

class ContainerHistoryTable extends AbstractTable
{
    protected $table ='container_history';


    public function __construct(Adapter $adapter){
        $this->adapter = $adapter;
        $this->initialize();
    }

    public function getHistory($sTableName, $row_id){
        $oWhere = new \Zend\Db\Sql\Where();
        $oWhere->equalTo('table_name',$sTableName);
        $oWhere->equalTo('row_id',$row_id);

        $rowset = $this->select(
            function (Select $select) use ($oWhere){
                $select->where($oWhere);
            }
        );
        return $rowset->toArray();
    }
}