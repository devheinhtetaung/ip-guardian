<?php

use Models\App;
use Models\Link;
use Models\User;
require 'vendor/autoload.php';
if(!isset($_GET['action'])) {
    $page = '<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Welcome to IP Guardian</title>
        <style>
            body {
                font-family: \'Arial\', sans-serif;
                background-color: #f4f4f4;
                text-align: center;
                margin: 0;
                padding: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100vh;
            }
    
            .container {
                max-width: 600px;
                background-color: #fff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
    
            h1 {
                color: #333;
            }
    
            p {
                color: #555;
                margin-bottom: 20px;
            }
    
            .btn {
                display: inline-block;
                padding: 10px 20px;
                font-size: 16px;
                background-color: #3498db;
                color: #fff;
                text-decoration: none;
                border-radius: 4px;
            }
        </style>
    </head>
    
    <body>
        <div class="container">
            <h1>Welcome to IP Guardian</h1>
            <p>Maximize your Shorten URL income with IP Guardian.</p>
            <a href="https://t.me/sidney" class="btn">Get Started</a>
        </div>
    </body>
    
    </html>';
    echo $page;
}
$action = $_GET['action'];
$UserMethod = ['signup','loginWithEmail','loginWithToken'];
$LinkMethod = ['create','delete','all','findOrfail'];
$AppMethod = ['checkUpdate','checkMaintenance','AdInfo'];
function LinkClass($action){
    $auth = User::loginWithToken($_POST['token']);
    $auth = json_decode($auth,TRUE);
    if($auth['status'] == 200){
        switch($action){
            case 'create':
                $linkId = rand(0000,9999);
                $whitelistCountry = $_POST['whitelistCountry'];
                $destinationLink = $_POST['destinationLink'];
                exit(Link::create($linkId,$whitelistCountry,$destinationLink));
            case 'delete':
                $linkId = $_POST['linkId'];
                exit(Link::delete($linkId));
            case 'all':
                $limit = $_POST['limit'];
                exit(Link::all($limit));
            case 'findOrfail':
                $linkId = $_POST['linkId'];
                exit(Link::findOrfail($linkId));
        }
    }else{
        exit('{"status":404,"message":"You are not authorized to perform this action"}');
    }
}
function UserClass($action){
    switch($action){
        case 'signup':
            $email = $_POST['email'];
            $password = $_POST['password'];
            exit(User::signup($email,$password));
        case 'loginWithEmail':
            $email = $_POST['email'];
            $password = $_POST['password'];
            exit(User::loginWithEmail($email,$password));
        case 'loginWithToken':
            $token = $_POST['token'];
            exit(User::loginWithToken($token));
    }
}
function AppClass($action){
    switch($action){
        case 'checkUpdate':
            exit(App::checkUpdate());
        case 'checkMaintenance':
            exit(App::checkMaintenance());
        case 'AdInfo':
            $Adinfo = App::AdInfo();
            exit(json_encode($Adinfo));
        
    }
}
switch($action){
    case in_array($action, $UserMethod):
        UserClass($action);
        break;
    case in_array($action, $LinkMethod):
        LinkClass($action);
        break;
    case in_array($action, $AppMethod):
        AppClass($action);
        break;
    case 'validateIP':
        $ip = $_POST['ip'];
        $id = $_POST['id'];
        exit(Link::validateIP($ip,$id));
    default:
        exit("Not found");

}

?>
