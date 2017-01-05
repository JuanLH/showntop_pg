<?php
    include('../../clases/Utilities.php'); 
    $db = Utilities::getConnection();
    //
    if(isset($_POST["cod_documento"])){
        session_start();
        $result = $db->desabilitarDoc($_POST['cod_documento']);
        
        if($result==FALSE){
            
            echo "BAD";
        }
        else{
            
            echo "OK";
        }
    }
    
?>