<?php
//template klass
//kohad, kus me saame info ise panna - see on põhimõtteliselt kujundusfail
//antud templates puudub cashimine

//kataloog, kust laaditakse template, defineeritud konstandina TMPL_DIR
//kontrollime ka, kas ta on juba defineeritud
//kui mitte, siis see on tmpl kataloog

if(!defined('TMPL_DIR'))
{
    define('TMPL_DIR', 'views/');
}
class Template
{
    var $file = '';
    var $content = false;
    var $vars = array();

//Template parameetriks on laadiva faili nimi
    function __construct($fn)
    {
        $this->file = $fn;
        $this->loadFile();
    }//Template

    function LoadFile()
    {
        $f = $this->file;

        //veakontrollid

        //kas template kataloog on olemas
        //!is_readable - kas on loetav
        if(!is_dir(TMPL_DIR))
        {
            echo 'Kataloogi '.TMPL_DIR.' ei leidud';
            exit;
        }


        //kui on juba kaas antud õige fail koos teega
        if(file_exists($f) and is_file($f) and is_readable($f))
        {
            $this->readFile($f);
        }

        //kui kasutaja annab ainutl failinimi ja laiend, ilma kataloogita
        $f = TMPL_DIR.$this->file;

        if(file_exists($f) and is_file($f) and is_readable($f))
        {
            $this->readFile($f);
        }

        //kasutaja annab ainult faili nimi, ilma laiendita
        //html faili jaoks
        $f = TMPL_DIR.$this->file.'.html';

        if(file_exists($f) and is_file($f) and is_readable($f))
        {
            $this->readFile($f);
        }
        //htm faili jaoks
        $f = TMPL_DIR.$this->file.'.htm';

        if(file_exists($f) and is_file($f) and is_readable($f))
        {
            $this->readFile($f);
        }

        //tmpl kataloogis saaks omakorda kataloogid valmistada, et oleks mitte nii segane võrk seal
        //jälle html faili jaoks
        $f = TMPL_DIR.str_replace('.', '/', $this->file).'.html';
        if(file_exists($f) and is_file($f) and is_readable($f))
        {
            $this->readFile($f);
        }
        //jälle htm faili jaoks
        $f = TMPL_DIR.str_replace('.', '/', $this->file).'.htm';
        if(file_exists($f) and is_file($f) and is_readable($f))
        {
            $this->readFile($f);
        }

        //kui siiani jõudnud, ja konteksti pole, siis väljastame veateade
        if($this->content === false)
        {
            echo 'Ei suutnud lugeda faili '.$this->file;
            exit;
        }



    }//loadFile

    function readFile($filename)
    {
        /*
        $fp = fopen($filename, 'rb'); //rb - binary save
        $this->content = fread($fp, filesize($filename));
        fclose($fp);
        */
        $this->content = file_get_contents($filename);
    }//readFile

    //template sisu määramine
    //kindla kohta templatis peab tulema vastava muutuja väärtus
    function set($name, $val)
    {
        $this->vars[$name] = $val;
    }//set

    //meie template tabelisse ridade juurde lisamine
    function add($name, $val)
    {
        //teostame kontrolli, kas väärtused juba on olemas
        //juhul, kui ei ole, siis tekitame neid
        if(!isset($this->vars[$name]))
        {
            $this->set($name, $val);
        }
        //juhul, kui on, siis lisame lihtsalt juurde
        else
        {
            $this->vars[$name].= $val;
        }
    }//add

    //meetod, mis asendab template konkreetse sisuga
    function parse()
    {
        $str = $this->content;
        foreach($this->vars as $name=>$val)
        {
            $str = str_replace('{'.$name.'}', $val, $str);
        }
        return $str;
    }//parse

}
?>