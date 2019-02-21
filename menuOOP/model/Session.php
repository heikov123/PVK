<?php
class Session
{
    //sessiooni muutujad
    var $sid = false;
    var $vars = array();
    var $http = false;
    var $db = false;
    var $anonymous = true;
    var $timeout = 1800; //sekundites, 1800 = 30 minutit
    function __construct(&$http, &$db)
    {
        $this->http = &$http;
        $this->db = &$db;
        $this->sid = $http->get('sid'); //http klassist pärit muutuja väärtus
        $this->checkSession();
    }//Session
    //timeout määramiseks vajalik meetod
    function setTimeout($t)
    {
        $this->timeout = $t;
    }//setTimeout
    function setAnonymous($bool)
    {
        $this->anonymous = $bool;
    }//setAnonymous
    function checkSession()
    {
        $this->clearSessions();
        //sessiooni võti pole kaasaantud, ja anonyymne on olemas - luua uus sessioon
        if($this->sid === false and $this->anonymous)
        {
            $this->createSession();
        }
        //kui võti on antud, siis tuleb kontrollida, kas sessioon on lahti - kui juba timeout, siis tuleb uue luua
        if($this->sid !== false)
        {
            $sql = 'SELECT * FROM session WHERE sid='.fixDb($this->sid);
            $res = $this->db->getData($sql);
            //kui andmebaasist päringut ei tule
            if($res == false)
            {
                if($this->anonymous)
                {
                    //anonüümsele valmistatakse uus sessioon
                    $this->createSession();
                }
                else
                {
                    //muidu sessioon kustutakse
                    $this->sid = false;
                    $this->http->del('sid');
                }
                //anonüümse sessiooni või kustutatud sessiooni korral kasutajal ei ole id-d ega ka rolli
                define('ROLE_ID', 0);
                define('USER_ID', 0);
            }
            //sessiooni muutujate sisse lugemine andmebaasi
            else
            {
                $vars = @unserialize($res[0]['svars']);
                if(!is_array($vars))
                {
                    $vars = array();
                }
                $this->vars = $vars;
                //muidu andmebaasis saame vajaliku andmed käte kasutaja rolli ja id kohta
                $user_data = unserialize($res[0]['user_data']);
                define('ROLE_ID', $user_data['role_id']);
                define('USER_ID', $user_data['user_id']);
                $this->user_data = $user_data;
            }
        }
        //kui üldse sessiooni võti puudub, siis alati on olemas need 2 konstandi
        else
        {
            define('ROLE_ID', 0);
            define('USER_ID', 0);
        }
    }//checkSession
    //meetod, mis kustutab andmebaasis kõik sessioonid
    function clearSessions()
    {
        $sql = 'DELETE FROM session '.' WHERE '.time().' - UNIX_TIMESTAMP(changed) > '.$this->timeout;
        $this->db->query($sql);
    }//clearSessions
    function createSession($user = false)
    {
        //kui kasutaja on anonüümne
        if($user == false)
        {
            $user = array(
                'user_id'=>0,
                'role_id'=>0,
                'username'=>'Anonymous'
            );
        }
        //võimalikult unikaalne sessiooni id
        $sid = md5(uniqid(time().mt_rand(1, 1000), true));
        //sisestame andmed andmebaasi
        $sql = 'INSERT INTO session SET '.'sid='.fixDb($sid).', '.'user_id='.$user['user_id'].', '.'user_data='.fixDb(serialize($user)).', '.'login_ip='.fixDb(REMOTE_ADDR).', '.'created=NOW()';
        $this->db->query($sql);
        //määrame sessiooni is
        $this->sid = $sid;
        $this->http->set('sid', $sid);
        //loome cookie
        setcookie(SITENAME, $sid, 0); //0 tähendab, et see cookie kestab senikaua, kuni brouser kinni pannakse
    }//createSession
    //sessiooni kustutamine
    function delSession()
    {
        if($this->sid !== false)
        {
            $sql = 'DELETE FROM session WHERE sid='.fixDb($this->sid);
            $this->db->query($sql);
            setcookie(SITENAME, '', -1);
            $this->sid = false;
            $this->http->del('sid');
        }
    }//delSession
    //meetod, mis me kasutame kasutaja sisendi asendamiseks
    function set($name, $val)
    {
        $this->vars[$name] = $val;
    }
    //meetod, mis tagastab vastava muutuja nime, juhul kui ta eksisteerib
    //html jaoks on ohutu
    function get($name)
    {
        if(isset($this->vars[$name]))
        {
            return $this->vars[$name];
        }
        return false;
    }
    //meetod, mis kasutatakse kasutaja sisendi kustutamiseks
    function del($name)
    {
        if(isset($this->vars[$name]))
        {
            unset($this->vars[$name]);
        }
    }
    //sessiooni muutujad ei tohi ära jätta - kui sessioon lõpeb, tuleb neid kustutada
    function flush()
    {
        if($this->sid != false)
        {
            $sql = 'UPDATE session SET changed=NOW(), '.'svars='.fixDB(serialize($this->vars)).'WHERE sid='.fixDb($this->sid);
            $this->db->query($sql);
        }
    }
}
?>