<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<div>       
<?php
    if(isset($_SESSION['user'])){
        /*Contenido con el usuario logueado*/
        echo "<div class='lateralMenu'>";
            include ('fragments/lateralMenu.php');
        echo "</div>";    
        include ('../sections/muro.php');
    }
    else{
        echo "<div class='publicacion'>";
        echo '<p><h2>Esta es una pagina de practica para proyecto de Diseño';
        echo ' y programacion de paginas web</h2></p><br>Usted podra compartir '
        . 'publicaciones en la categoria que mas  le encaje. Esto es para facilitar la busqueda  del contenido deseado.';
        ?><br>
        <h3>Aqui podra hacer lo siguiente</h3>
        <ul>
            <li>Crear usuarios</li>
            <li>Compartir imagenes,enlaces o mensajes con ubicaciones</li>
            <li>Valorar las publicaciones y ver las mejor valoradas en la cima del listado</li>
            <li>Buscar Publicaciones por categorias y sub categorias</li>
        </ul>
        <?php
      
        echo "</div>";
    }
?>

