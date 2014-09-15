<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Mariusz
 * Date: 20.01.14
 * Time: 14:28
 * Usuwa nie potrzebne dane przed zapisem do bazy
 */

namespace My\Tools;
class DbPost{

    public static function clear($aPost,$oModel){

        $metadata = $oModel->getAdapter()->describeTable($oModel->getName());
        $aColumm = array_keys($metadata);
        $aResult = array();
        foreach($aPost as $key => $value){
            if(in_array($key, $aColumm)) {
                $aResult[$key]=$value;
            }
        }
        return $aResult;
    }

}