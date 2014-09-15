<?php
/**
 * @desc Generuje xml z gridem wg parametrów wejsciowych
 */

namespace My\Dhtmlx;
class Grid{

    protected $aData = array();
    protected $position_start = 0;
    protected $position_total = 0;
    protected $oDom;
    protected $id = 'id';
    protected $aHead = array();
    protected $bLiczbaPorzadkowa = false;
    protected $bCzyKopiowacId = false;


    public function __construct($aData = array(),$id = 'id',$userdata='', $aHead=array()){
        if($aData instanceof \Zend\Stdlib\ArrayObject){
            $aData = $aData->toArray();
        }
        $this->aData = $aData;
        $this->oDom = new \DOMDocument('1.0', 'utf-8');
        $this->aHead = $aHead;
        $this->id= $id;
    }

    public function setPagingGrid($start, $total){
        $this->position_start = intval($start);
        $this->position_total = intval($total);
    }

    /**
     * @param $bLiczbaPorzadkowa
     * @desc Ustawia dodaktową kolumnę lp na początku grida
     */
    public function setCzyLiczbaPorzadkowa($bLiczbaPorzadkowa){
        $this->bLiczbaPorzadkowa = $bLiczbaPorzadkowa;
    }

    public function setCzyKopiowacId($bCzyKopiowacId){
        $this->bCzyKopiowacId = $bCzyKopiowacId;
    }
    /**
     * @param $table
     * @return bool
     * @desc Ustawia nagłówki grida jeżeli wcześniej nie zostały zdefiniowane
     */
    private function dhtmlxGridHead($table,$elementRows){
        $elementHead = $this->oDom->createElement('head');
        $elementRows->appendChild($elementHead);
        $count=0;
        foreach($table as $key => $val){
            if($key === 'filter' || $key === 'paging'|| $key === 'footer'){
                continue;
            }
            $count++;
            $cell = $this->oDom->createElement('column',$val["name"]);
            $cell->setAttribute('width',$val["width"]);
            $cell->setAttribute('type',$val["type"]);
            $cell->setAttribute('align',$val["align"]);
            $cell->setAttribute('sort',$val["sort"]);
            if(isset($val["id"])){
                $cell->setAttribute('id',$val["id"]);
            }
            $elementHead->appendChild($cell);
        }
        if(isset($table['paging'])){
            $beforeInit = $this->oDom->createElement('beforeInit');
            $call = $this->oDom->createElement('call');
            $call->setAttribute('command',"attachHeader");

            $cspan = '';
            for($i=0;$i<$count;$i++){
                $cspan .= ',#cspan';
            }
            $param = $this->oDom->createElement('param',$table['paging'].$cspan);
            $call->appendChild($param);
            $beforeInit->appendChild($call);
            $elementHead->appendChild($beforeInit);
        }
        if(isset($table['filter'])){
            $beforeInit = $this->oDom->createElement('beforeInit');
            $call = $this->oDom->createElement('call');
            $call->setAttribute('command',"attachHeader");
            $cspan = '';
            $i=0;
            foreach($table['filter'] as $sFiltr){
                $i++;
                $cspan .= $sFiltr;
                if($count != $i){
                    $cspan .= ',';
                }

            }
            $param = $this->oDom->createElement('param',$cspan);
            $call->appendChild($param);
            $beforeInit->appendChild($call);
            $elementHead->appendChild($beforeInit);
        }
        if(isset($table['footer'])){
            $beforeInit = $this->oDom->createElement('afterInit');
            $call = $this->oDom->createElement('call');
            $call->setAttribute('command',"attachFooter");
            $cspan = '';
            $i=0;
            foreach($table['footer'] as $sFiltr){
                $i++;

                if($i == 1){
                    $cspan .= $sFiltr;
                }else{
                    $cspan .= ','.$sFiltr;
                }

            }
            $param = $this->oDom->createElement('param',$cspan);
            $call->appendChild($param);
            $beforeInit->appendChild($call);
            $elementHead->appendChild($beforeInit);
        }
        return true;
    }

    /*
     * @desc generuje z array xml wg struktury array
     * Array => key => array
     */
    public function createXml(){
        $elementRows = $this->oDom->createElement('rows');
        if($this->position_total>0){
            $elementRows->setAttribute('total_count',$this->position_total);
        }
        $i=$this->position_start;

        if($i == 0){
            $i=1;
        }
        $elementRows->setAttribute('pos',$this->position_start);

        $this->oDom->appendChild($elementRows);
        if($this->aHead && count($this->aHead)>0){
            $this->dhtmlxGridHead($this->aHead,$elementRows);
        }
        if(is_array($this->aData) && !empty($this->aData)){

            foreach($this->aData as $key => $aRow){
                $row = $this->oDom->createElement('row');
                $row->setAttribute($this->id,$aRow[$this->id]);
                $elementRows->appendChild($row);
                if($this->bLiczbaPorzadkowa){
                    $cell = $this->oDom->createElement('cell',$i);
                    $row->appendChild($cell);
                }
                $i++;
                foreach($aRow as $key_cell => $value_cell){
                    if($key_cell !== $this->id || $this->bCzyKopiowacId){
                        if(is_array($value_cell)){
                            $cell = $this->oDom->createElement('cell',$value_cell['value']);
                            unset($value_cell['value']);

                            foreach($value_cell as $key => $attrib){
                                $cell->setAttribute($key,$attrib);
                            }
                        }else{
                            $cell = $this->oDom->createElement('cell',htmlspecialchars($value_cell));
                        }
                        $row->appendChild($cell);
                    }
                }
            }
        }
        return $this->oDom->saveXML();
    }

    /*
     *
     * @return array (size=5)
                  0 =>
                    array (size=3)
                      'id' => string '1' (length=1)
                      'nazwa' => string 'Administrator' (length=13)
                      'status' => string 'Aktywny' (length=7)
                  1 =>
                    array (size=3)
                      'id' => string '5' (length=1)
                      'nazwa' => string 'Sprzedający' (length=12)
                      'status' => string 'Aktywny' (length=7)

     * */
    public static function  polaczFromularzZDanymiDoGrida($aFrom,$aDane){

        $aDaneOut=array();
        foreach($aFrom as $keyForm =>$pole){
            foreach($aDane as $keyDane =>$dane){
                if($pole['nazwa_pola']==$keyDane){
                    $aDaneOut[$keyForm]['id']=$keyForm;
                    $aDaneOut[$keyForm][$keyDane]=array('type'=>'ro','class'=>'form_cell','value'=>$pole['etykieta']);
                    $aDaneOut[$keyForm][$keyForm]=array('type'=>'ro','class'=>'','value'=>$dane);
                }
            }
        }
        return $aDaneOut;

    }


    public static function  polaczFromularzZPelnymDanymiDoGrida($aFrom,$aDane){

        $aDaneOut=array();
        foreach($aFrom as $keyForm =>$pole){
            foreach($aDane as $keyDane =>$dane){
                if($pole['nazwa_pola']==$keyDane){
                    $aDaneOut[$keyForm]['id']=$keyForm;
                    $aDaneOut[$keyForm][$keyDane]=array('type'=>'ro','class'=>'form_cell','value'=>$pole['etykieta']);
                    if($pole['tabela_slownikowa'] != ''){
                        $aData = \My\Tools\Lista::Lista($pole["tabela_slownikowa"]);
                        $aDataSlownik = \My\Tools\Lista::Slownik($aData,'id');
                        $dane = (isset($aDataSlownik[$dane]))?$aDataSlownik[$dane]['name']:$dane;
                    }
                    $aDaneOut[$keyForm][$keyForm]=array('type'=>'ro','class'=>'','value'=>$dane);
                }
            }
        }
        return $aDaneOut;

    }
}