<?php
namespace Models;

require  __DIR__.'/../vendor/autoload.php';

use Models\DBConnect;
use Models\PHPMailer\PHPMailer;
class User {
    public static function signup($email, $password){
        $emailVerifyToken = static::generateToken();
        $userExist = static::userExists($email);
        if(empty($email) && empty($password)){
            return FALSE;
        }
        if($userExist){
            exit('{"status":409,"message":"Email Already Exist"}');
        }else{
            $password = password_hash($password,PASSWORD_BCRYPT);
            $personal_token = static::generatePT();
            $query = "INSERT INTO `user` (`email`, `password`, `personal_token`, `email_verify_token`) VALUES ('$email', '$password', '$personal_token', '$emailVerifyToken');";
            $db = new DBConnect();
            if($db->execute($query)){
                return static::sendMail($email,$emailVerifyToken,$personal_token);
            }else{
                return '{"status":400,"message":"Failed to register account!"}';
            }
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
                return '{"status":200,"message":"Login successfully!"}';
            }else{
                return '{"status":400,"message":"Email or Password is not correct!""}';
            }
        }
    }
    public static function loginWithToken($token){
        if(static::validateToken($token)){
            $query = "SELECT * FROM `user` WHERE `personal_token` = '$token';";
            $db = new DBConnect();
            $result = $db->execute($query);
            if(mysqli_num_rows($result) > 0){
                return '{"status":200,"message":"Login successfully!"}';
            }
            return '{"status":400,"message":"Token : Not Found!"}';
        }
        return '{"status":400,"message":"Invalid Token!"}';
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
    private static function sendMail($email,$emailVerifyToken,$personal_token){
        date_default_timezone_set('Asia/Yangon');
        $verificationLink = 'https://ip-guardian-36e002dbd52b.herokuapp.com/verify.php?token=' . $emailVerifyToken;
        $mail = new PHPMailer();
        $mail->addAddress($email,'User');
        $mail->setFrom('ip-guardian@outlook.com', 'IP Guardian Account Center');
        $mail->Subject = 'Confirm Your Email Address for IP Guardian!';
        $mail->Body = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Email Verification</title>
            <style>
                body {
                    font-family: \'Arial\', sans-serif;
                    background-color: #f4f4f4;
                    margin: 0;
                    padding: 0;
                    text-align: center;
                }
        
                .container {
                    max-width: 600px;
                    margin: 50px auto;
                    background-color: #fff;
                    padding: 20px;
                    border-radius: 10px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
        
                h1 {
                    color: #3498db;
                }
        
                p {
                    color: #555;
                }
        
                .verification-button {
                    display: inline-block;
                    margin-top: 20px;
                    padding: 0; /* Remove padding to allow custom styling on the <a> tag */
                    cursor: pointer;
                }
        
                .verification-button a {
                    display: block;
                    padding: 10px 20px;
                    background-color: #3498db;
                    color: #fff;
                    text-decoration: none;
                    border: none;
                    border-radius: 5px;
                    font-weight: bold;
                }
        
                .verification-button a:hover {
                    background-color: #2980b9;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>Email Verification</h1>
                <p>We are excited to have you on board. Your account has been successfully created. Feel free to explore and enjoy our services. If you have any questions or need assistance, don not hesitate to contact us.</p>
                <p>To complete your registration, please click the verification button below:</p>
                <button class="verification-button"><a href=\'' . $verificationLink . '\'>Verify Email</a></button>
            </div>
        </body>
        </html>'
        ;
        $mail->isHTML(true);
        $mail->isSMTP();
        $mail->Host = 'smtp-mail.outlook.com';
        $mail->SMTPAuth = true;
        $mail->Username = getenv('SMTP_USERNAME');
        $mail->Password = getenv('SMTP_PASSWORD');
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        if (!$mail->send()) {
            return "Error sending email: " . $mail->ErrorInfo;
        } else {
            return '{"status":200,"message":"Registered successfully!\n Check Your email to confirm your account.","token":"'.$personal_token.'"}';
        }
    }
    private static function generateToken(){
        $randomBytes = random_bytes(32);
        return bin2hex($randomBytes);
        
    }
    private static function userExists($email){
        $query = "SELECT email FROM `user` WHERE `email` = '$email';";
        $db = new DBConnect();
        $result = $db->execute(($query));
        if(mysqli_num_rows($result) > 0){
            return TRUE;
        }
        return FALSE;
    }
}
?>
