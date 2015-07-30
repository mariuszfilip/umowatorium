<?php

namespace Import\Model;

use Zend\Db\Adapter\Adapter;
use \Import\Model\AbstractTable;

class ContainerTable extends AbstractTable
{
    protected $table ='container';


    public function __construct(Adapter $adapter){
        $this->adapter = $adapter;
        $this->initialize();
        $this->bHistorySave = true;
        $this->sHistoryTableName = 'container_history';
    }

    public function getContainer($id)
    {
        $id  = (int) $id;
        $rowset = $this->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
}