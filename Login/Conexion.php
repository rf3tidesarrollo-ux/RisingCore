<?php

class DB{
    private $Host;
    private $DB;
    private $User;
    private $Password;
    private $Charset;

    public function __construct(){
        
        $this->Host     = 'localhost';
        $this->DB       = 'bd_risingfarms3';
        $this->User     = 'root';
        $this->Password = "";
        $this->Charset  = 'utf8mb4';
        /*
        $this->Host     = 'localhost';
        $this->DB       = 'id20759305_bd_inventario';
        $this->User     = 'id20759305_master_chief';
        $this->Password = "JErgL%$[v?aD7pjF";
        $this->Charset  = 'utf8mb4';
        */

    }

    function connect(){
    
        try{
            
            $sql = "mysql:host=" . $this->Host . ";dbname=" . $this->DB . ";charset=" . $this->Charset;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $pdo = new PDO($sql, $this->User, $this->Password, $options);
    
            return $pdo;

        }catch(PDOException $e){
            print_r('Error connection: ' . $e->getMessage());
        }   
    }
}


?>
