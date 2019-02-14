<?php
/**
 * Created by PhpStorm.
 * User: anna.karutina
 * Date: 24.01.2018
 * Time: 8:39
 */

class http
{
    // klassi muutujad
    var $vars = array(); // andmed mis jõuavad HTTP kaudu
    var $server = array(); // serveriga seotud andmed

    /**
     * http constructor.
     */
    public function __construct(){
        $this->init();
        $this->initConst();
    }

    // klassi muutujate väärtustega täitmine
    function init(){
        // nüüd on olemas kõik andmed, mis serverile
        // jõudunud
        $this->vars = array_merge($_GET, $_POST);
        // serveri andmed
        $this->server = $_SERVER;
    }

    // vajalike konstandite defineerimine
    function initConst(){
        $constNames = array('HTTP_HOST', 'SCRIPT_NAME', 'REMOTE_ADDR');
        foreach ($constNames as $constName){
            if(!defined($constName) and isset($this->server[$constName])){
                define($constName, $this->server[$constName]);
            }
        }
    }

    // funktsioon, mis uurib $this->vars massiivi, ja kui
    // antud massiivis on olemas element nimega $name,
    // siis annab antud elemendi väärtuse
    function get($name){
        if(isset($this->vars[$name])){
            return $this->vars[$name];
        } else {
            return false;
        }
    }

    // funktsioon, mis võimaldab määrata $this->vars[$name] elemendile
    // ka kindel väärtus $value
    function set($name, $value){
        $this->vars[$name] = $value;
    }

    // suunamine erinevatele lehtedele
    function redirect($url = false){
        if($url == false){
            $url = $this->getLink();
        }
        $url = str_replace('&amp;', '&', $url);
        header('Location: '.$url);
        exit;
    }
}