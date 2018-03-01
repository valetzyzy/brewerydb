#!/usr/bin/php
<?php

require_once 'vendor/autoload.php';

//init constants
define('ROOT_DIR', __DIR__);

use App\App;

if (php_sapi_name() !== 'cli') {
    throw new Exception('Sorry, this application use CLI');
}


$app = new App();
$app->run();



