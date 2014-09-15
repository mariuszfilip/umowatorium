<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Mariusz
 * Date: 18.02.14
 * Time: 18:21
 * To change this template use File | Settings | File Templates.
 */
namespace My\Tools;
class ObjMethodList{

    public static function getArrayMethodList($obj){
        $class_methods = get_class_methods($obj);
        $aMethodList = array();
        foreach ($class_methods as $method_name) {
            $aMethodList[]=$method_name;
        }
        return $aMethodList;
    }
    public static function writeMethodList($obj){
        $class_methods = get_class_methods($obj);
        foreach ($class_methods as $method_name) {
            echo $method_name.PHP_EOL;
        }
    }

}