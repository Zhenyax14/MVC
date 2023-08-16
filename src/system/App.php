<?php


namespace App\system;

use App\system\Model\DbDriver;

class App
{
    private $router;
    private $dbDriver;

    public function run() {

        $this->dbDriver = DbDriver::get_instance();
        $this->dbDriver->setConnection(HOST, USER, PASSWORD, DB_NAME);

        $this->router = new Router();
        $this->router->proccessRequest();
    }
}