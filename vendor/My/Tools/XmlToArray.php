<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Mariusz
 * Date: 25.03.14
 * Time: 22:12
 * To change this template use File | Settings | File Templates.
 */
namespace My\Tools;
class XmlToArray{

    public static function convert($xml,$main_heading = '') {
        $deXml = simplexml_load_string($xml);
        $newArray = array () ;
        $deXml = ( array ) $deXml;
        foreach ( $deXml as $key => $value )
        {
            $value = ( array ) $value ;
            if ( isset ( $value [ 0 ] ) )
            {
                $newArray [ $key ] = trim ( $value [ 0 ] ) ;
            }
            else
            {
                $newArray [ $key ] = XML2Array ( $value , true ) ;
            }
        }
        return $newArray;
    }
}