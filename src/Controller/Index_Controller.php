<?php

namespace App\Controller;

use App\Model\User;
use App\system\Controller\AbstractController;
class Index_Controller extends AbstractController
{
    public function indexAction($params = []) {

        $user = new User();

        $user->login = 'admin';
        $user->email = 'admin@admin.com';
        $user->save();

        $this->page = $this->view->render('index/index', ['title' => 'IndexView']);

//        var_dump($this->page);
        return $this->output();
    }
}