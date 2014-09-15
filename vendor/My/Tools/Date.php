<?php
namespace My\Tools;
class Date
{

    public static function odejmijDaty($dDataRozpoczenia,$dDataKonca){
        $dStart = new DateTime(date("Y-m-d",strtotime($dDataRozpoczenia)));
        $dEnd = new DateTime(date("Y-m-d",strtotime($dDataKonca)));
        return $dStart->diff($dEnd)->days;
    }

}

?>