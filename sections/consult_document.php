<?php
    include "../clases/Utilities.php";
    $db = Utilities::getConnection();
    $stage = 0;        
    if(isset($_POST['submit'])){
        $text = $_POST['texto'];
        $correo = $_POST['correo'];
        $pais = $_POST['pais'];
        $tipoDoc = $_POST['tipoDoc'];
        
        if($tipoDoc!=''){
            
            if($pais!='')
                $documents = $db->consultTipoDocumentWithUbicacion($_POST);
            else
               $documents = $db->consultTipoDocument ($_POST);
            
        }else{
           
            if($pais!='')
                $documents = $db->consultDocumentWithUbicacion($_POST);
            else
               $documents = $db->consultDocument($_POST);
        }
        $stage =1;
        
    }
?>


<?php
if($stage == 0){
?>
<div class="publicacion">
    <h1>Filtros de Documentos</h1></br>
    <form action="" id="frmDoc" name="frmDoc" method="post" enctype = "multipart/form-data">
    
        <label for="texto">Contenga en texto:</label></br>
        <input type="text" name="texto" value=""></input></br>

        <label for="vinculo">Contenga en vinculo:</label></br>
        <input type="text"  name="correo" value =""></input></br>
        
        

        <label for="pais">En el Pais:</label></br>
       
        <select form="frmDoc" id="paises" name="pais">
            <?php
                $paises= $db->getListPaises();
                foreach($paises as $row){
                    echo "<option value='".$row['nicename']."' id='".$row['cod_pais']."'>".$row['nicename']."</option>";
                }
            ?>
        </select></br></br>
        <label for="tipoDoc">Tipo de Documento</label></br>
        <select  name="tipoDoc">
        
            <option value="">Todos</option>
            <option value="1">Imagen</option>
            <option value="5">Enlace</option>
            <option value="3">Texto</option>
            <option value="0">Variada</option>
        </select></br></br>
        
        <label for="tipoDoc">Ordenar Por:</label></br>
        <select name="orden">
            <option value="desc">Mas Votados</option>
            <option value="asc">Menos Votados</option>
        </select></br></br>
        <button type="submit" name="submit" >BUSCAR</button>
    </form>

</div>  
<?php
}
else{
    if(sizeof($documents)==0){
        echo '<div class="publicacion">';
        echo "<h2> No se encontraron resultados</h2> <img src ='resources/smailer-triste.png' class='noPubImagen'>";
        echo '</div>';

    }
    else{
        echo '<div class="publicacion">';
        echo "<h2><b>".sizeof($documents)."</b> Resultados de La busqueda</h2>";
        echo '</div>';
    }
    for($i=0; $i<sizeof($documents); $i++){

        echo '<div class=\'publicacion\' id=\''.$documents[$i]['cod_documento'].'\' >';
        ?>
        <?php
        $usuario = $db->getUsuario($documents[$i]['cod_usuario']);
        //var_dump($usuario);
        echo $usuario['nombre'].' publicado al '.$documents[$i]['fecha'].' <br>';
         if($documents[$i]['texto']!='')
            echo "<b>Mensaje:</b>".$documents[$i]['texto'].'<br>';
        if($documents[$i]['ubicacion']!='')
            echo "<b>Ubicacion:</b>".$db->getUbicacionText ($documents[$i]['ubicacion']).'<br>';
        if($documents[$i]['vinculo']!='')
            echo "<b>Vinculo:</b>".'<a href='.$documents[$i]['vinculo'].'>enlace</a><br>';
        echo '<img src =\''.$documents[$i]['tumbnail'].'\'><br>';
        
        echo '<p  id=\''.($documents[$i]['cod_documento']-1000).'\'><b>Valoracion:</b>'.$documents[$i]['valoracion'].'</p>';
        ?>
        </div>   
        <?php

    }
}
?>