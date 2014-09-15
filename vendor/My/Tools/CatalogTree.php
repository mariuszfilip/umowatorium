<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Mariusz
 * Date: 10.02.14
 * Time: 09:51
 * To change this template use File | Settings | File Templates.
 */
class My_Tools_CatalogTree{


    public static function getDateCatalogTree($sPath, $data = false, $czyWyswietlacBledy = false)
    {
        $data = $data ? substr($data, 0, 10) : date('Y-m-d');
        $data = explode('-', $data);

        $oldUmask = umask(0);
        if (!file_exists($sPath . $data[0] . '/')) {
            if($czyWyswietlacBledy){
                mkdir($sPath . $data[0] . '/', 0770);
            }else{
                @mkdir($sPath . $data[0] . '/', 0770);
            }
        }

        if (!file_exists($sPath . $data[0] . '/' . $data[1] . '/')) {
            if($czyWyswietlacBledy){
                mkdir($sPath . $data[0] . '/' . $data[1] . '/', 0770);
            }else{
                @mkdir($sPath . $data[0] . '/' . $data[1] . '/', 0770);
            }
        }

        if (!file_exists($sPath . $data[0] . '/' . $data[1] . '/' . $data[2] . '/')) {
            if($czyWyswietlacBledy){
                mkdir($sPath . $data[0] . '/' . $data[1] . '/' . $data[2] . '/', 0770);
            }else{
                @mkdir($sPath . $data[0] . '/' . $data[1] . '/' . $data[2] . '/', 0770);
            }
        }
        umask($oldUmask);

        return $sPath . $data[0] . '/' . $data[1] . '/' . $data[2] . '/';
    }
}