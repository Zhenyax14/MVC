<?php

namespace App\system\View;

class View implements IView
{

    public function __construct($basePath, $templatePath) {
        $this->basePath = $basePath;
        $this->templatePath = $templatePath;
    }
     public function render($path, $params = array()) : string
     {
         extract($params);

         ob_start();
         if(!include($this->basePath.'/'.$this->templatePath.'/'.$path.'.php')) {
            throw new \Exception('View is not found');
         }
         return ob_get_clean();
     }
}
