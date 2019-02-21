<?php
/**
 * Created by PhpStorm.
 * User: anna.karutina
 * Date: 12.02.2018
 * Time: 12:19
 */

class session
{
    // sessiooni klassi muutujad
    var $sid = false; // sessiooni id
    var $vars = array(); // sessiooni ajal tekkinud andmed
    var $http = false; // otseühendus $http objektiga
    var $db = false;// otseühendus $db objektiga

    var $timeout = 1800; // sessiooni pikkus - 30 minutit
    var $anonymous = true; // kas on lubatud anonüümne kasutamine

    /**
     * session constructor.
     * @param bool $http
     * @param bool $db
     */
    public function __construct(&$http, &$db)
    {
        $this->http = &$http;
        $this->db = &$db;
    }

    // sessiooni loomine
    function createSession($user = false){
        // kui kasutaja on anonüümne
        if($user == false){
            // loome anonüümse kasutaja andmed
            $user = array(
                'user_id' => 0,
                'role_id' => 0,
                'username' => 'Anonüümne'
            );
        }
        // loome sessiooni id
        $sid = md5(uniqid(time().mt_rand(1, 1000), true));
        // päring sessiooni andmete sisetamiseks andmebaasi
        $sql = 'INSERT INTO session SET '.
            'sid='.fixDB($sid).', '.
            'user_id='.fixDB($user['user_id']).', '.
            'user_data='.fixDB(serialize($user)).', '.
            'login_ip='.fixDB(REMOTE_ADDR).', '.
            'created=NOW()';
        // saadame päring andmebaasi
        $this->db->query($sql);
        // määrame sessioonile loodud id
        $this->sid = $sid;
        // paneme antud väärtus ka veebi andmete sisse
        $this->http->set('sid', $sid);
    }

    // sessiooni tabeli puhastamine vanenenud sessioonidest
    function clearSessions(){
        $sql = 'DELETE FROM session WHERE '.
            time().'- UNIX_TIMESTAMP(changed) > '.
            $this->timeout;
        $this->db->query($sql);
    }

    // sessiooni seisundi kontroll
    function checkSession(){
        $this->clearSessions();
        // kui sid ei ole kätte saadav ja
        // anonüümne sessioon on lubatud
        // avame uus anonüümne sessioon
        if($this->sid === false and $this->anonymous){
            $this->createSession();
        }
        // kui sid on kätte saadav
        if($this->sid !== false){
            // tuleb andmed võtma andmebaasist
            $sql = 'SELECT * FROM session WHERE '.
                'sid='.fixDB($this->sid);
            $result = $this->db->getData($sql);
            // kui andmed ei tulnud
            if($result == false){
                // loome uus anonüümne sessioon,
                // kui see on lubatud
                if($this->anonymous){
                    $this->createSession();
                } else {
                    // koristame andmed, mis nii sama on
                    $this->sid = false;
                    // on vaja veebist ka maha võta!!!
                    // .. veel ei ole
                }
                // loon anonüümse kasutaja rolli ja user_id
                define('ROLE_ID', 0);
                define('USER_ID', 0);
            } else {
                // kasutame andmed andmebaasist
                $vars = unserialize($result[0]['svars']);
                $this->vars = $vars;
                $user_data = unserialize($result[0]['user_data']);
                define('ROLE_ID', $user_data['role_id']);
                define('USER_ID', $user_data['user_id']);
                $this->user_data = $user_data;
            }
        } else {
            // kui sessiooni pole
            define('ROLE_ID', 0);
            define('USER_ID', 0);
        }
    }
}