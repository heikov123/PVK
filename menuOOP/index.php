<?php
require_once 'conf.php';
// loome vajalikud vaade objektid
$mainTmpl = new Template('main');
$mainTmpl->set('title', 'Menu App');
$contentTmpl = new Template('content');

require_once 'controller.php';

$mainTmpl->set('content', $contentTmpl->parse());
echo $mainTmpl->parse();