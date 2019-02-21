<?php
require_once 'conf.php';
// loome vajalikud vaate objektid
$mainTmpl = new Template('main');

$mainTmpl->set('title', 'Menu App');
$contentTmpl = new Template('content');

$mainTmpl->set('content', $contentTmpl->parse());

echo $mainTmpl->parse();

//echo '<pre>';
//print_r($mainTmpl);
//echo '</pre>';

