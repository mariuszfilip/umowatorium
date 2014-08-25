<?php

namespace Application\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use \Application\Model\UserEntity;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;

class UserTable extends AbstractTableGateway{
    protected $table ='uzytkownicy_ewidencja';

    public function __construct(Adapter $adapter){
        $this->adapter = $adapter;
        $resultSet = new ResultSet(); // Zend\Db\ResultSet\ResultSet
        $resultSet->setArrayObjectPrototype(new UserEntity());
        $this->resultSetPrototype = $resultSet;
        $this->initialize();
    }


    public function getUser($id)
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