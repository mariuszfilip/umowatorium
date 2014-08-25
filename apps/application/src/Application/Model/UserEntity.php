<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Mariusz
 * Date: 07.03.14
 * Time: 15:34
 * To change this template use File | Settings | File Templates.
 */
namespace Application\Model;
class UserEntity{


    protected $id;
    protected $first_name;
    protected $last_name;

    public function exchangeArray($data)
    {
       $this->id     = (isset($data['id'])) ? $data['id'] : null;
       $this->first_name     = (isset($data['imie'])) ? $data['imie'] : null;
       $this->last_name     = (isset($data['nazwisko'])) ? $data['nazwisko'] : null;

    }


    public function getName(){
        return $this->first_name.' '.$this->last_name;
    }

}