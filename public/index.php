<?php

use App\system\App;

require "../vendor/autoload.php";
require "../config/app.php";

try {
    $obj = new App();
    $obj->run();
}catch(\Exception $e){

    echo $e->getMessage();
    return;
}