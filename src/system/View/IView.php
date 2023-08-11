<?php

namespace App\system\View;

interface IView
{
    public function render($path, $params = []);
}
