<?php

if(!defined('ABSPATH'))
    exit;


foreach(glob(dirname(__FILE__) . '/inc/*.class.php') as $filename) {
    require_once $filename;
}

foreach(glob(dirname(__FILE__) . '/inc/CPT/*.class.php') as $filename) {
    require_once $filename;
}

initSyrusAIPlugin();