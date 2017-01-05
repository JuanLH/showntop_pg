
<?php
    include('../../clases/Utilities.php'); 
    $db = Utilities::getConnection();
    //echo "<script type='text/javascript'>alert('".$_POST["cod_documento"]."')</script>";
    if(isset($_POST["cod_documento"])){
        $db->insertValoracion($_POST['cod_documento'],$_POST['valoracion']);
        echo $db->getValoracion($_POST['cod_documento']);
    }
    
    
    
?>
  
