<?php
//linkide koostamiseks vajalik klass
require_once('Http.php');

class Linkobject extends Http
{
    var $baseUrl = false;
    var $delim = '&amp;'; 	//sümmol  '&'
    var $eq = '=';
    var $protocol = 'http://';  //$this->server['SERVER_PROTOCOL']

    //add if exists
    //'sid'=>'sid'   - sid jaoks indeksi loomine
    var $aie = array('lang_id', 'sid'=>'sid', 'nocache');

    function LinkObject()
    {
        //pärime kõik Http klassi asjad
        parent::__construct();

        //koostame url-i
        $this->baseUrl = $this->protocol.$this->server['HTTP_HOST'].$this->server['SCRIPT_NAME'];

        $this->set('nocache', time());

        //cookie tekitamine
        //juhul kui cookie on olemas, siis sessiooni võtme väärtust pole vaja
        //nagunii on cookies vajalikud andmed olemas
        if (isset($this->cookie[SITENAME]))
        {
            unset($this->aie['sid']);
            $this->set('sid', $this->cookie[SITENAME]);
        }
    }

    function fixUrl($val)
    {
        return urlencode($val);
    }

    function addToLink(&$link, $name, $val)
    {
        if($link != '')
        {
            $link .= $this->delim;
        }
        $link .= $this->fixUrl($name).$this->eq.$this->fixUrl($val);
    }

    /*
    $add = array('page_id'=>, 'news_id'=>2, 'username'=>'admin');
    $aie = array('page_id', 'news_id');//add if exists
    $not = array('lang_id');
    */

    function getLink($add = array(), $aie = array(), $not = array())
    {
        $link = '';
        foreach($add as $name=>$val)
        {
            $this->addToLink($link, $name, $val);
        }

        //juhul kui antud asi eksisteerib
        foreach($aie as $name)
        {
            $val = $this->get($name);
            if ($val !== false)
            {
                $this->addToLink($link, $name, $val);
            }
        }

        //juhul kui massiivis on see juba olemas
        foreach($aie as $name)
        {
            $val = $this->get($name);
            if ($val !== false and !in_array($name, $not))
            {
                $this->addToLink($link, $name, $val);
            }
        }

        //kui url puudub, paneme baasurl?mingi link
        if ($link !='')
        {
            $link = $this->baseUrl.'?'.$link;
        }
        //muidu paneme baasurl
        else
        {
            $link = $this->baseUrl;
        }
        return $link;
    }

}
?>