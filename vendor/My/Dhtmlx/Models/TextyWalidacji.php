<?php

namespace My\Dhtmlx\Models;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;

class TextyWalidacji extends AbstractTableGateway
{
    protected $table ='formularze_texty_walidacji';

    public function __invoke(Adapter $adapter){
        $this->adapter = $adapter;
        $this->initialize();
    }

    public function getPolaFormularza($iIdForm){
        $iIdForm = intval($iIdForm);
        $result = $this->select(array('id' => $iIdForm));
        return $result->toArray();

    }


}