<?php

/**
 * @desc Budowa xml do formularzy z zachowaniem struktury dhtmlx
 */
namespace My\Dhtmlx;
use My\Dhtmlx\Models\FormElements;

class FormTest
{
    protected $id_formularza = 0;

    protected $oDom;
    protected $styldoedycji = "-moz-border-radius: 2px;
								text-align: left;
								padding:1px 1px 1px 3px !important;
								margin:3px 0px 2px 2px !important;
								-moz-box-shadow: 1px 1px 1px #A4BED4;
								height: 16pt;
								color:#225796;
								text-transform: none;
								vertical-align: middle;";

    protected $styldoedycji_template = "-moz-border-radius: 2px;
								text-align: left;
								padding:1px 1px 1px 3px !important;
								margin:3px 0px 2px 2px !important;
								-moz-box-shadow: 1px 1px 1px #A4BED4;
								color:#225796;
								text-transform: none;
								vertical-align: middle;";

    protected $styldoedycji_txbox = "-moz-border-radius: 2px;
								text-align: left;
								padding:1px 1px 1px 3px !important;
								margin:3px 0px 2px 2px !important;
								-moz-box-shadow: 1px 1px 1px #A4BED4;
								height: 40pt;
								color:#225796;
								text-transform: none;
								vertical-align: middle;";

    protected $styldoedycji2 = "-moz-border-radius: 2px;
								text-align: left;
								padding:1px 1px 1px 3px !important;
								margin:3px 0px 2px 2px !important;
								-moz-box-shadow: 1px 1px 1px #A4BED4;
								height: 16pt;
								color:#225796;
								text-transform: none;
								vertical-align: middle;";
    protected $styldoedycji3 = "-moz-border-radius: 2px;
								text-align: left;
								padding:1px 1px 1px 3px !important;
								margin:3px 0px 2px 2px !important;
								-moz-box-shadow: 1px 1px 1px #A4BED4;
								color:#225796;
								 text-transform: none;
								 vertical-align: middle;";

    public $nazwaFormularza = '';
    public $width = '500';
    public $submit = 'Save';
    public $id_form = 'Zapisz';
    public $czy_podpowiedzi = true;
    protected $iDbDefault = 1;
    protected $bPokazPrzyciskZapisz = true;
    protected $error;
    protected $oFormElementTable;
    protected $aForm = array();


    public function __construct($aForm)
    {
        $this->aForm = $aForm;
        $this->oDom = new \DOMDocument('1.0', 'utf-8');
    }


    /**
     * @param $bPokazPrzyciskZapisz
     * @desc pokazuje przycisk zapisz nawet jesli nie jest nigdzie zdenifniowant
     */
    public function setPokazPrzyciskZapisz($bPokazPrzyciskZapisz)
    {
        $this->bPokazPrzyciskZapisz = $bPokazPrzyciskZapisz;
    }


    /**
     * @param $nazwa
     * @desc Ustawia nazwe formularza - opcjonalne
     */
    public function setNazwaFormularza($nazwa)
    {
        $this->nazwaFormularza = $nazwa;
    }


    /**
     * @param $width
     * @desc szerokosc formularza
     */
    public function setSzerokoscFormularza($width)
    {
        $this->width = $width;
    }


    /**
     * @param $sNazwa
     * @desc ustawa nazwe domyslnego przycisku zapisu
     */
    public function setSubmitFormularza($sNazwa){
        $this->submit = $sNazwa;
    }



    /**
     * @param $id_formularz
     * @param $aDane
     * @return bool
     * @desc metoda zwraca wynik czy form przeszedl walidacje
     */
    public function isValid($aDane){
        return $this->validate($aDane);
    }



    /**
     * @param $id_formularz
     * @param $aDane
     * @return bool
     * @throws Exception
     */
    protected function validate($aDane)
    {
        $aForm  = array();
        foreach($this->aForm as $aValue){
            $aForm[$aValue['nazwa_pola']]=$aValue;
        }
        $czyPrawidlowyFormularz = true;
        $Errors = array();

        //zapis wszystkich funkcji itextów walidacji pól fromularza
        foreach($aForm as $pole) {
            if(!is_null($pole['walidacja_serwer_klasa'])){
                $aFunkcjeWalidacji[$pole['nazwa_pola']]['walidacja_serwer_klasa']=$pole['walidacja_serwer_klasa'];
                $aFunkcjeWalidacji[$pole['nazwa_pola']]['walidacja_serwer_parametry']=\My\Tools\XmlToArray::convert($pole['walidacja_serwer_parametry']);
            }
        }


        foreach($aDane as $pole => $wartosc) {
            $czyPoprawnePole=null;
            if(isset($aFunkcjeWalidacji[$pole])){
                    $shortClassName = $aFunkcjeWalidacji[$pole]['walidacja_serwer_klasa'];
                    $aParametry = $aFunkcjeWalidacji[$pole]['walidacja_serwer_parametry'];
                    if(!is_null($shortClassName)){
                        $className = '\Zend\Validator\\'.$shortClassName;
                        if(class_exists($className)){
                            $oValidate = new $className($aParametry);
                            if(!$oValidate->isValid($wartosc)){
                                $Errors[$pole]['column']=$aForm[$pole]['etykieta'];
                                $Errors[$pole]['messages']=implode(', ',$oValidate->getMessages());
                                $czyPrawidlowyFormularz = false;
                            }
                        }
                    }

            }
        }
        $this->error = $Errors;
        return $czyPrawidlowyFormularz;
    }


    /**
     * @param string $format
     * @return string
     * @description Zwraca info o błędach przy zapisie
     */
    public function getError($format='string')
    {
        /**
         * json to string
         * */
        if($format=='sjson'){
            return \My\Tools\Json::arrayToJsonString($this->error);
        }
        /**
         * array()
         * */
        if($format=='array'){
            return $this->error;
        }

        if($format=='string'){
            $sReturn = '';
            foreach($this->error as $aError){
                $sReturn .= $aError['column'].' <b>:</b> '.$aError['messages'].'<br/>';;
            }
            return $sReturn;
        }
    }
    /**
     * @param $nazwaPola
     * @param $aDane
     * @param $aSearch
     * @desc ustawia w formularzu dane w selecie , dane są wstrzykiwane
     * */

    public function setSelectOption($nazwaPola, $aDane, $aSearch)
    {

        foreach($aSearch as $key => $val) {
            if($val['nazwa_pola'] == $nazwaPola) {
                $this->aForm[$key]['column'] = $nazwaPola;
                $this->aForm[$key]['tabela_slownikowa'] = $aDane;
            }
        }
        return true;
    }

    /**
     * Date: 13.01.14
     * Time: 10:13
     * @param $nazwaPola
     * @param $aDane
     * @param $aSearch
     * @return array
     */
    public function setRadioOption($nazwaPola, $aDane, $aSearch)
    {
        foreach($aSearch as $key => $val) {
            if($val['nazwa_pola'] == $nazwaPola) {
                $this->aForm[$key]['column'] = $nazwaPola;
                $this->aForm[$key]['tabela_slownikowa'] = $aDane;
            }
        }
        return true;
    }


    /**
     * @param array $aData
     * @param array $aDataValues
     * @return string
     * @desc zwraca kompletnego xml dhtmlx form
     */
    public function getXMLForm($aDataValues = array())
    {
        $Items = $this->oDom->createElement('items');

        $itemSetting = $this->oDom->createElement('item');
        $itemSetting->setAttribute('type', 'settings');
        $itemSetting->setAttribute('position', 'label-left');
        $itemSetting->setAttribute('labelAlign', 'right');
        $Items->appendChild($itemSetting);

        $aDataPosortowane = array();

        if(is_array($this->aForm) && count($this->aForm) > 0) {

            foreach($this->aForm as $key =>  $val) {
                if($val['id_nadrzednego'] != 0){
                    $aDataPosortowane[$val['id_nadrzednego']][]=$val;
                    unset($this->aForm[$key]);
                }
            }

            foreach($this->aForm as $key => $val) {
                if($val['id_nadrzednego'] == 0){
                    $itemNew = $this->setItem($val, $aDataValues);
                    if(is_array($itemNew)){
                        foreach($itemNew as $item){
                            $Items->appendChild($item);
                        }
                    }else{
                        $Items->appendChild($itemNew);
                    }
                    $this->dodajElementPodrzedny($itemNew,$val,$aDataPosortowane,$aDataValues);
                }

            }
        }
        if($this->bPokazPrzyciskZapisz) {
            $itemSaveButton = $this->oDom->createElement('item');
            $itemSaveButton->setAttribute('type', 'button');
            $itemSaveButton->setAttribute('name', 'save');
            $itemSaveButton->setAttribute('offsetLeft', $this->width - 150);
            $itemSaveButton->setAttribute('width', '150');
            $itemSaveButton->setAttribute('value', $this->submit);
            $Items->appendChild($itemSaveButton);
        }
        $this->oDom->appendChild($Items);


        return $this->oDom->saveXML();
    }

    /**
     * @param $itemNew
     * @param $val
     * @param $aDataPosortowane
     * @param $aDataValues
     * @return mixed
     */
    private function dodajElementPodrzedny($itemNew,$val,$aDataPosortowane,$aDataValues){
        if(isset($aDataPosortowane[$val['id']]) && is_array($aDataPosortowane[$val['id']])){
            foreach($aDataPosortowane[$val['id']] as $valPodrzedne) {
                $iItemPod = $this->setItem($valPodrzedne, $aDataValues);
                if(is_array($iItemPod)){
                    foreach($iItemPod as $itemPodrzendy){
                        $itemNew->appendChild($itemPodrzendy);
                    }
                }else{
                    $itemPodObj =$itemNew->appendChild($iItemPod);
                }
                if($valPodrzedne['id_nadrzednego'] != 0){
                    $this->dodajElementPodrzedny($itemPodObj,$valPodrzedne,$aDataPosortowane,$aDataValues);
                }
            }
        }
        return $itemNew;
    }

    private function setItem($vector, $dane, $def_widthpola = 200, $def_widthlabel = 150)
    {
        if(isset($dane[$vector["nazwa_pola"]]) && $dane[$vector["nazwa_pola"]] == '') {
            $dane[$vector["nazwa_pola"]] = trim($vector["wartosc_domyslna"]);
        }

        if($vector["szerokosc_pola"] == 0) {
            $width_pola = $def_widthpola;
        } else {
            $width_pola = $vector["szerokosc_pola"];
        }
        if($vector["szerokosc_etykiety"] == 0) {
            $width_label = $def_widthlabel;
        } else {
            $width_label = $vector["szerokosc_etykiety"];
        }
        $styl = $this->styldoedycji;
        $itemXml = $this->oDom->createElement('item');
        if($vector["margin_gora"] > 0) {
            $vector["margin_gora"] = $vector["margin_gora"] + 7;
        }
        $labelleft = $vector["margin_lewy"] - $width_label - 5;
        $labeltop = $vector["margin_gora"] + 5;
        switch($vector["typ_pola"]) {

            case 1: //input
                $itemXml->setAttribute('type', 'input');
                $itemXml->setAttribute('required', $vector["czy_wymagane"]);
                $itemXml->setAttribute('name', $vector["nazwa_pola"]);
                if(isset($dane[$vector["nazwa_pola"]])){
                    $itemXml->setAttribute('value', htmlspecialchars($dane[$vector["nazwa_pola"]]));
                }
                $itemXml->setAttribute('label', $vector["etykieta"]);
                $itemXml->setAttribute('labelWidth', $width_label);
                $itemXml->setAttribute('inputWidth', $width_pola);
                $itemXml->setAttribute('validate', $vector["walidacja"]);
                $itemXml->setAttribute('style', $styl);
                $itemXml->setAttribute('className', $vector["class_css"]);
                if($vector["margin_lewy"] > 0 && $vector["margin_gora"] > 0) {
                    $itemXml->setAttribute('position', 'absolute');
                    $itemXml->setAttribute('inputTop', $vector["margin_gora"]);
                    $itemXml->setAttribute('inputLeft', $vector["margin_lewy"]);
                    $itemXml->setAttribute('labelLeft', $labelleft);
                    $itemXml->setAttribute('labelTop', $labeltop);
                }

                break;

            case 2:

                if($vector["czy_wymagane"] == 1) {
                    $itemXml->setAttribute('required', 'true');
                    $itemXml->setAttribute('validate', $vector["walidacja"]);
                }
                $itemXml->setAttribute('type', 'select');
                $itemXml->setAttribute('required', $vector["czy_wymagane"]);
                $itemXml->setAttribute('name', $vector["nazwa_pola"]);
                //	            $itemXml->setAttribute('value',htmlspecialchars($dane[$vector["nazwa_pola"]]));
                $itemXml->setAttribute('label', $vector["etykieta"]);
                $itemXml->setAttribute('labelWidth', $width_label);
                $itemXml->setAttribute('inputWidth', $width_pola);
                $itemXml->setAttribute('style', $styl);
                $itemXml->setAttribute('className', $vector["class_css"]);
                if($vector["margin_lewy"] > 0 && $vector["margin_gora"] > 0) {
                    $itemXml->setAttribute('position', 'absolute');
                    $itemXml->setAttribute('inputTop', $vector["margin_gora"]);
                    $itemXml->setAttribute('inputLeft', $vector["margin_lewy"]);
                    $itemXml->setAttribute('labelLeft', $labelleft);
                    $itemXml->setAttribute('labelTop', $labeltop);
                }
                $select_data = array();
                if(trim($vector["tabela_slownikowa"]) != '' && !is_array($vector["tabela_slownikowa"])) {
                    $select_data = \My\Tools\Lista::Lista($vector["tabela_slownikowa"]);
                }
                $select_data_insert = '';
                if(is_array($vector["tabela_slownikowa"])) {
                    $select_data_insert = $vector["tabela_slownikowa"];
                }
                /*
                 * dodaje arraya do wstrzykniecia
                 * */
                $option = $this->oDom->createElement('option');
                $option->setAttribute('text', 'Select');
                $option->setAttribute('value', '');
                $itemXml->appendChild($option);
                if(is_array($select_data_insert) && count($select_data_insert) > 0) {
                    foreach($select_data_insert as $key => $val) {
                        $option = $this->oDom->createElement('option');
                        $option->setAttribute('value', $val["id"]);
                        //$option->setAttribute('text', htmlspecialchars($val["nazwa"]));
                        $option->setAttribute('text', $val["nazwa"]);
                        if(isset($dane[$vector['column']]) && $dane[$vector['column']] == $val["id"]) {
                            $option->setAttribute('selected', 'true');
                        }
                        $itemXml->appendChild($option);
                    }
                }
                $itemXml->appendChild($option);
                if(is_array($select_data) && count($select_data) > 0) {
                    foreach($select_data as $val) {
                        $option = $this->oDom->createElement('option');
                        $option->setAttribute('value', $val["id"]);

                        //$option->setAttribute('text', htmlspecialchars($val["name"]));
                        $option->setAttribute('text', $val["name"]);
                        if(isset($dane[$vector["nazwa_pola"]]) && $val["id"] == $dane[$vector["nazwa_pola"]]) {
                            $option->setAttribute('selected', 'true');
                        }
                        $itemXml->appendChild($option);
                    }
                }


                break;

            case 3:
                $itemXml->setAttribute('type', 'calendar');
                $itemXml->setAttribute('required', $vector["czy_wymagane"]);

                $itemXml->setAttribute('className', $vector["class_css"]);
                $itemXml->setAttribute('dateFormat', '%Y-%m-%d');
                $itemXml->setAttribute('name', $vector["nazwa_pola"]);
                if(isset($dane[$vector["nazwa_pola"]]) && $dane[$vector["nazwa_pola"]] != 0){
                    $itemXml->setAttribute('value', htmlspecialchars($dane[$vector["nazwa_pola"]]));
                }
                $itemXml->setAttribute('label', $vector["etykieta"]);
                $itemXml->setAttribute('labelWidth', $width_label);
                $itemXml->setAttribute('inputWidth', $width_pola);
                $itemXml->setAttribute('validate', $vector["walidacja"]);
                $itemXml->setAttribute('style', $styl);
                if($vector["margin_lewy"] > 0 && $vector["margin_gora"] > 0) {
                    $itemXml->setAttribute('position', 'absolute');
                    $itemXml->setAttribute('inputTop', $vector["margin_gora"]);
                    $itemXml->setAttribute('inputLeft', $vector["margin_lewy"]);
                    $itemXml->setAttribute('labelLeft', $labelleft);
                    $itemXml->setAttribute('labelTop', $labeltop);
                }
                break;

            case 4: //textarea
                $itemXml->setAttribute('type', 'input');
                $itemXml->setAttribute('rows', '3');
                $itemXml->setAttribute('required', $vector["czy_wymagane"]);

                $itemXml->setAttribute('className', $vector["class_css"]);
                $itemXml->setAttribute('name', $vector["nazwa_pola"]);
                $itemXml->setAttribute('value', htmlspecialchars($dane[$vector["nazwa_pola"]]));
                $itemXml->setAttribute('label', $vector["etykieta"]);
                $itemXml->setAttribute('labelWidth', $width_label);
                $itemXml->setAttribute('inputWidth', $width_pola);
                $itemXml->setAttribute('validate', $vector["walidacja"]);
                $styl = $this->styldoedycji_txbox;

                $itemXml->setAttribute('style', $styl);

                if($vector["margin_lewy"] > 0 && $vector["margin_gora"] > 0) {
                    $itemXml->setAttribute('position', 'absolute');
                    $itemXml->setAttribute('inputTop', $vector["margin_gora"]);
                    $itemXml->setAttribute('inputLeft', $vector["margin_lewy"]);
                    $itemXml->setAttribute('labelLeft', $labelleft);
                    $itemXml->setAttribute('labelTop', $labeltop);
                }
                //                $styl = $this->styldoedycji_txbox;
                //                $danexml.='<item type="input" rows="3" name="'.$vector["nazwa_pola"].'" required="'.$vector["czy_wymagane"].'"  value="'.$dane[$vector["nazwa_pola"]].'" label="'.$vector["etykieta"].':" inputWidth="'.$width_pola.'"  labelWidth="'.$width_label.'" validate="'.$vector["walidacja"].'"  style="'.$styl.'"/>';

                break;

            case 5:
                $styl = $this->styldoodczytu;

                $itemXml->setAttribute('type', 'input');
                $itemXml->setAttribute('readonly', 'true');
                $itemXml->setAttribute('required', $vector["czy_wymagane"]);

                $itemXml->setAttribute('className', $vector["class_css"]);
                $itemXml->setAttribute('name', $vector["nazwa_pola"]);
                $itemXml->setAttribute('value', htmlspecialchars($dane[$vector["nazwa_pola"]]));
                $itemXml->setAttribute('label', $vector["etykieta"]);
                $itemXml->setAttribute('labelWidth', $width_label);
                $itemXml->setAttribute('inputWidth', $width_pola);
                $itemXml->setAttribute('validate', $vector["walidacja"]);
                $itemXml->setAttribute('style', $styl);
                if($vector["margin_lewy"] > 0 && $vector["margin_gora"] > 0) {
                    $itemXml->setAttribute('position', 'absolute');
                    $itemXml->setAttribute('inputTop', $vector["margin_gora"]);
                    $itemXml->setAttribute('inputLeft', $vector["margin_lewy"]);
                    $itemXml->setAttribute('labelLeft', $labelleft);
                    $itemXml->setAttribute('labelTop', $labeltop);
                }
                break;

            case 6:

                $command = "";
                $itemXml->setAttribute('type', 'button');
                //$itemXml->setAttribute('offsetLeft', $vector["margin_lewy"]);
                $itemXml->setAttribute('name', $vector["nazwa_pola"]);
                $itemXml->setAttribute('value', $vector["etykieta"]);
                $itemXml->setAttribute('label', $vector["etykieta"]);
                $itemXml->setAttribute('labelWidth', $width_label);
                $itemXml->setAttribute('inputWidth', $width_pola);
                $itemXml->setAttribute('validate', $vector["walidacja"]);
                $itemXml->setAttribute('style', $styl);
                $itemXml->setAttribute('className', $vector["class_css"]);
                if($vector["margin_lewy"] > 0) {
                    $itemXml->setAttribute('position', 'absolute');
                    $itemXml->setAttribute('inputTop', $vector["margin_gora"]);
                    $itemXml->setAttribute('inputLeft', $vector["margin_lewy"]);
                    $itemXml->setAttribute('labelLeft', $labelleft);
                    $itemXml->setAttribute('labelTop', $labeltop);
                }
                break;

            case 7:
                $itemXml->setAttribute('type', 'checkbox');
                $itemXml->setAttribute('required', $vector["czy_wymagane"]);

                $itemXml->setAttribute('name', $vector["nazwa_pola"]);
                $itemXml->setAttribute('value', 1);
                $itemXml->setAttribute('label', $vector["etykieta"]);


                if($dane['wartosc_domyslna']!=''){
                    $vector["wartosc_domyslna"]=$dane['wartosc_domyslna'];
                }
                $itemXml->setAttribute('checked', \My\Tools\Lista::Logiczna($vector["wartosc_domyslna"]));

                $itemXml->setAttribute('labelWidth', $width_label);
                $itemXml->setAttribute('inputWidth', $width_pola);
                $itemXml->setAttribute('validate', $vector["walidacja"]);
                $itemXml->setAttribute('style', $styl);
                $itemXml->setAttribute('className', $vector["class_css"]);
                if($vector["margin_lewy"] > 0 && $vector["margin_gora"] > 0) {
                    $itemXml->setAttribute('position', 'absolute');
                    $itemXml->setAttribute('inputTop', $vector["margin_gora"]);
                    $itemXml->setAttribute('inputLeft', $vector["margin_lewy"]);
                    $itemXml->setAttribute('labelLeft', $labelleft);
                    $itemXml->setAttribute('labelTop', $labeltop);
                }

                break;

            case 8:
                $itemXml->setAttribute('type', 'hidden');

                $itemXml->setAttribute('name', $vector["nazwa_pola"]);
                $itemXml->setAttribute('value', htmlspecialchars($dane[$vector["nazwa_pola"]]));
                $itemXml->setAttribute('label', $vector["etykieta"]);
                $itemXml->setAttribute('labelWidth', $width_label);
                $itemXml->setAttribute('inputWidth', $width_pola);
                $itemXml->setAttribute('validate', $vector["walidacja"]);
                $itemXml->setAttribute('style', $styl);
                $itemXml->setAttribute('className', $vector["class_css"]);
                if($vector["margin_lewy"] > 0 && $vector["margin_gora"] > 0) {
                    $itemXml->setAttribute('position', 'absolute');
                    $itemXml->setAttribute('inputTop', $vector["margin_gora"]);
                    $itemXml->setAttribute('inputLeft', $vector["margin_lewy"]);
                    $itemXml->setAttribute('labelLeft', $labelleft);
                    $itemXml->setAttribute('labelTop', $labeltop);
                }
                $command = '';
                // $danexml.='<item type="hidden"  offsetLeft="'.$vector["margin_lewy"].'" name="'.$vector["nazwa_pola"].'" value="'.$vector["etykieta"].'" width="'.$width_pola.'" '.$command.' style="'.$styl.'"/>';

                break;

            case 9:
                $itemXml->setAttribute('type', 'file');

                $itemXml->setAttribute('name', $vector["nazwa_pola"]);
                $itemXml->setAttribute('value', htmlspecialchars($dane[$vector["nazwa_pola"]]));
                $itemXml->setAttribute('label', $vector["etykieta"]);
                $itemXml->setAttribute('labelWidth', $width_label);
                $itemXml->setAttribute('inputWidth', $width_pola);
                $itemXml->setAttribute('validate', $vector["walidacja"]);
                $itemXml->setAttribute('style', $styl);
                $itemXml->setAttribute('className', $vector["class_css"]);
                if($vector["margin_lewy"] > 0 && $vector["margin_gora"] > 0) {
                    $itemXml->setAttribute('position', 'absolute');
                    $itemXml->setAttribute('inputTop', $vector["margin_gora"]);
                    $itemXml->setAttribute('inputLeft', $vector["margin_lewy"]);
                    $itemXml->setAttribute('labelLeft', $labelleft);
                    $itemXml->setAttribute('labelTop', $labeltop);
                }
                $command = '';
                // $danexml.='<item type="hidden"  offsetLeft="'.$vector["margin_lewy"].'" name="'.$vector["nazwa_pola"].'" value="'.$vector["etykieta"].'" width="'.$width_pola.'" '.$command.' style="'.$styl.'"/>';

                break;

            case 10:
                $itemXml->setAttribute('type', 'template');
                $itemXml->setAttribute('name', htmlspecialchars($vector["nazwa_pola"]));

                //$itemXml->setAttribute('value', htmlspecialchars($dane[$vector["nazwa_pola"]]));
                if(isset($dane[$vector["nazwa_pola"]])){
                    $itemXml->setAttribute('value', $dane[$vector["nazwa_pola"]]);
                }
                $itemXml->setAttribute('label', $vector["etykieta"]);
                $itemXml->setAttribute('labelWidth', $width_label);
                $itemXml->setAttribute('inputWidth', $width_pola);
                $itemXml->setAttribute('className', $vector["class_css"]);
                //inputHeight
                $itemXml->setAttribute('style', $this->styldoedycji_template);
                if($vector["margin_lewy"] > 0 && $vector["margin_gora"] > 0) {
                    $itemXml->setAttribute('position', 'absolute');
                    $itemXml->setAttribute('inputTop', $vector["margin_gora"]);
                    $itemXml->setAttribute('inputLeft', $vector["margin_lewy"]);
                    $itemXml->setAttribute('labelLeft', $labelleft);
                    $itemXml->setAttribute('labelTop', $labeltop);
                }
                $command = '';
                // $danexml.='<item type="hidden"  offsetLeft="'.$vector["margin_lewy"].'" name="'.$vector["nazwa_pola"].'" value="'.$vector["etykieta"].'" width="'.$width_pola.'" '.$command.' style="'.$styl.'"/>';

                break;

            case 12:
                $itemXml->setAttribute('type', 'block');

                $itemXml->setAttribute('name', $vector["nazwa_pola"]);
                $itemXml->setAttribute('width', $width_pola);
                // $itemXml->setAttribute('validate', $vector["walidacja"]);
                $itemXml->setAttribute('className', $vector["class_css"]);
                if($vector["margin_lewy"] > 0 && $vector["margin_gora"] > 0) {
                    $itemXml->setAttribute('position', 'absolute');
                    $itemXml->setAttribute('inputTop', $vector["margin_gora"]);
                    $itemXml->setAttribute('inputLeft', $vector["margin_lewy"]);
                    $itemXml->setAttribute('labelLeft', $labelleft);
                    $itemXml->setAttribute('labelTop', $labeltop);
                }


                break;

            case 13:
                $itemXml->setAttribute('type', 'newcolumn');
                $itemXml->setAttribute('name', $vector["nazwa_pola"]);
                if($vector["margin_lewy"] > 0 && $vector["margin_gora"] > 0) {
                    $itemXml->setAttribute('position', 'absolute');
                    $itemXml->setAttribute('inputTop', $vector["margin_gora"]);
                    $itemXml->setAttribute('inputLeft', $vector["margin_lewy"]);
                    $itemXml->setAttribute('labelLeft', $labelleft);
                    $itemXml->setAttribute('labelTop', $labeltop);
                }
                $command = '';
                // $danexml.='<item type="hidden"  offsetLeft="'.$vector["margin_lewy"].'" name="'.$vector["nazwa_pola"].'" value="'.$vector["etykieta"].'" width="'.$width_pola.'" '.$command.' style="'.$styl.'"/>';
                break;

            case 14:
                unset($itemXml);
                $aItem = array();
                $select_data = array();
                if(trim($vector["tabela_slownikowa"]) != '' && !is_array($vector["tabela_slownikowa"])) {
                    if(!isset($vector["tabela_slownikowa_baza"])) {
                        $vector["tabela_slownikowa_baza"] = $this->iDbDefault;
                    }

                    $select_data = \My\Tools\Lista::Lista($vector["tabela_slownikowa"]);
                }
                $select_data_insert = '';
                if(is_array($vector["tabela_slownikowa"])) {
                    $select_data_insert = $vector["tabela_slownikowa"];
                }
                $count = count($select_data_insert);
                $iIloscKolumn = $vector["ilosc_kolumn"];
                $ile = ceil($count/$iIloscKolumn);

                /*
                 * wstrzykiwanie danych
                 * */

                if(is_array($select_data_insert) && count($select_data_insert) > 0) {
                    $i = 0;
                    foreach($select_data_insert as $key => $val) {
                        $i++;
                        $itemXml = $this->oDom->createElement('item');
                        $itemXml->setAttribute('className', $vector["class_css"]);
                        if($vector["czy_wymagane"] == 1) {
                            $itemXml->setAttribute('required', 'true');
                        }else{
                            $itemXml->setAttribute('required', 'false');
                        }
                        if($vector["walidacja"] != ''){
                            $itemXml->setAttribute('validate', $vector["walidacja"]);
                        }
                        $itemXml->setAttribute('type', 'radio');

                        $itemXml->setAttribute('name', $vector["nazwa_pola"]);
                        $itemXml->setAttribute('label', $vector["etykieta"]);
                        $itemXml->setAttribute('labelWidth', $width_label);
                        $itemXml->setAttribute('labelAlign', 'left');
                        $itemXml->setAttribute('position', 'label-right');

                        $itemXml->setAttribute('inputWidth', $width_pola);
                        $itemXml->setAttribute('style', $styl);
                        if($vector["margin_lewy"] > 0 && $vector["margin_gora"] > 0) {
                            $itemXml->setAttribute('position', 'absolute');
                            $itemXml->setAttribute('inputTop', $vector["margin_gora"]);
                            $itemXml->setAttribute('inputLeft', $vector["margin_lewy"]);
                            $itemXml->setAttribute('labelLeft', $labelleft);
                            $itemXml->setAttribute('labelTop', $labeltop);
                        }
                        $itemXml->setAttribute('value', $val["id"]);

                        $itemXml->setAttribute('label', htmlspecialchars($val["name"]));

                        if(isset($dane[$vector['column']]) && $dane[$vector['column']] == $val["id"]) {
                            $itemXml->setAttribute('checked', 'true');
                        }
                        $aItem[]=$itemXml;
                        if($i == $ile){
                            $itemXml2 = $this->oDom->createElement('item');
                            $itemXml2->setAttribute('type', 'newcolumn');
                            $aItem[]=$itemXml2;
                            $i=0;
                        }
                    }
                }
                $count = count($select_data);
                $iIloscKolumn = $vector["ilosc_kolumn"];
                $ile = ceil($count/$iIloscKolumn);

                if(is_array($select_data) && count($select_data) > 0) {
                    $i = 0;
                    foreach($select_data as $val) {
                        $i++;

                        $itemXml = $this->oDom->createElement('item');
                        $itemXml->setAttribute('type', 'radio');

                        $itemXml->setAttribute('className', $vector["class_css"]);
                        if($vector["czy_wymagane"] == 1) {
                            $itemXml->setAttribute('required', 'true');
                        }else{
                            $itemXml->setAttribute('required', 'false');
                        }
                        if($vector["walidacja"] != ''){
                            $itemXml->setAttribute('validate', $vector["walidacja"]);
                        }else{
                            $itemXml->setAttribute('validate',"");
                        }
                        $itemXml->setAttribute('name', $vector["nazwa_pola"]);
                        $itemXml->setAttribute('label', $vector["etykieta"]);
                        $itemXml->setAttribute('labelWidth', $width_label);
                        $itemXml->setAttribute('inputWidth', $width_pola);
                        $itemXml->setAttribute('labelAlign', 'left');
                        $itemXml->setAttribute('position', 'label-right');
                        $itemXml->setAttribute('style', $styl);
                        if($vector["margin_lewy"] > 0 && $vector["margin_gora"] > 0) {
                            $itemXml->setAttribute('position', 'absolute');
                            $itemXml->setAttribute('inputTop', $vector["margin_gora"]);
                            $itemXml->setAttribute('inputLeft', $vector["margin_lewy"]);
                            $itemXml->setAttribute('labelLeft', $labelleft);
                            $itemXml->setAttribute('labelTop', $labeltop);
                        }
                        $itemXml->setAttribute('value', $val["id"]);

                        $itemXml->setAttribute('label', htmlspecialchars($val["name"]));

                        if(isset($dane[$vector["nazwa_pola"]]) && $val["id"] == $dane[$vector["nazwa_pola"]]) {
                            $itemXml->setAttribute('checked', 'true');
                        }
                        $aItem[]=$itemXml;
                        if($i == $ile){
                            $itemXml2 = $this->oDom->createElement('item');
                            $itemXml2->setAttribute('type', 'newcolumn');
                            $aItem[]=$itemXml2;
                            $i=0;
                        }
                    }
                }
                $itemXml = $aItem;

                break;

            case 15:
                $itemXml->setAttribute('type', 'label');
                $itemXml->setAttribute('name', $vector["nazwa_pola"]);
                $itemXml->setAttribute('width', $width_pola);
                $itemXml->setAttribute('validate', $vector["walidacja"]);
                $itemXml->setAttribute('label', $vector["etykieta"]);
                $itemXml->setAttribute('style', $styl);
                $itemXml->setAttribute('className', $vector["class_css"]);
                if($vector["margin_lewy"] > 0 && $vector["margin_gora"] > 0) {
                    $itemXml->setAttribute('position', 'absolute');
                    $itemXml->setAttribute('inputTop', $vector["margin_gora"]);
                    $itemXml->setAttribute('inputLeft', $vector["margin_lewy"]);
                    $itemXml->setAttribute('labelLeft', $labelleft);
                    $itemXml->setAttribute('labelTop', $labeltop);
                }
                $command = '';
                // $danexml.='<item type="hidden"  offsetLeft="'.$vector["margin_lewy"].'" name="'.$vector["nazwa_pola"].'" value="'.$vector["etykieta"].'" width="'.$width_pola.'" '.$command.' style="'.$styl.'"/>';

                break;

            case 16:
                $itemXml->setAttribute('type', 'container');
                $itemXml->setAttribute('name', $vector["nazwa_pola"]);
                $itemXml->setAttribute('width', $width_pola);
                $itemXml->setAttribute('validate', $vector["walidacja"]);
                $itemXml->setAttribute('style', $styl);
                $itemXml->setAttribute('className', $vector["class_css"]);
                if($vector["margin_lewy"] > 0 && $vector["margin_gora"] > 0) {
                    $itemXml->setAttribute('position', 'absolute');
                    $itemXml->setAttribute('inputTop', $vector["margin_gora"]);
                    $itemXml->setAttribute('inputLeft', $vector["margin_lewy"]);
                    $itemXml->setAttribute('labelLeft', $labelleft);
                    $itemXml->setAttribute('labelTop', $labeltop);
                }
                $command = '';
                // $danexml.='<item type="hidden"  offsetLeft="'.$vector["margin_lewy"].'" name="'.$vector["nazwa_pola"].'" value="'.$vector["etykieta"].'" width="'.$width_pola.'" '.$command.' style="'.$styl.'"/>';

                break;
            case 17:
                $itemXml->setAttribute('type', 'upload');
                $itemXml->setAttribute('name', $vector["nazwa_pola"]);
                $itemXml->setAttribute('width', $width_pola);
                $itemXml->setAttribute('validate', $vector["walidacja"]);
                $itemXml->setAttribute('style', $styl);
                $itemXml->setAttribute('className', $vector["class_css"]);
                $itemXml->setAttribute('autoStart',$vector["autostartupload"]);

                //$itemXml->setAttribute('url', "");

                if($vector["margin_lewy"] > 0 && $vector["margin_gora"] > 0) {
                    $itemXml->setAttribute('position', 'absolute');
                    $itemXml->setAttribute('inputTop', $vector["margin_gora"]);
                    $itemXml->setAttribute('inputLeft', $vector["margin_lewy"]);
                    $itemXml->setAttribute('labelLeft', $labelleft);
                    $itemXml->setAttribute('labelTop', $labeltop);
                }
                $command = '';
                // $danexml.='<item type="hidden"  offsetLeft="'.$vector["margin_lewy"].'" name="'.$vector["nazwa_pola"].'" value="'.$vector["etykieta"].'" width="'.$width_pola.'" '.$command.' style="'.$styl.'"/>';

                break;

            case 18:
                $itemXml->setAttribute('type', 'fieldset');

                $itemXml->setAttribute('name', $vector["nazwa_pola"]);
                $itemXml->setAttribute('label', $vector["etykieta"]);
                $itemXml->setAttribute('width', $width_pola);
                $itemXml->setAttribute('style', $styl);
                $itemXml->setAttribute('className', $vector["class_css"]);
                if($vector["margin_lewy"] > 0 && $vector["margin_gora"] > 0) {
                    $itemXml->setAttribute('position', 'absolute');
                    $itemXml->setAttribute('inputTop', $vector["margin_gora"]);
                    $itemXml->setAttribute('inputLeft', $vector["margin_lewy"]);
                    $itemXml->setAttribute('labelLeft', $labelleft);
                    $itemXml->setAttribute('labelTop', $labeltop);
                }
                break;
            case 19: //input with checkbox window
                $itemXml->setAttribute('type', 'input');
                $itemXml->setAttribute('required', $vector["czy_wymagane"]);
                $itemXml->setAttribute('name', $vector["nazwa_pola"]);
                if(isset($dane[$vector["nazwa_pola"]])){
                    $itemXml->setAttribute('value', htmlspecialchars($dane[$vector["nazwa_pola"]]));
                }
                $itemXml->setAttribute('label', $vector["etykieta"]);
                $itemXml->setAttribute('labelWidth', $width_label);
                $itemXml->setAttribute('inputWidth', $width_pola);
                $itemXml->setAttribute('validate', $vector["walidacja"]);
                $itemXml->setAttribute('style', $styl);
                $itemXml->setAttribute('className', $vector["class_css"]);
                if($vector["margin_lewy"] > 0 && $vector["margin_gora"] > 0) {
                    $itemXml->setAttribute('position', 'absolute');
                    $itemXml->setAttribute('inputTop', $vector["margin_gora"]);
                    $itemXml->setAttribute('inputLeft', $vector["margin_lewy"]);
                    $itemXml->setAttribute('labelLeft', $labelleft);
                    $itemXml->setAttribute('labelTop', $labeltop);
                }

                break;
            case 20: //combo
                if($vector["czy_wymagane"] == 1) {
                    $itemXml->setAttribute('required', 'true');
                    $itemXml->setAttribute('validate', $vector["walidacja"]);
                }
                $itemXml->setAttribute('type', 'combo');
                $itemXml->setAttribute('required', $vector["czy_wymagane"]);
                $itemXml->setAttribute('name', $vector["nazwa_pola"]);
                $itemXml->setAttribute('label', $vector["etykieta"]);
                $itemXml->setAttribute('labelWidth', $width_label);
                $itemXml->setAttribute('inputWidth', $width_pola);
                $itemXml->setAttribute('style', $styl);
                $itemXml->setAttribute('autocomplete', 'on');
                $itemXml->setAttribute('className', $vector["class_css"]);
                if($vector["margin_lewy"] > 0 && $vector["margin_gora"] > 0) {
                    $itemXml->setAttribute('position', 'absolute');
                    $itemXml->setAttribute('inputTop', $vector["margin_gora"]);
                    $itemXml->setAttribute('inputLeft', $vector["margin_lewy"]);
                    $itemXml->setAttribute('labelLeft', $labelleft);
                    $itemXml->setAttribute('labelTop', $labeltop);
                }
                $select_data = array();
                if(trim($vector["tabela_slownikowa"]) != '' && !is_array($vector["tabela_slownikowa"])) {
                    $select_data = \My\Tools\Lista::Lista($vector["tabela_slownikowa"]);
                }
                $select_data_insert = '';
                if(is_array($vector["tabela_slownikowa"])) {
                    $select_data_insert = $vector["tabela_slownikowa"];
                }
                /*
                 * dodaje arraya do wstrzykniecia
                 * */
                $option = $this->oDom->createElement('option');
                $option->setAttribute('text', '');
                $option->setAttribute('value', '');
                $itemXml->appendChild($option);
                if(is_array($select_data_insert) && count($select_data_insert) > 0) {
                    foreach($select_data_insert as $key => $val) {
                        $option = $this->oDom->createElement('option');
                        $option->setAttribute('value', $val["id"]);
                        $option->setAttribute('text', htmlspecialchars($val["nazwa"]));
                        if(isset($dane[$vector['column']]) && $dane[$vector['column']] == $val["id"]) {
                            $option->setAttribute('selected', 'true');
                        }
                        $itemXml->appendChild($option);
                    }
                }
                $itemXml->appendChild($option);
                if(is_array($select_data) && count($select_data) > 0) {
                    foreach($select_data as $val) {
                        $option = $this->oDom->createElement('option');
                        $option->setAttribute('value', $val["id"]);

                        $option->setAttribute('text', htmlspecialchars($val["nazwa"]));

                        if(isset($dane[$vector["nazwa_pola"]]) && $val["id"] == $dane[$vector["nazwa_pola"]]) {
                            $option->setAttribute('selected', 'true');
                        }
                        $itemXml->appendChild($option);
                    }
                }


                break;
        }
        if(!is_array($itemXml) && $this->czy_podpowiedzi && $vector["podpowiedz"] != '') {
            $note = $this->oDom->createElement('note', $vector["podpowiedz"]);
            $itemXml->appendChild($note);
        }
        return $itemXml;
    }

    /**
     *
     * ustawia lub nie wersję edukacyjna
     * @param $czy_podpowiedzi
     */
    public function setCzyPodpowiedzi($czy_podpowiedzi)
    {
        $this->czy_podpowiedzi = $czy_podpowiedzi;
    }

    /**
     * @return string
     * @desc pobiera style dla wszystkich elementow forma
     */
    public function getStyle()
    {
        return $this->styldoedycji;
    }


}

?>