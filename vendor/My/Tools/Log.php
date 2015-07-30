<?php

/**
* @desc tworzy drzewko plików i zapisuje log do pliku
 */

namespace My\Tools;
class Log{

    protected $sSciezkaKataloguLog = '';
    protected $sPrzedrostek = '';

    public function __construct($sSciezkaKataloguLog,$sPrzedrostek){
        $this->sSciezkaKataloguLog = $sSciezkaKataloguLog;
        $this->sPrzedrostek = $sPrzedrostek;
    }

    private function getDateCatalogTree($sPath, $data = false, $czyWyswietlacBledy = false)
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


    /**
     * @param $mDane
     */
    public function pre($mDane){
        echo '<pre>';
        print_r($mDane);
        echo '</pre>';
    }


    /**
     * @param int $e
     * @return string
     */
    public function getMicroTime($e = 7){
        list($u, $s) = explode(' ', microtime());
        return bcadd($u, $s, $e);
    }

    /**
     * @param $sTrescDoLog
     * @return bool
     * @throws Exception
     * @desc zapis danych do pliku z logami , plik jest jeden dziennie wazne!!!!
     */
    public function log($sTrescDoLog){
        if($sTrescDoLog == ''){
            throw new Exception('Próba zapisu pustego loga');
        }

        $sTxtDoPliku = '';
        $sTxtDoPliku .= PHP_EOL;
        $sTxtDoPliku .= date('Y-m-d H:i:s').'	'.$this->getMicroTime();
        $sTxtDoPliku .= PHP_EOL;
        $sTxtDoPliku .= $sTrescDoLog;
        $sTxtDoPliku .= PHP_EOL;
			try{
        $sKatalogPath = $this->getDateCatalogTree($this->sSciezkaKataloguLog);

        $sNazwaPliku = $this->sPrzedrostek.date('Y-m-d').'.txt';
        $mUchwytPliku = fopen($sKatalogPath.$sNazwaPliku, 'a');
        fwrite($mUchwytPliku, $sTxtDoPliku);
        fclose($mUchwytPliku);
			}catch (Exception $e){
				var_dump($e);
				return false;
			}


        return true;
    }

}