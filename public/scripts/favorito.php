<?php
    include('../../clases/Utilities.php'); 
    $db = Utilities::getConnection();
    //
    if(isset($_POST["cod_documento"])){
        session_start();
        $result = $db->addFavorito($_POST['cod_documento'],$_SESSION['user']);
        
        if($result==FALSE){
            
            echo "BAD";
        }
        else{
            
            echo "OK";
        }
    }
    
    
    
?>