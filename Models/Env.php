<?php
namespace Models;

class Env {
    public static $env = __DIR__.'/../.env';
    public static function put() {
        $env = static::$env;
        if(file_exists($env)){
            $lines = file($env, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach($lines as $line){
                list($key,$value) = explode('=',$line, 2);
                putenv($key.'='.$value);
            }
        }else{
            exit('Env file not found');
        }
    }
}