<?php


namespace App\system;

class Router
{

    private $action;
    private $params;
    private $controller;

    private $requestUrl;

    public function __construct()
    {
        $request = $_SERVER['REQUEST_URI'];
        $requestUrl = substr($request, strlen(SITE_URL));
        $url = explode('/', rtrim($requestUrl, '/'));
        $this->controller = "App\\Controller\\";

        if (!empty($url[1])) {
            $this->controller .= ucfirst($url[1]) . '_Controller';
        } else {
            $this->controller .= 'Index_Controller';
        }

        if (!empty($url[2])) {
            $this->action .= ucfirst($url[2]) . 'Action';
        } else {
            $this->action .= 'indexAction';
        }

//        All right

        if (!empty($url[3])) {

//            Keys and values are swapped

            $count = count($url);

            $key = [];
            $value = [];

            for ($i = 3; $i < $count; $i++) {

                if ($i % 2 == 0) {
                    $value[] = $url[$i];

                } else {

                    $key[] = $url[$i];
                }

            }

            if (!$this->params = array_combine($key, $value)) {
                throw new \Exception('Error request!!');
            }
        }

//        var_dump($this->controller);
//        var_dump($this->action);

//        Video 1 26:00

    }

    public function proccessRequest()
    {

        if (class_exists($this->controller)) {
            $ref = new \ReflectionClass($this->controller);

            if ($ref->hasMethod($this->action)) {

                if ($ref->isInstantiable()) {
                    $class = $ref->newInstance();
                    $method = $ref->getMethod($this->action);

                    $method->invoke($class, $this->params);
                }
            }

        } else {

            throw new \Exception('Page error');
        }

    }
}