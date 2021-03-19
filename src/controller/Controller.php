<?php

namespace luka\controller;

abstract class Controller 
{
 
    protected $layout = 'layout/main';
    
    public static function run()
    {
        $actionId = $_GET['action'] ?? 'index';
        $method = 'action' . ucfirst($actionId); 
        try {
            $controller = self::create();
            $content = $controller->$method();
        } catch (\Throwable $e) {
            $controller = new ErrorController();
            $content = $controller->render('layout/_error', ['content' => $e->getMessage()]);
        }        
        return $controller->render($controller->layout, ['content' => $content]);        
    }
    
    public static function create()
    { 
        $controlerId = $_GET['controller'] ?? 'Student';
        $controllerClass = "\\luka\\controller\\".ucfirst($controlerId)."Controller";
        if ( ! class_exists($controllerClass)) {
            throw new \Exception('Unknown controller');
        }
        return new $controllerClass();
    }
    
    public static function render($file, $data)
    {
        extract($data);
        ob_start();
        include __DIR__ . "/../view/$file.php";
        return ob_get_clean();
    }    
    
    public function redirect($location)
    {
        $arr = explode('/', $location[0]);
        $url = 'index.php?controller='. urlencode($arr[0]) . '&action=' . $arr[1];
        array_shift($location);
        foreach($location as $key => $value) {
            $url .= '&'.urlencode($key) . '=' . urlencode($value);
        }
        header("Location:$url");
        die();
    }
}

