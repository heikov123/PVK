<?php
class Http
{
    var $vars = array(); //päringu requestile kaasantud muutujad kasutaja poolt
    var $cookie = array(); //cookiede hoidmiseks vajalik massiiv

    function __construct()
    {
        //konstruktori lihtusustamiseks paneme kõik tema loogika eraldi meetodisse
        $this->init();
        $this->initConst();
    }

    function initConst()
    {
        $vars = array('REMOTE_ADDR', 'PHP_SELF');
        foreach($vars as $var)
        {
            //& on preudoloogika ja seda ei soovitatav kasutada
            //õigem kirjutada and, or

            //preudo if (?; kombinatsioon) ei ole soovitatav kasutada,
            //kuna see on raskesti loetav, ja teami töö korral kasutatakse and, or jne.
            if(!defined($var) and isset($this->server[$var]))
            {
                define($var, $this->server[$var]);
            }
        }
    }

    function init()
    {
        //$_GET $_POST $COOKIE $FILES
        //kasutaja poolt saadud info nende vahendite järgi

        //automaatne varjastus POST ja COOKIE korral tuleb käsitsi välja lülitada
        if(get_magic_quotes_gpc() == 1)
        {
            function fixValue(&$val, $key)
            {
                $val = stripSlashes($val);
            }
            //funktsioon, mis kõib massiivi läbi ja korjab vajalikud parameetrid
            array_walk($_GET, 'fixValue');
            array_walk($_POST, 'fixValue');
            array_walk($_COOKIE, 'fixValue');
        }

        //paneme kõike massiivi
        $this->vars = array_merge($_GET, $_POST, $_FILES);
        $this->cookie = $_COOKIE;
        $this->server = $_SERVER;
    }

    /*
    //meetod, mis tagastab vastava muutuja nime, juhul kui ta eksisteerib
    function get($name)
    {
        if(isset($this->vars[$name]))
        {
            return $this->vars[$name];
        }
        return false;
    }
    */

    //meetod, mis tagastab vastava muutuja nime, juhul kui ta eksisteerib
    //html jaoks on ohutu
    function get($name, $fix = false)
    {
        if(isset($this->vars[$name]))
        {
            if($fix)
            {
                return fixHtml($this->vars[$name]);
            }
            return $this->vars[$name];
        }
        return false;
    }

    //meetod, mis me kasutame kasutaja sisendi asendamiseks
    function set($name, $val)
    {
        $this->vars[$name] = $val;
    }

    //meetod, mis kasutatakse kasutaja sisendi kustutamiseks
    function del($name)
    {
        if(isset($this->vars[$name]))
        {
            unset($this->vars[$name]);
        }
    }

    //kasutaja ümbersuunamine
    function redirect($url = false)
    {
        //see mis nüüd kirjas, ei ole ilus,
        //parem teha sessiooni klassi kaudu, aga nii on ka võimalik
        global $sess;
        $sess->flush();

        //juhul, kui suunamise parameeter pole antud, siis saame ta kätte
        if($url == false)
        {
            $url = $this->getLink();
        }
        //muidu teeme suunamine
        $url = str_replace('&amp;', '&', $url);
        header('Location: '.$url);
        exit;
    }//redirect
}
?>