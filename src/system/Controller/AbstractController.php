<?php

namespace App\system\Controller;

use App\system\View\View;

abstract class AbstractController
{
    protected $view;

    protected $page;

    public function __construct() {
        $this->view =  new View(BASEPATH, TEMPLATE);
    }

    public function get_page() {
        return $this->page;
    }

    public function output(){
        echo $this->get_page();
    }
}