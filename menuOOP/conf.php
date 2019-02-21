<?php
//vajaliku häälestused

define('BASE_DIR', './'); // define('BASE_DIR', '../');

//tegelikult peavad olema conf.php failis
//---------------------------------------
//ajalimiidi ära võtmine
set_time_limit(0);//30 secundit  - default
//eesti sätted
setlocale(LC_TIME, 'Estonia');
//vigade näitamine
error_reporting(E_ALL);

//vajalikud konstandid - kataloogide ja nimede määramiseks

//nimi, millega valmistatakse cookie
define('SITENAME', 'Söökla menüü');


define('MODEL_DIR', BASE_DIR.'model/');
define('TMPL_DIR', BASE_DIR.'views/');
define('LIB_DIR', BASE_DIR.'lib/');
define('LANG_DIR', BASE_DIR.'lang/');
define('CONTROLLER_DIR', BASE_DIR.'controllers/');

//defineerime ka rollide konstandid
define('ROLE_NONE', 0);
define('ROLE_ADMIN', 1);
define('ROLE_USER', 2);


define('DEFAULT_CONTROLLER', 'default'); //vaikimisi defineeritud tegevused
//kasutajate tegevused on kataloogis user, lehe muutused on kataloogis page jne - see on mugav
//ennem tuleb valmistada loogikat, kuidas tegevus ülesehitada
//selleks on eraldi fail - act.php
//----------------------------------------

//impordime vajalikud klassid
require_once(MODEL_DIR.'Template.php');
require_once(MODEL_DIR.'Http.php');
require_once(MODEL_DIR.'Mysql.php');
require_once(MODEL_DIR.'Linkobject.php');
require_once(MODEL_DIR.'Session.php');

require_once(LIB_DIR.'utils.php');

require_once(BASE_DIR.'db_conf.php');

//kui kasutaja tuleb esmakordselt lehele, milline keel näidatakse vaikimisi
define('DEFAULT_LANG', 'et');
//defineerime keelekoodid
$siteLangs = array(
    'et' => 'estonian',
    'en' => 'english',
    'ru' => 'russian'
);

$db = new MySQL(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$http = new LinkObject;
$sess = new Session($http, $db);

//keelekontroll - milline keel praegu aktiivne
$lang_id = $http->get('lang_id');
if(!isset($siteLangs[$lang_id]))
{
    $lang_id = DEFAULT_LANG;
    $http->set('lang_id', $lang_id);
}
define('LANG_ID', $lang_id);

//defineerime rollide tekstilised analoogid
$siteRoles = array(
    ROLE_NONE => 'Pole',
    ROLE_ADMIN => 'Administraator',
    ROLE_USER => 'Kasutaja'
);
?>