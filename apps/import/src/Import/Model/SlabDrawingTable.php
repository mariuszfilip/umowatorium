<?php

namespace Import\Model;


use Zend\Db\Adapter\Adapter;
use \Import\Model\AbstractTable;
use Zend\Db\Sql\Select;

class SlabDrawingTable extends AbstractTable
{
    protected $table ='slab_drawing';

    protected $aColumnList = array('id','description','cr_date','cr_user');

    public function __construct(Adapter $adapter){
        $this->adapter = $adapter;
        $this->initialize();
    }
    public function createWhere(\Zend\Session\Container $container){

        $oWhere = $this->createWhereDefault($container);
        return $oWhere;
    }

    public function getSlabDrawing($iIdContainer){
        $iIdContainer = (int)$iIdContainer;
        $rowset = $this->select(function(Select $select) use ($iIdContainer) {
            $select->join('user', $this->table.'.cr_user = user.id', array('first_name','last_name'));
            $select->where('id_slab= '.$iIdContainer);
        });

        //array('id_container' => $iIdContainer));
        if($rowset){
            return $rowset->toArray();
        }
        return array();
    }



}