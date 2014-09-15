<?php
namespace My\Dhtmlx\Weryfikacja;
class Funkcje
{

	public static function niePuste($value){
		if($value=='null'){return false;}
		if($value==''){
			return false;
		}
		return true;
	}


	public static function isText($value)
	{
		$value=trim($value);
		$value=strtolower($value);
		if(preg_match("/^[0-9a-ząęóżźćńłś]*$/",$value))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public static function isFloat($value,$dlugosc)
	{


		if(filter_var($value,FILTER_VALIDATE_FLOAT,($dlugosc)) !==False)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
