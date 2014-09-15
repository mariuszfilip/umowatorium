<?php

namespace My\Dhtmlx\Models;


use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;


class FormElements extends AbstractTableGateway
{
    protected $table ='formularze_pola';

    public function __construct(Adapter $adapter){
        $this->adapter = $adapter;
        $this->initialize();
    }

    public function getPolaFormularza($iIdForm){
        $iIdForm = intval($iIdForm);
        $where = new  Where();
        $where->equalTo('id_formularza', $iIdForm) ;
        $where->equalTo('status',1);
        $rowset = $this->select(
            function (Select $select) use ($where){
                $select->where($where);
                $select->order('pozycjonowanie ASC');
            }
        );
        return $rowset->toArray();

    }


}