<?php
/**
 * Created by JetBrains PhpStorm.
 * User: admin
 * Date: 14.11.13
 * Time: 19:51
 * To change this template use File | Settings | File Templates.
 */

namespace Auth\Model;
use Zend\Authentication\Storage\Session as MyStorage;

class MyAuthStorage extends MyStorage{
    public function setRememberMe($rememberMe = 0, $time = 1209600)
    {
        if ($rememberMe == 1) {
            $this->session->getManager()->rememberMe($time);
        }
    }

    public function forgetMe()
    {
        $this->session->getManager()->forgetMe();
    }

}