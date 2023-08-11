<?php


namespace App\system;

class App
{
    private $router;
    private $dbDriver;

    public function run() {

        $this->router = new Router();
        $this->router->proccessRequest();
    }
}