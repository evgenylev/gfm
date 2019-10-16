<?php

class Autoloader {

    public static function register()
    {
        require(__DIR__.'/../vendor/autoload.php');
        
        return spl_autoload_register(['Autoloader', 'load']);
    }
    
    public static function load($className)
    {
        if (class_exists($className)) {
            return true;
        }
        $fileName = str_replace(['\\','_'], '/', $className) . '.php';
        if (file_exists($fileName)) {
            include($fileName);
        }
        return class_exists($className);
    }
}