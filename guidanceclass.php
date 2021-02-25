<?php
//-----------------DECLARING FOR CONNECTION----------------------------\\
class MyGuidance{
    private $server = "mysql:host=localhost;dbname=dbguidance";
    private $user = "root";
    private $pass = "";
    private $option = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO:: FETCH_ASSOC);
    protected $conn;
//-----------------OPENING THE CONNECTION----------------------------\\
public function OpenConnection(){

    try{
        $this->conn = new PDO($this->server,$this->user,$this->pass, $this->option);
        return $this->conn;

    }catch(PDOException $e){
        echo "Error:". $e->getMessage();
    }
}
//-----------------CLOSING THE CONNECTION----------------------------\\
public function CloseConnection(){
        
    $this->conn =null;
}
//-----------------GETTING THE MEMBERS' DATA FROM THE SQL----------------------------\\
    public function getUser(){

        $connection = $this->OpenConnection();
        $stmt= $connection->prepare("SELECT * FROM tbllogin");
        $stmt->execute();
        $users= $stmt->fetchAll();
        $userCount =$stmt->rowCount();

            if($userCount>0){
                return $users;
            }else{
                return 0;
            }
    }
    //-----------------LOG-IN----------------------------\\    

    public function login(){

        
        if(isset($_POST['submit'])){
            $passwordLogin= ($_POST['Password']);
            $emailLogin = $_POST['Email'];
            
            

            $connection = $this->OpenConnection();
            $stmt= $connection->prepare("select * from tbllogin where ID = ? and Password =?");
            $stmt->execute([$emailLogin,$passwordLogin]);
            $user = $stmt->fetch();
            $num = $stmt->rowCount();
                if($num > 0){
                    echo "Welcome ".$user['FirstName']." " .$user['LastName'];
                    $this->setSession($user); 
                }else{
                    echo "Login Failed";
                }

        
        }
    }
    //-----------------SET SESSION----------------------------\\
    public function setSession($array){

        if (!isset($_SESSION)){
                session_start();
                
        }
        $_SESSION['userdata']= array(

                "FullName" =>$array['FirstName']. " ".$array['LastName']
        );
        echo header("Location: index.php");
        return $_SESSION['userdata'];
    }
//-----------------GET SESSION----------------------------\\
    public function getSession(){

        if (!isset($_SESSION)){
            session_start();
    }
        if (isset($_SESSION['userdata'])){
            return $_SESSION['userdata'];
        }else{
            return null;
        }
        

    }


}

$guidance=new MyGuidance();
?>