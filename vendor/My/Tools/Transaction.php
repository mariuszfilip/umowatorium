<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Mariusz
 * Date: 25.02.14
 * Time: 15:08
 * To change this template use File | Settings | File Templates.
 */
namespace My\Tools;
class Transaction{


    public static function getUniqueId()
    {
        return md5(uniqid(rand(), true) . uniqid(rand(), true));
    }
}