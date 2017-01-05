
<div class="muro">
    <?php 
        include_once('../clases/Utilities.php'); 
        echo '<p id></p>';
        $category = isset($_GET['category']) ? $_GET['category'] : 0;
        $sub_category = isset($_GET['sub_category']) ? $_GET['sub_category'] : 0;
        
       
        
        
        if($category == null && $sub_category==null) {
            //show all documents
            
            $listDoc = $db->getListDoc($sub_category);
            if(sizeof($listDoc)==0){
                echo '<div class=\'publicacion\' >';
                echo "<h2>Aun no existen publicaciones en el sitio</h2> <img src ='resources/smailer-triste.png' class='noPubImagen'>";
                echo '</div>';
            }  else {
                echo '<div class=\'publicacion\' >';
               echo "<h2>Publicaciones en todo el sitio</h2>";
               echo '</div>';
            }
            for($i=0; $i<sizeof($listDoc); $i++){
                //  var_dump($listDoc[$i]);
                echo '<div class=\'publicacion\' id=\''.$listDoc[$i]['cod_documento'].'\' >';
                ?>
                <?php
                $usuario = $db->getUsuario($listDoc[$i]['cod_usuario']);
                //var_dump($usuario);
                echo $usuario['nombre'].' a publicado al '.$listDoc[$i]['fecha'].' en '.$db->getNameCategoryandSubCategory($listDoc[$i]['cod_sub_categoria']).' <br>';
                 if($listDoc[$i]['texto']!='')
                    echo "<b>Mensaje:</b>".$listDoc[$i]['texto'].'<br>';
                if($listDoc[$i]['ubicacion']!='')
                    echo "<b>Ubicacion:</b>".$db->getUbicacionText ($listDoc[$i]['ubicacion']).'<br>';
                if($listDoc[$i]['vinculo']!='')
                    echo "<b>Vinculo:</b>".'<a href='.$listDoc[$i]['vinculo'].'>enlace</a><br>';
                echo '<img src =\''.$listDoc[$i]['tumbnail'].'\'><br>';
                echo '<img src =\'resources/like.png\' id=\''.$listDoc[$i]['cod_documento'].'\'  class = \'like\' width=20px  height = 20px>';
                echo '<img src =\'resources/dislike.png\' id=\''.$listDoc[$i]['cod_documento'].'\' class = \'dislike\'  width=20px  height = 20px>';
                echo '<img src =\'resources/Button-Favorite-icon.png\' id=\''.$listDoc[$i]['cod_documento'].'\' class = \'favorito\'  width=20px  height = 20px>';
                echo '<p  id=\''.($listDoc[$i]['cod_documento']-1000).'\'> <b>Valoracion:  </b>'.$listDoc[$i]['valoracion'].'</p>';
                
                ?>
                </div>   
                <?php
                
            }
             
        }
        else if(isset ($category) && $sub_category==''){
            ?>
            
            
            <?php
            //show all documents from this category
            
         
            //$array = $db->getListDocSubC($sub_category);
            //var_dump($array[0]['fecha'].'Prueba');
            //var_dump($db->getListDocSubC($sub_category));exit();
            
            $listDoc = $db->getListDocCat($category);
            //var_dump($listDoc);exit();
            if(sizeof($listDoc)==0){
                echo '<div class=\'publicacion\' >';
                echo "<h2>Aun no existen publicaciones en la categoria ".$_GET['category']."</h2> <img src ='resources/smailer-triste.png' class='noPubImagen'>";
                echo '</div>';
            }  else {
                echo '<div class=\'publicacion\' >';
               echo "<h2>Publicaciones en  Categoria ".$_GET['category']."</h2> ";
               echo '</div>';
            }
            
            for($i=0; $i<sizeof($listDoc); $i++){
                echo '<div class=\'publicacion\' id=\''.$listDoc[$i]['cod_documento'].'\' >';
                ?>
                <?php
                $usuario = $db->getUsuario($listDoc[$i]['cod_usuario']);
                //var_dump($usuario);
                echo $usuario['nombre'].' a publicado al '.$listDoc[$i]['fecha'].'  en '.$db->getNameCategoryandSubCategory($listDoc[$i]['cod_sub_categoria']).' <br>';
                 if($listDoc[$i]['texto']!='')
                    echo "<b>Mensaje:</b>".$listDoc[$i]['texto'].'<br>';
                    if($listDoc[$i]['ubicacion']!='')
                   echo "<b>Ubicacion:</b>".$db->getUbicacionText ($listDoc[$i]['ubicacion']).'<br>';
                if($listDoc[$i]['vinculo']!='')
                    echo "<b>Vinculo:</b>".'<a href='.$listDoc[$i]['vinculo'].'>enlace</a><br>';
                echo '<img src =\''.$listDoc[$i]['tumbnail'].'\'><br>';
                echo '<img src =\'resources/like.png\' id=\''.$listDoc[$i]['cod_documento'].'\'  class = \'like\' width=20px  height = 20px>';
                echo '<img src =\'resources/dislike.png\' id=\''.$listDoc[$i]['cod_documento'].'\' class = \'dislike\'  width=20px  height = 20px>';
                echo '<img src =\'resources/Button-Favorite-icon.png\' id=\''.$listDoc[$i]['cod_documento'].'\' class = \'favorito\'  width=20px  height = 20px>';
                echo '<p  id=\''.($listDoc[$i]['cod_documento']-1000).'\'><b>Valoracion:</b>'.$listDoc[$i]['valoracion'].'</p>';
                ?>
                </div>   
                <?php
                
            }
        }
        else if(isset($category) && isset ($sub_category)){
            ?>
            <div class="formPub">    
                <h2>Nueva Publicacion</h2>
                <form action="index.php?section=upload" method="post" id="PubForm" enctype = "multipart/form-data">
                <label for="mail">Archivo:</label><br>
                <input type="file"  name="image" /><br>
                <label for="msg">Mensaje:</label><br>
                <textarea  name="user_message"></textarea><br>
                <label for="enlace">Enlace:</label><br>
                <textarea  name="user_enlace"></textarea><br>
                <label for="ubicacion">Ubicacion:</label><br>
                
                <select  name="paises" form="PubForm" id="paises" >
                    <option></option>
                <?php
                    $db = Utilities::getConnection();
                    $paises= $db->getListPaises();
                    foreach($paises as $row){
                        echo "<option value='".$row['cod_pais']."' id = '".$row['nicename']."'>".$row['nicename']."</option>";
                    }
                   
                ?>
                </select><br>
                <label for="ubicacion">Provincia + Descripcion:</label><br>
                <input type="text" name="user_ubicacion"></input><br>

                <br>
                <input type="hidden" value="<?php echo $_GET['sub_category']?>" name="sub_category" />
                <input type="hidden" value="<?php echo $_SESSION["user"]?>" name="id_user" />
                <button type="submit">Enviar</button>
                </form>
            </div>
            
            <?php
            //show all documents from this category
            
         
            //$array = $db->getListDocSubC($sub_category);
            //var_dump($array[0]['fecha'].'Prueba');
            //var_dump($db->getListDocSubC($sub_category));exit();
            
            $listDoc = $db->getListDocSubC($sub_category);
            if(sizeof($listDoc)==0){
                echo '<div class="publicacion">';
                echo "<h2>Aun no hay publicaciones en Sub Categoria ".$_GET['sub_category']."</h2> <img src ='resources/smailer-triste.png' class='noPubImagen'>";
                echo '</div>';

            }
            
            else{
                echo '<div class="publicacion">';
                echo "<h2>publicaciones en Sub Categoria ".$_GET['sub_category']."</h2>";
                echo '</div>';
            }
            for($i=0; $i<sizeof($listDoc); $i++){
                
                echo '<div class=\'publicacion\' id=\''.$listDoc[$i]['cod_documento'].'\' >';
                ?>
                <?php
                $usuario = $db->getUsuario($listDoc[$i]['cod_usuario']);
                //var_dump($usuario);
                echo $usuario['nombre'].' a publicado al '.$listDoc[$i]['fecha'].'  en '.$db->getNameCategoryandSubCategory($listDoc[$i]['cod_sub_categoria']).' <br>';
                 if($listDoc[$i]['texto']!='')
                    echo "<b>Mensaje:</b>".$listDoc[$i]['texto'].'<br>';
                if($listDoc[$i]['ubicacion']!='')
                    echo "<b>Ubicacion:</b>".$db->getUbicacionText ($listDoc[$i]['ubicacion']).'<br>';
                if($listDoc[$i]['vinculo']!='')
                    echo "<b>Vinculo:</b>".'<a href='.$listDoc[$i]['vinculo'].'>enlace</a><br>';
                echo '<img src =\''.$listDoc[$i]['tumbnail'].'\'><br>';
                echo '<img src =\'resources/like.png\' id=\''.$listDoc[$i]['cod_documento'].'\'  class = \'like\' >';
                echo '<img src =\'resources/dislike.png\' id=\''.$listDoc[$i]['cod_documento'].'\' class = \'dislike\' >';
                echo '<img src =\'resources/Button-Favorite-icon.png\' id=\''.$listDoc[$i]['cod_documento'].'\' class = \'favorito\' >';
                echo '<p  id=\''.($listDoc[$i]['cod_documento']-1000).'\'><b>Valoracion:</b>'.$listDoc[$i]['valoracion'].'</p>';
                ?>
                </div>   
                <?php
                
            }
            
        }
    ?>   
</div>
<!--<script src="resources/JQuery3-1-1.html"></script>-->
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script>
 $(document).ready(function() {
     
    var values = {
            'category': '<?=$category?>',
            'sub_category': '<?=$sub_category?>'
    }; 
    
   $(".favorito").on('click', function() {
        //console.log($(this).attr('id'));
        var documento = {
            'cod_documento' : $(this).attr('id'),
        };
          $.ajax({
            url: "scripts/favorito.php",
            type: "POST",
            data: documento,
            success: function(data) {
               console.log(data);
               alert("La publicacion  a sido agregada a sus favoritos");
              
            },
            error: function() {
              alert("error");
            }
          });
        });
    
    $(".like").on('click', function() {
        console.log($(this).attr('id'));
        var valoracion = {
            'cod_documento' : $(this).attr('id'),
            'valoracion': 1
        };
          $.ajax({
            url: "scripts/valoracion.php",
            type: "POST",
            data: valoracion,
            success: function(data) {
               //alert(data);
               //example web
             var val = "<b>Valoracion:  </b>";
               document.getElementById(valoracion['cod_documento']-1000).innerHTML =val.concat(data);
              //$("#likeCounts").text(data);
            },
            error: function() {
              alert("error");
            }
          });
        });
        
        $(".dislike").on('click', function() {
        console.log($(this).attr('id'));
        var valoracion = {
            'cod_documento' : $(this).attr('id'),
            'valoracion': -1
        };
          $.ajax({
            url: "scripts/valoracion.php",
            type: "POST",
            data: valoracion,
            success: function(data) {
               //alert(data);
               //Example im Web
               //document.getElementById("p1").innerHTML = "New text!";
               var val = "<b>Valoracion:  </b>";
               document.getElementById(valoracion['cod_documento']-1000).innerHTML =val.concat(data);
               
               
              //$("#likeCounts").text(data);
            },
            error: function() {
              alert("error");
            }
          });
        });
    /*$.ajax({
        url: "scripts/getDocuments.php",
        type: "POST",
        data : values,
        
        success: function(data) {
          var documents =JSON.parse(data);
          console.log(documents);
          for (var index in documents) {
            var document = documents[index];
            console.log(document);
            var img = $("<img>").attr("src", document.tumbnail).attr("id",document.cod_documento.concat("srt"));
            
            var li = $("<li>").append(img);
            $(".muro").append(li);
          }
        },
        error: function() {
          alert("error en usuarios");
        }
    });*/

    });
   </script> 


