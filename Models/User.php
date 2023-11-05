<?php
namespace Models;

require  __DIR__.'/../vendor/autoload.php';

use Models\DBConnect;

class User {
    public static function signup($email, $password){
        if(empty($email) && empty($password)){
            return FALSE;
        }
        $password = password_hash($password,PASSWORD_BCRYPT);
        $personal_token = static::generatePT();
        $query = "INSERT INTO `user` (`email`, `password`, `personal_token`) VALUES ('$email', '$password', '$personal_token');";
        $db = new DBConnect();
        if($db->execute($query)){
            return "{'status':200,'message':'Registered successfully!'}";
        }else{
            return "{'status':400,'message':'Failed to register account!'}";
        }
    }
    public static function loginWithEmail($email,$password){
        $query = "SELECT `password` FROM `user` WHERE `email` = '$email';";
        $db = new DBConnect();
        $result = $db->execute($query);
        if(mysqli_num_rows($result) < 0){
            return FALSE;
        }else{
            $row = mysqli_fetch_row($result);
            $hash_password = $row[0];
            if(password_verify($password, $hash_password)){
                return "{'status':200,'message':'Login successfully!'}";
            }else{
                return "{'status':400,'message':'Email or Password is not correct!'}";
            }
        }
    }
    public static function loginWithToken($token){
        if(static::validateToken($token)){
            $query = "SELECT * FROM `user` WHERE `personal_token` = '$token';";
            $db = new DBConnect();
            $result = $db->execute($query);
            if(mysqli_num_rows($result) > 0){
                return "{'status':200,'message':'Login successfully!'}";
            }
            return "{'status':400,'message':'Token : Not Found!'}";
        }
        return "{'status':400,'message':'Invalid Token!'}";
    }
    public static function generatePT(){
        $data = "123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz@#";
        $length = 30;
        $token = '';
        $dataLength = strlen($data);
        for($i = 0; $i < $length; $i++){
            $token .= $data[rand(0, $dataLength -1)];
        }
        return md5($token);
    }
    public static function validateToken($token){
        if(strlen($token) == 32 && is_string($token)){
            if(preg_match('/^[0-9A-Za-z]+$/',$token)){
                return TRUE;
            }
        }
        return FALSE;
    }
}
?>
