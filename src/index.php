<?php

require_once '../vendor/autoload.php';

use Admin\Consulta\config\Config_db;


$auth_db = new Config_db();
$auth_db->auth_db();

require_once('public/main_page.php');
