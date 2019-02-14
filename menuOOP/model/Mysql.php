<?php
//andmebaasi klass

class MySQL
{
    var $conn = false; //ühenduse muutuja
    var $history = array(); //päringute meeles hoidmine - dubleerimise jälgimine jne

    var $host = false; //host
    var $user = false; //kasutajatunnus
    var $pass = false; //parool
    var $dbname = false; //andmebaasinimi

    function __construct($h, $u, $p, $n)
    {
        //kui luuakse klass, siis kohe tekkitakse ühendus

        //korrektne igale omadusele teha eraldi omistamismeetodi, otseomistamine ei ole ilus, aga siin teeme otseomistamist
        $this->host = $h;
        $this->user = $u;
        $this->pass = $p;
        $this->dbname = $n;
        $this->connect();
    }

    // andmebaasiga ühenduse loomine
    function connect(){
        $this->conn = mysqli_connect($this->host, $this->user, $this->pass, $this->dbname);
        if(!$this->conn){
            echo 'Probleem andmebaasi ühendusega<br />';
            exit;
        }
    }
    // päringu saatmise funktsioon
    function query($sql){
        $begin = $this->getMicrotime();
        $result = mysqli_query($this->conn, $sql);
        if(!$result){
            echo 'Probleem päringuga '.$sql.' <br />';
            return false;
        }
        $time = $this->getMicrotime() - $begin;
        $this->history[] = array(
            'sql' => $sql,
            'time' => $time
        );
        return $result;
    }
    // andmete lugemine päringust
    function getData($sql){
        $result = $this->query($sql); // saadame päring andmebaasi
        $data = array(); // päringu andmete salvestamiseks
        // nii kaua kui olemas andmed
        while ($row = mysqli_fetch_assoc($result)){
            $data[] = $row; // loeme need ridade kaupa
        }
        // kui probleem andmete lugemisega
        if(count($data) == 0){
            return false;
        }
        return $data; // või tagastame korralikud andmed
    }

    //funktsioon, mis mõõdab päringuaja
    function getMicrotime()
    {
        list($usec, $sec) = explode(' ', microtime());
        return ((float)$usec + (float)$sec);

    }//getMicrotime


    function showHistory()
    {
        if(count($this->history) > 0)
        {
            //päringute arvu vaatamine
            //ordered listi abil
            echo '<hr /><ol>';
            //massiivis olevate elementide väljaprintimine
            foreach($this->history as $key=>$val)
            {
                //
                echo '<li>'.$val['sql'].'<br /><strong>';
                echo round($val['time'], 6).'</strong>';
            }
        }
    }//showHistory

}
?>