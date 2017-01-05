
 <?php
            include_once('../clases/Utilities.php'); 
            $db = Utilities::getConnection();
           
            
            $listDoc = $db->getListUserDoc($_SESSION['user']);
            if(sizeof($listDoc)==0){
                echo "<div class='publicacion'>";
                echo "<h2>Actualmente no tiene publicaciones</  h2>";
                echo "</div>";
            }
            for($i=0; $i<sizeof($listDoc); $i++){
                echo '<div class=\'publicacion\' id=\''.$listDoc[$i]['cod_documento'].'\' >';
                ?>
                <?php
                $usuario = $db->getUsuario($listDoc[$i]['cod_usuario']);
                //var_dump($usuario);
                echo $usuario['nombre'].' publicado al '.$listDoc[$i]['fecha'].' <br>';
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
                echo "<img src='resources/Icon-trash.png' id='".$listDoc[$i]['cod_documento']."' class='eliminar'>";
                echo '<p id=\''.($listDoc[$i]['cod_documento']-1000).'\'> <b>Valoracion</b>:'.$listDoc[$i]['valoracion'].'</p>';
                ?>
                </div>   
                <?php
                
            }
            
    ?>
<!--<script src="resources/JQuery3-1-1.html"></script>-->
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script>
 $(document).ready(function() {
     
   
    
   $(".eliminar").on('click', function() {
        //console.log($(this).attr('id'));
        var documento = {
            'cod_documento' : $(this).attr('id'),
        };
        var r = confirm("Deseas eliminar esta publicacion??");
        if (r == true) {
            $.ajax({
            url: "scripts/eliminarDoc.php",
            type: "POST",
            data: documento,
            success: function(data) {
               console.log(data);
               alert("La publicacion se ha eliminado correctamente");
               location.reload();
            },
            error: function() {
              alert("error");
            }
          });
        } 
          
    });
        
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
