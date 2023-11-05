<?php

use Models\App;
use Models\Link;
use Models\User;
require 'vendor/autoload.php';
if(!isset($_GET['action'])) {
    exit('Empty Action ');
}
$action = $_GET['action'];
$UserMethod = ['signup','loginWithEmail','loginWithToken'];
$LinkMethod = ['create','delete','all','findOrfail','validateIP'];
$AppMethod = ['checkUpdate','checkMaintenance','AdInfo'];
function LinkClass($action){
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
        case 'validateIP':
            $linkId = $_POST['linkId'];
            $ip = $_POST['ip'];
            exit(Link::validateIP($ip, $linkId));
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
    default:
        exit("Not found");

}

?>
