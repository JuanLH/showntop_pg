<?php
        include '../clases/Utilities.php';
        $respuesta = '';
        $stage = 0;
        
        if(isset($_POST['recuperar'])){
            $email = $_POST['correo'];
            
            $respuesta=Utilities::sendEmail($email); 
            $stage = 1;
        }
        
        if(isset($_GET['user']) && isset($_GET['securitycode'])){
            
            if(isset($_POST['modificar'])){
                if($_POST['clave1']==$_POST['clave2']){
                    $db = Utilities::getConnection();
                    $isChange = $db->changePass($_GET['user'],
                            $_POST['clave1'],
                            $_GET['securitycode']);
                   
                    if($isChange==true){
                        $stage = 3;
                        $respuesta = 'Su Clave fue modificada <b>exitosamente</b>';
                    }
                }
                else{
                    $stage = 2;
                    $respuesta = "Las claves deben <b>coincidir</b>";
                }
            }
            else{
                $stage = 2;
                $db = Utilities::getConnection();
                if(!$db->checkSecurityUserCode($_GET['user'],$_GET['securitycode'])){
                    $stage = -1;
                }
            }
        }

        switch($stage){
            case 0:
                
            ?>  
                <div class="publicacion">
                <form method="POST" action="">
                        Correo:<input name="correo" type="text" value=""/><br><br>
                        <input name="recuperar" type="submit" value="Enviar"/>
                </form>
                </div>
            <?php    
            break;
            case 1:
            ?>
                <div class="publicacion">
                <h1><?=$respuesta?></h1>
                </div>
            <?php       
            break;
            case 2:
            ?>
                <div class="publicacion">
                 <?=$respuesta?>
                <form method="POST" action="">
                        Clave:<input name="clave1" type="password" value=""/><br><br>
                        Clave:<input name="clave2" type="password" value=""/><br><br>
                        <input name="modificar" type="submit" value="Enviar"/>
                        
                </form>
                </div>    
            <?php       
            break;
            case 3:
            ?>
                <div class="publicacion">
                <h1><?=$respuesta?></h1>
                </div>
            <?php       
            break;
            case -1:
            ?>
                <div class="publicacion">
                <h1>Enlace no disponible</h1>
                </div>
            <?php       
            break;
        
        }
?>

