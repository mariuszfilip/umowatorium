<?php

namespace Import\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Paginator\Paginator;

use Zend\Paginator\Adapter\DbSelect;


class AbstractTable extends AbstractTableGateway
{
    protected $sort;

    protected $oWhere;

    protected $bHistorySave = false;

    protected $sHistoryTableName = '';

    protected $eventManager;

    public function clearArray($aData)
    {
        $aDataReturn = array();
        $oColumns = $this->getColumnList();
        foreach($oColumns as $oColumn){

            if(isset($aData[$oColumn->getName()])){
                if(!$oColumn->isNullable()){
                    if(is_null($aData[$oColumn->getName()])){
                        $aDataReturn[$oColumn->getName()] = 0;
                        continue;
                    }
                }else{
                    if($aData[$oColumn->getName()] == ''){
                        $aData[$oColumn->getName()] = null;
                    }
                }
                $aDataReturn[$oColumn->getName()]=$aData[$oColumn->getName()];
            }else{
                if(!$oColumn->isNullable()){
                    $aDataReturn[$oColumn->getName()] = 0;
                }else{
                    if($aData[$oColumn->getName()] == ''){
                        $aData[$oColumn->getName()] = null;
                    }
                }
            }
        }
        unset($aDataReturn['id']);
        return $aDataReturn;
    }

    protected function insertData($aData)
    {
        $aClearData = $this->clearArray($aData);
        $id = $this->insert($aClearData);
        if($this->bHistorySave){
            $this->saveHistory($aClearData, array(), $this->getLastInsertValue());
            //$this->getEventManager()->trigger('saveHistoryChange', null, array('new' => $aData,'id'=>$id));
        }
        return $id;
    }

    protected function updateData($aData,$where)
    {
        $aClearData = $this->clearArray($aData);
        if($this->bHistorySave){
            $aDataOld = $this->getRowWhere($where);
            $this->saveHistory($aClearData, $aDataOld, $aDataOld['id']);
            //$this->getEventManager()->trigger('saveHistoryChange', null, array('new' => $aData,'id'=>$id));
        }
        return $this->update($aClearData,$where);
    }

    public function getRow($id)
    {
        $id  = (int) $id;
        $rowset = $this->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function getRowWhere($sWhere)
    {
        $rowset = $this->select(
            function (Select $select) use ($sWhere){
                $select->where($sWhere);
            }
        );
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find ");
        }
        return $row;
    }

    public function createWhereDefault(\Zend\Session\Container $container)
    {

        $aColumnList = $this->getColumnList();
        $this->oWhere = new \Zend\Db\Sql\Where();
        foreach($aColumnList as $oColumn){
            if($container->offsetExists($oColumn->getName())){
                $value= $container->offsetGet($oColumn->getName());
                if($oColumn->getDataType() == 'int' && $value != ''){
                    $this->oWhere->equalTo($oColumn->getName(),(int)$value);
                }else{
                    if($value != ''){
                        $this->oWhere->like($oColumn->getName(),'%'.$value.'%');
                    }
                }
            }
        }
    }

    public function createOrder($ind = 0, $dir)
    {
        if ($dir == "des") {
            $dir = "desc";
        }
        if(isset($this->aColumnList[$ind])){
            $this->sort = $this->aColumnList[$ind] . " " . $dir;
            return true;
        }
        return false;
    }

    public function getColumnList()
    {
        $metadata = new \Zend\Db\Metadata\Metadata($this->adapter);
        $table = $metadata->getTable($this->getTable());
        $aColumnsList = $table->getColumns();

        return $aColumnsList;
    }

    public function save($aData , $id = 0)
    {
        $id = (int)$id;
        if ($id == 0) {
            $this->insertData($aData);
            $id = $this->lastInsertValue;
        } else {
            if ($this->getRow($id)) {
                $this->updateData($aData,'id='.$id);
            } else {
                throw new \Exception('Row id does not exist');
            }
        }
        return $id;
    }

    public function fetchAllPaginator()
    {

        $select = new Select($this->table);
        $select->where($this->oWhere);
        if($this->sort != ''){
            $select->order($this->sort);
        }

        // isf na eta
        // stone_list z material_price
        $select->columns($this->aColumnList);
        $resultSetPrototype = new ResultSet();
        $paginatorAdapter = new DbSelect(
        // our configured select object
            $select,
            // the adapter to run it against
            $this->adapter,
            // the result set to hydrate
            $resultSetPrototype
        );
        $paginator = new Paginator($paginatorAdapter);
        return $paginator;

    }

    public function getColumnsArray()
    {
        $aColumns = array();
        $metadata = new \Zend\Db\Metadata\Metadata($this->adapter);
        $table = $metadata->getTable($this->getTable());
        $aColumnsList = $table->getColumns();

        foreach( $aColumnsList as $oColumn){
            $aColumns[$oColumn->getName()]='';
        }

        return $aColumns;
    }

    protected function saveHistory($aNewValues, $aOldValues, $iId)
    {
        $iIdUzytkownika = 1;
        $aPomijaneKolumny = array('cr_date','cr_user');
        foreach($aNewValues as $sNazwaKolumny => $sNowaWartosc){
            if(!is_integer($sNazwaKolumny)){
                if(!in_array($sNazwaKolumny, $aPomijaneKolumny)){
                    $sStaraWartosc = isset($aOldValues[$sNazwaKolumny]) ? $aOldValues[$sNazwaKolumny] : '';
                    if($sNowaWartosc != $sStaraWartosc){
                        $aData = array();
                        if(empty($aStareDane)){
                            $aData['operation']='I';
                        }else{
                            $aData['operation']='U';
                        }
                        $aData['table_name']=$this->getTable();
                        $aData['column_name']=$sNazwaKolumny;
                        $aData['new_value']=$sNowaWartosc;
                        $aData['old_value']=$aOldValues[$sNazwaKolumny];
                        $aData['id_user']=$iIdUzytkownika;
                        $aData['row_id']=$iId;
                        $aData['cr_Date']=new \Zend\Db\Sql\Expression("getdate()");
                        \My\Tools\Lista::Insert($this->sHistoryTableName, $aData);

                    }
                }
            }
        }
    }

    public function deleteRow($id){
        $aClearData = array('deleted'=>1);
        return $this->update($aClearData,'id='.(int)$id);
    }

    public function getAuthService()
    {
        $sm  = $this->getServiceLocator();
        $auth = $sm->get('AuthService');
        return $auth;
    }


}