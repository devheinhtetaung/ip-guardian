<?php

use Models\App;
use Models\Link;
use Models\User;
require 'vendor/autoload.php';
if(!isset($_GET['action'])) {
    $page = '<!DOCTYPE html>

    <html
     
    lang="en">
    
    <head>
    
        
    <meta
     
    charset="UTF-8">
    
        
    <meta
     
    name="viewport"
     
    content="width=device-width, initial-scale=1.0">
    
        
    <title>IP Guardian - Maximize Your Shorten URL Income</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-0evHe/X+4u2/QPOq4hJXvAaCq/mZYGlvjYfX27KwtGWLPgT9uAuh5yYn59x+fWv" crossorigin="anonymous">
        <style>
            body {
                background-color: #f5f5f5;
                font-family: sans-serif;
            }
    
            .hero {
                background-image: url(https://source.unsplash.com/random/1600x900);
                background-size: cover;
                background-position: center;
                height: 50vh;
            }
    
            .hero-content {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                height: 100%;
            }
    
            h1 {
                color: #fff;
                font-size: 3rem;
                font-weight: bold;
                margin-bottom: 1rem;
            }
    
            p {
                color: #fff;
                font-size: 1.5rem;
                line-height: 1.5;
                margin-bottom: 2rem;
            }
    
            .btn {
                background-color: #007bff;
                color: #fff;
                padding: 10px 20px;
                border-radius: 5px;
                font-size: 1.2rem;
                cursor: pointer;
            }
    
            .btn:hover {
                background-color: #0062cc;
            }
        </style>
    </head>
    <body>
        <div class="hero">
            <div class="hero-content">
                <h1>Welcome to IP Guardian</h1>
                <p>Maximize your Shorten URL income with IP Guardian</p>
                <a href="https://t.me/sidney" target="_blank" class="btn">Get Started Today</a>
            </div>
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
    default:
        exit("Not found");

}

?>
