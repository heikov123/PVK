<?php
//koodi kaitsmiseks - et väljaspool antud veebi ei saa sisse
if(!defined('BASE_DIR'))
{
    exit;
}
//kontrollime, kas mingi tegevus on kaasa antud või mitte
$controller = $http->get('controller');
$fn = CONTROLLER_DIR.str_replace('.', '/', $controller).'.php';
//kontroll
//kas mingi tegevus on antud kaasa
if(file_exists($fn) and is_file($fn) and is_readable($fn))
{
    require_once($fn);
}
//juhul kui ei ole, siis käivitatakse vaikimisi määratud tegevus
else
{
    $fn = CONTROLLER_DIR.DEFAULT_CONTROLLER.'.php';
    $http->set('controller', DEFAULT_CONTROLLER);
    require_once $fn;
}