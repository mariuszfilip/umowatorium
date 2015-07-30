<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Mariusz
 * Date: 13.02.14
 * Time: 15:40
 * To change this template use File | Settings | File Templates.
 */
namespace My\Dhtmlx;
class Tree{

    public function __construct(){

    }
    public function TreeGridXMLHistory($data, $img,$imgopt,$opis){
        $ac="<?xml version=\"1.0\"?".">";
        $ac.="<rows>";
        if(is_array($data) and count($data)>0)
        {
            foreach($data as $k=>$v)
            {
                $maincategory = $opis." ".$k;
                $ac.='<row id="'.$k.'">';
                $ac.='<cell image="'.$img[1].'">'.'    '.$maincategory.'</cell><cell type="ro"></cell><cell type="ro"></cell>';


                if(is_array($data[$k]) and count($data[$k])>0)
                    foreach($data[$k] as $key=>$val){

                        if($imgopt){
                            $nazwa = explode('--',$key);
                            $txt = "         ".$nazwa[1]."(".$nazwa[2].")";
                            $i =$imgopt[$nazwa[0]];

                        }
                        else
                        {
                            $i = $img[2] ;
                            $txt =$key;
                        }
                        $ac.='<row id="'.$key.'">';
                        $ac.='<cell image="'.$i.'">'.$txt.'</cell><cell type="ro"></cell><cell type="ro"></cell>';
                        foreach($data[$k][$key] as $kk=>$vv){
                            $ac.='<row id="'.$txt.'_'.$k.'_'.$kk.'">';
                            $ac.='<cell image="'.$img[3].'">'.'    '.$kk.'</cell><cell>'.$vv["old_value"].'</cell><cell>'.$vv["new_value"].'</cell>';
                            $ac.='</row>';
                        }
                        $ac.='</row>';
                    }
                $ac.='</row>';
            }
        }
        $ac.="</rows>";
        return $ac;
    }
    public function TreeGridXML($data, $img,$imgopt,$opis){
        $ac="<?xml version=\"1.0\"?".">";
        $ac.="<rows>";
        if(is_array($data) and count($data)>0)
        {
            foreach($data as $k=>$v)
            {
                $maincategory = $opis." ".$k;
                $ac.='<row id="'.$k.'">';
                $ac.='<cell image="'.$img[1].'">'.'    '.$maincategory.'</cell><cell type="ro"></cell><cell type="ro"></cell>';


                if(is_array($data[$k]) and count($data[$k])>0)
                    foreach($data[$k] as $key=>$val){
                        if($imgopt){
                            $nazwa = explode('--',$key);
                            $txt = "         ".$nazwa[1]."(".$nazwa[2].")";
                            $i =$imgopt[$nazwa[0]];

                        }
                        else
                        {
                            $i = $img[2] ;
                            $txt =$key;
                        }
                        $ac.='<row id="'.$key.'">';
                        $ac.='<cell image="'.$i.'">'.$txt.'</cell><cell type="ro"></cell><cell type="ro"></cell>';
                        foreach($data[$k][$key] as $kk=>$vv){
                            $ac.='<row id="'.$txt.'_'.$k.'_'.$kk.'">';
                            $ac.='<cell image="'.$img[3].'">'.'    '.$kk.'</cell><cell>'.$vv["staredane"].'</cell><cell>'.$vv["nowedane"].'</cell>';
                            $ac.='</row>';
                        }



                        $ac.='</row>';
                    }
                $ac.='</row>';
            }
        }
        $ac.="</rows>";
        return $ac;
    }
}