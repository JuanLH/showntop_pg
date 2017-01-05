<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php 
        $msg=""; 
	if(!isset($_SESSION)) 
        { 
            session_start(); 
        };
	if(isset($_SESSION["user"])){
		header("location:index.php");
		exit;
	}
        
        if(isset($_SESSION["msg"])){
            $_POST = array();
            $msg = "Credenciales incorrectos";
            unset($_SESSION["msg"]);
        }
	if(isset($_POST["submit"])){
		include_once "../clases/Utilities.php";
		$db = Utilities::getConnection();
		$result = $db->login($_POST["user"],$_POST["pass"]);
		//var_dump($result);exit;
		if($result){
                    $_SESSION["user"]=$result["cod_usuario"];
                    header("location:index.php");
                    exit;
		}else{
                    //var_dump($result);exit();
                    $_SESSION["msg"]=-1;
                    header("location:index.php?section=login");
                    exit;
		}
	}
        echo "<div class='formCenter'>";
        echo $msg;
?>

<form method="POST" action="index.php?section=login">
        <b>User Name:</b><br><input name="user" type="text" value=""/><br><br>
        <b>Password:</b><br><input name="pass" type="password" value=""/><br><br>
        <input name="submit" type="submit" value="entrar"/>
        <a href='index.php?section=recuperarClave'>Olvide mi contraseña</a>
</form>
</div>	