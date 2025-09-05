<?php
include 'Conexion.php';

class User extends DB{
    private $Username;

    /*public function hash($password) {
         return password_hash($password, PASSWORD_DEFAULT, ['cost' => 15]);
    }
    $hash = $User->hash('1234');*/

    public function verify($password, $user) {
        $query = $this->connect()->prepare('SELECT password FROM usuarios WHERE BINARY username = :User');
        $query->bindParam(':User', $user,PDO::PARAM_STR);
        $query->execute();
        $hash="";
        $Reg = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($Reg) {
            foreach ($Reg as $Registro) {
                $hash=$Registro["password"];
            }
        }
        return password_verify($password, $hash);
    }

    public function setUser($User){
        $query = $this->connect()->prepare('SELECT * FROM usuarios WHERE BINARY username = :User');
        $query->bindParam(':User', $user,PDO::PARAM_STR);
        $query->execute();
        //$query->execute([':User' => $User]);
        
        foreach ($query as $CurrentUser) {
            $this->Username = $CurrentUser['usuario'];
        }
    }

    public function getUsuario(){
        return $this->Username;
    }
}

?>