<?php
/**
 * @desc Generuje array z danymi słownikowymi
 */
namespace My\Tools;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Where;



class Lista
{

    /**
     * @desc Metoda zwraca liste rekordów z kolumna id i nazwa dla tabeli podanej w parametrze. Jeżeli jest pole status to zwraca tylko te z status=1
     * @param $sTabelaSlownikowa
     * @param $intBaza nazwa bazy do ktorej ma się odnosic lista
     * @return array
     */
    public static function ListaOgraniczoneKolumny($nazwaSlownika,$aCols){

        $projectTable = new TableGateway($nazwaSlownika, \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter());
        $projectTable->getColumns();
        $select = new \Zend\Db\Sql\Select();
        if(!empty($aCols)){
            $select->columns($aCols);
        }
        $aKolumny = \My\Tools\Lista::KolumnyNazwy($nazwaSlownika);
        if(in_array('is_active',$aKolumny)){
            $select->where('id_active=1');
        }
        if(in_array('deleted',$aKolumny)){
            $select->where('deleted=0');
        }

        $select->from($nazwaSlownika);
        $rowset = $projectTable->selectWith($select);

        if($rowset){
            return $rowset->toArray();
        }
        return array();
    }

	/**
	 * @desc Metoda zwraca liste rekordów z kolumna id i nazwa dla tabeli podanej w parametrze. Jeżeli jest pole status to zwraca tylko te z status=1
	 * @param $sTabelaSlownikowa
	 * @param $intBaza nazwa bazy do ktorej ma się odnosic lista
	 * @return array
	 */
    public static function Lista($nazwaSlownika){

        $aKolumny = \My\Tools\Lista::KolumnyNazwy($nazwaSlownika);
        $aWhere = array();
        if(in_array('is_active',$aKolumny)){
            $aWhere['is_active']=1;
        }
        if(in_array('deleted',$aKolumny)){
            $aWhere['deleted']=0;
        }
        if(in_array('del',$aKolumny)){
            $aWhere['del']=0;
        }

        $projectTable = new TableGateway($nazwaSlownika, \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter());
        $rowset = $projectTable->select($aWhere);
        if($rowset){
            return $rowset->toArray();
        }
        return array();
    }
	/**
	 * @desc Metoda zwraca liste rekordów z kolumna id i nazwa dla tabeli podanej w parametrze. Jeżeli jest pole status to zwraca tylko te z status=1
	 * @param $sTabelaSlownikowa
	 * @param $aIds array
	 * @param $intBaza nazwa bazy do ktorej ma się odnosic lista
	 * @return array
	 */
    public static function ListaIn($nazwaSlownika,$aIds=array(),$aCols=array()){
        $projectTable = new TableGateway($nazwaSlownika, \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter());
        $projectTable->getColumns();
        $select = new \Zend\Db\Sql\Select();
        if(!empty($aCols)){
            $select->columns($aCols);
        }
        $select->from($nazwaSlownika);
        $select->in(implode(',',$aIds));
        $rowset = $projectTable->selectWith($select);

        if($rowset){
            return $rowset;
        }
        return array();
    }

    /**
     * @desc Zwraca wszystkie rekordy z tabeli
     * @param $sTabelaSlownikowa
     * @return array
     */
    public static function ListaWszystkieKolumny($nazwaSlownika){
        $projectTable = new TableGateway($nazwaSlownika, \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter());
        $rows = $projectTable->select();
        if($rows){
            return $rows->toArray();
        }
        return array();
    }


    public static function ListaWszystkieKolumnyLimit($nazwaSlownika, $limit = null,$offset = null){
        $aKolumny = \My\Tools\Lista::KolumnyNazwy($nazwaSlownika);
        $select = new \Zend\Db\Sql\Select();
        $select->from($nazwaSlownika);
        if(in_array('deleted',$aKolumny)){
            $select->where('deleted=0');
        }
        if(in_array('is_active',$aKolumny)){
            $select->where('is_active=1');
        }
        if(!is_null($limit) && $limit > 0){
            $select->limit((int)$limit);
        }
        if(!is_null($offset) && $offset >= 0){
            $select->offset((int)$offset);
        }

        $projectTable = new TableGateway($nazwaSlownika, \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter());

        $rowset = $projectTable->selectWith($select);

        if($rowset){
            return $rowset->toArray();
        }
        return array();
    }

    /**
     * @desc Zwraca rekord dla tabeli i id podanych w parametrach
     * @param $idSlownika
     * @param $iId
     * @return array
     */
    public static function ListaWszystkieKolumnyWartosc($nazwaSlownika,$iId){
        $projectTable = new TableGateway($nazwaSlownika, \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter());
        $where = new Where();
        $rows = $projectTable->select();

        if($rows){
            return $rows;
        }
        return array();
    }

    /**
     * @desc Zwraca informacje o kolummach tabeli - typ , długośc , nazwa
     * @param $sTabelaSlownikowa
     * @return mixed
     */
    public static function Kolumny($nazwaSlownika){
        //$projectTable = new TableGateway($nazwaSlownika, \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter(),new \Zend\Db\TableGateway\Feature\MetadataFeature());
        $metadata = new \Zend\Db\Metadata\Metadata(\Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter());
        $table = $metadata->getTable($nazwaSlownika);
        $aColumns = $table->getColumns();
        return $aColumns;
    }

    /**
     * @desc Zwraca tylko nazwy kolumn danej tabeli
     * @param $sTabelaSlownikowa
     * @return array
     */
    public static function KolumnyNazwy($nazwaSlownika){
        $metadata = new \Zend\Db\Metadata\Metadata(\Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter());
        $table = $metadata->getTable($nazwaSlownika);
        $aColumns = $table->getColumns();

        foreach($aColumns as $oColumn){
            $aColumnsName[]=$oColumn->getName();
        }
        return $aColumnsName;
    }

    /**
     * @desc Metoda zamienia array w uporządkowna tablice gdzie kluczem jest kolumna podana w parametrze
     * @param $tab
     * @param $id
     * @return array
     */
    public static function Slownik($tab, $id){
        /*
         * zmiana tabeli na array[$id]=rows;
         *
         */
        $out = array();
        if(is_array($tab) && count($tab)>0){
            foreach($tab as $val){
                $out[$val[$id]] = $val;
            }
        }
        return $out;

    }

    /**
     * @desc Zwraca true,false na podstawie wartosc z funkcji
     * @param $nr
     * @return bool
     */
    public static function Logiczna($nr){
        if($nr==0) return false;
        else if($nr==1) return true;
    }


    /**
     * @param $nr
     * @return bool
     */
    public static function IsTrue($nr){
        if($nr>0) return true;
        else return false;
    }


    /**
     * @desc Zapisuje do bazy dane do tabeli podanej w parametrze oraz array z kluczami i wartoscami
     * @param $sTabelaSlownikowa
     * @param $aDataInsert
     * @return bool
     */
    public static function Insert($nazwaSlownika, $aDataInsert){
        $projectTable = new TableGateway($nazwaSlownika, \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter());
        $numRows = $projectTable->insert($aDataInsert);
        if($numRows){
            return $numRows;
        }
        return false;
    }

    /**
     * @desc Aktualizuje dane do tabeli podanej w parametrze oraz array z kluczami i wartoscami
     * @param $sTabelaSlownikowa
     * @param $aDataInsert
     * @param $iID
     * @return bool
     */
    public static function Update($nazwaSlownika, $aDataInsert,$iID){
        $projectTable = new TableGateway($nazwaSlownika, \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter());
        $update = $projectTable->update($aDataInsert,'id='.$iID);
        if($update){
            return $update;
        }
        return false;
    }

    public static function IdWhere($nazwaSlownika,$kolumna,$wartosc){
        $projectTable = new TableGateway($nazwaSlownika, \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter());
        //$oWhere = new Zend\Db\Sql\Where();
        //$oWhere->
        $row = false;

        if($row){
            return $row['id'];
        }
        return array();
    }

    public static function ListaWhere($nazwaSlownika,$kolumna,$wartosc){
        $projectTable = new TableGateway($nazwaSlownika, \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter());
        $select = new \Zend\Db\Sql\Select($nazwaSlownika);
        $where = new  Where();
        $where->equalTo($kolumna, $wartosc) ;
        $select->where($where);
        $rowset = $projectTable->selectWith($select);
        if($rowset){
            return $rowset->current();
        }
        return array();
    }
}
