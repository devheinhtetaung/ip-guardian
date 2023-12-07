<?php
use Models\DBConnect;
require 'vendor/autoload.php';
if(!isset($_GET['token'])){
    exit("No Token found");
}
$token = $_GET['token'];
$db = new DBConnect();
$token = mysqli_real_escape_string($db->run, $token);
$query = "SELECT * FROM `user` WHERE `email_verify_token` = '$token' AND `email_verified` IS NULL;";
$result = $db->execute($query);
if(mysqli_num_rows($result) > 0){
    $db = new DBConnect();
    $query = "UPDATE `user` SET `email_verified` = 1 WHERE `email_verify_token` = '$token';";
    $result = $db->execute($query);
    if($result){
        echo '<!DOCTYPE html>
        <html lang="en">
        
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Email Verification - Thank You</title>
            <style>
                body {
                    font-family: \'Arial\', sans-serif;
                    background-color: #f8f9fa;
                    margin: 0;
                    padding: 0;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    height: 100vh;
                    text-align: center;
                }
        
                .container {
                    background-color: #fff;
                    padding: 40px;
                    border-radius: 10px;
                    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
                    max-width: 600px;
                    width: 100%;
                    box-sizing: border-box;
                }
        
                h1 {
                    color: #007bff;
                }
        
                p {
                    color: #555;
                    margin-bottom: 20px;
                }
        
                .success-icon {
                    color: #28a745;
                    font-size: 60px;
                    margin-bottom: 20px;
                }
        
                .back-to-home {
                    text-decoration: none;
                    background-color: #007bff;
                    color: #fff;
                    padding: 10px 20px;
                    border-radius: 5px;
                    font-weight: bold;
                    transition: background-color 0.3s;
                }
        
                .back-to-home:hover {
                    background-color: #0056b3;
                }
            </style>
        </head>
        
        <body>
            <div class="container">
                <i class="success-icon">&#10004;</i>
                <h1>Thank You!</h1>
                <p>Your email address has been successfully verified.</p>
            </div>
        </body>
        
        </html>'
;        
    }else{
        echo '<!DOCTYPE html>
    <html lang="en">
    
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Email Verification - Failed</title>
        <style>
            body {
                font-family: \'Arial\', sans-serif;
                background-color: #f8f9fa;
                margin: 0;
                padding: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100vh;
                text-align: center;
            }
    
            .container {
                background-color: #fff;
                padding: 40px;
                border-radius: 10px;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
                max-width: 600px;
                width: 100%;
                box-sizing: border-box;
            }
    
            h1 {
                color: #dc3545;
            }
    
            p {
                color: #555;
                margin-bottom: 20px;
            }
    
            .failure-icon {
                color: #dc3545;
                font-size: 60px;
                margin-bottom: 20px;
            }
    
            .back-to-home {
                text-decoration: none;
                background-color: #dc3545;
                color: #fff;
                padding: 10px 20px;
                border-radius: 5px;
                font-weight: bold;
                transition: background-color 0.3s;
            }
    
            .back-to-home:hover {
                background-color: #bd2130;
            }
        </style>
    </head>
    
    <body>
        <div class="container">
            <i class="failure-icon">&#10008;</i>
            <h1>Verification Failed</h1>
            <p>Sorry, we could not verify your email address.</p>
            <a href="https://yourhomepage.com" class="back-to-home">Back to Homepage</a>
        </div>
    </body>
    
    </html>'
;    
    }
}else{
    echo '<!DOCTYPE html>
    <html lang="en">
    
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Email Verification - Token Invalid</title>
        <style>
            body {
                font-family: \'Arial\', sans-serif;
                background-color: #f8f9fa;
                margin: 0;
                padding: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100vh;
                text-align: center;
            }
    
            .container {
                background-color: #fff;
                padding: 40px;
                border-radius: 10px;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
                max-width: 600px;
                width: 100%;
                box-sizing: border-box;
            }
    
            h1 {
                color: #dc3545;
            }
    
            p {
                color: #555;
                margin-bottom: 20px;
            }
    
            .error-icon {
                color: #dc3545;
                font-size: 60px;
                margin-bottom: 20px;
            }
    
            .back-to-home {
                text-decoration: none;
                background-color: #dc3545;
                color: #fff;
                padding: 10px 20px;
                border-radius: 5px;
                font-weight: bold;
                transition: background-color 0.3s;
            }
    
            .back-to-home:hover {
                background-color: #bd2130;
            }
        </style>
    </head>
    
    <body>
        <div class="container">
            <i class="error-icon">&#9888;</i>
            <h1>Invalid Verification Token</h1>
            <p>The verification token is either expired or not valid.</p>
        </div>
    </body>
    
    </html>'
;    
}
?>