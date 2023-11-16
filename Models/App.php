<?php
namespace Models;
require __DIR__.'/../vendor/autoload.php';
use Models\DBConnect;
class App{
    public static function checkUpdate(){
        $result = static::run('updateStatus');
        $row = mysqli_fetch_row($result);
        if($row[0] == 1){
            return '{"status":200,"message":"Update Available"}';
        }else{
            return '{"status":404,"message":"No Update Available"}';
        }
    }
    public static function checkMaintenance(){
        $result = static::run('maintenance');
        $row = mysqli_fetch_row($result);
        if($row[0] == 1){
            return '{"status":503,"message":"App is under maintenance"}';
        }else{
            return '{"status":200,"message":"App is working fine"}';
        }
    }
    public static function AdInfo(){
        $result = static::run('bannerId,interstitialId');
        $row = mysqli_fetch_row($result);
        $bannerId = $row[0];
        $interstitialId = $row[1];
        return [
            "bannerId" => $bannerId,
            "interstitialId" => $interstitialId
        ];
    }
    // public static function 
    private static function run($columns){
        $query = "SELECT `$columns` FROM app;";
        $db = new DBConnect();
        return $db->execute($query);
    }

}
