<?php
namespace Models;

require  __DIR__.'/../vendor/autoload.php';

class DBConnect{
    public $db_host;
    public $db_port;
    public $db_name;
    public $db_username;
    public $run;
    public $db_password;
    public function __construct(){
        Env::put();
        $this->db_host = getenv('DB_HOST');
        $this->db_port = getenv('DB_PORT');
        $this->db_name = getenv('DB_NAME');
        $this->db_username = getenv('DB_USERNAME');
        $this->db_password = getenv('DB_PASSWORD');
        $this->run = mysqli_connect($this->db_host, $this->db_username, $this->db_password, $this->db_name, $this->db_port);
        if(!$this->run){
            die("Error".mysqli_connect_error());
        }
    }
    public function execute($query){
        $result = $this->run->query($query);
        mysqli_close( $this->run ); 
        return $result;
    }
}