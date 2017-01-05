<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<?php
    include ('../clases/FileHandle.php');
    $fileHandle = new FileHandle();
    //var_dump($_POST);exit();
    
    $result = $fileHandle->upload($_FILES['image'], $_POST);
   
    if($result = 1){
        header("Location:index.php");
    }
    else if($result = -1){
        die(' extension del archivo no esta permitida');
    }
    else if($result  = -2){
        die('la imagen no se pudo generar');
    }
?>

