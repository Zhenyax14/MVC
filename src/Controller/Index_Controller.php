<?php

namespace App\Controller;

use App\system\Controller\AbstractController;
class Index_Controller extends AbstractController
{
    public function indexAction($params = []) {

        $this->page = $this->view->render('index/index', ['title' => 'TITLE']);

        var_dump();
        return $this->output();
    }
}