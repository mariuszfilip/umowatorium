<?php

namespace Import\Model;

use Zend\Db\Adapter\Adapter;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Paginator\Paginator;

use Import\Model\AbstractTable;

class ContainerView extends AbstractTable
{
    protected $table ='v_container_list';

    protected $aColumnList = array('location_short','isf','port','sign','supplier_name','broker_name','eta','receive_date','arrival_notice','original_document','material_paid','id_ocean_freight_paid','id');


    public function __construct(Adapter $adapter){
        $this->adapter = $adapter;
        $this->initialize();
    }

    public function createWhere(\Zend\Session\Container $container){

        $this->createWhereDefault($container);
        if($container->offsetExists('loc_id')){
            if($container->offsetGet('loc_id') != ''){
                $this->oWhere->in('id_location',explode(',',$container->offsetGet('loc_id')));
            }
        }

        $keyword = $container->offsetGet('keyword');
        if($keyword != ''){
            $this->oWhere->like('sign','%'.$keyword.'%');
        }
    }

    public function getColumnViewList(){
        return $this->aColumnList;
    }


}