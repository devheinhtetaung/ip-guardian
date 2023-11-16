<?php
namespace Models;
require  __DIR__.'/../vendor/autoload.php';
use Models\DBConnect;
use Models\Env;
class Link{
    public static function create($linkId,$whitelistCountry,$destinationLink){
        $shortenUrl = static::BindUrlWithId($linkId);
        $query = "INSERT INTO `link` (`linkId`,`whitelistCountry`,`destinationLink`,`shortenUrl`) VALUES ('$linkId','$whitelistCountry','$destinationLink','$shortenUrl');";
        $db = new DBConnect();
        if($db->execute($query)){
            return '{"status":200,"message":"Created Link Successfully.","Link":"'.$shortenUrl.'"}';
        }else{
            return '{"status":200,"message":"Failed to create Link!"}';
        }
    }

    public static function delete($linkId){
        if(is_numeric($linkId)){
            if(static::findOrfail($linkId) === false){
                exit('{"status":404,"message":"Link Not Found!"}');
            }
            $query = "DELETE FROM `link` WHERE `linkId` = '$linkId';";
            $db = new DBConnect();
            if($db->execute($query)){
                return '{"status":200,"message":"Deleted Link Successfully."}';
            }else{
                return '{"status":200,"message":"Failed To Delete Link!"}';
            }
        }
        return false;
    }
    public static function all($limit){
        if(is_numeric($limit)){
            $query = "SELECT * FROM `link` LIMIT $limit;";
            $db = new DBConnect();
            $result = $db->execute($query);
            return static::processData($result);
        }else{
            return false;
        }
    }
    public static function findOrfail($linkId){
        if(is_numeric($linkId)){
            $query = "SELECT * FROM `link` WHERE `linkId` = '$linkId'";;
            $db = new DBConnect();
            $result = $db->execute($query);
            return static::processData($result);
        }else{
            return false;
        }
    }
    public static function validateIP($ip,$linkId){
        $CC = static::checkIP($ip);
        if(is_bool($CC)){
            return false;
        }
        $data = static::findOrfail($linkId);
        $idInfo = json_decode($data,true);
        $WhiteListCountry = explode(",",$idInfo['data'][0]['whitelistCountry']);
        $link = $idInfo['data'][0]['destinationLink'];
        if(in_array($CC,$WhiteListCountry)){
            exit('{"status":200,"message":"Authorized IP","destinationLink":"'.$link.'"}');
        }else{
            exit('{"status":400,"message":"Unauthorized IP"}');
        }
    }
    private static function checkIP($ip){
        $endpoint = 'http://ip-api.com/json/'.$ip.'?fields=16386';
        $data = file_get_contents($endpoint);
        $apiInfo = json_decode($data,true);
        $status = $apiInfo['status'];
        if($status !== 'success'){
            return false;   
        }
        $CC = $apiInfo['countryCode'];
        return $CC;
    }
    private static function processData($returnData){
        $data = [];
        $response = [];
        if(mysqli_num_rows($returnData) > 0){
            while($row = mysqli_fetch_assoc($returnData)){
                $data[] = $row;
            }
            $response["status"] = 200;
            $response["data"] = $data;
            return json_encode($response,true);
        }else{
            return false;
        }
    }
    public static function BindUrlWithId($linkId){
        $query = "SELECT `wpLink` FROM `wordpress` ORDER BY RAND() LIMIT 1";
        $db = new DBConnect();
        $result = $db->execute($query);
        $row = mysqli_fetch_row($result);
        $wplink = $row[0].'&id='.$linkId;
        return static::shortenUrl($wplink);
    }
    private static function shortenUrl($url){
        Env::put();
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://api.tinyurl.com/create');
        $payload = array(
            "url" => $url,
            "description"=> "Created With API Key"
        );
        $header = array(
            "accept: application/json",
            "Content-Type: application/json",
            "Authorization: Bearer ".getenv('API_KEY')
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_POSTFIELDS,json_encode($payload));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
        $data = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($data,true);
        return $result['data']['tiny_url'];
    }
}
