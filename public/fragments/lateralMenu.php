<!DOCTYPE html>
<?php 
    include('../clases/Utilities.php');
    $db = Utilities::getConnection();
    $isAdmin = $db->isAdminUser($_SESSION['user']);
?>

    <h3>Categorias</h3>
    
          
     <select id="cat" name="categorias" >
         <?php 
            $listCat = $db->getlistCategory();
            echo "<option value=''></option>";
            while (list($cod_categoria,$name_categoria,$descripcion) = 
                $listCat->fetch(PDO::FETCH_BOTH)) {
                echo "<option value='".$name_categoria."' id='".$cod_categoria."' class = 'catt'>$name_categoria</option>";
            }
         ?>
    </select><br>
    
    <h3>Sub Categorias</h3>
    
    <select id="sub_categorias">
        <option id="" value=""></option>
    </select>
    <br>
    <button type="submit" value="Buscar" id="Document">Buscar</button>
    
    <br><br>
    <?php 
        if($isAdmin){echo "<li><b><a href='index.php?section=add_categoria'>Nueva Categoria</a></b></li>";}
        if($isAdmin){echo "<li><b><a href='index.php?section=add_sub_category'>Nueva SubCategoria</a></b></li>";}
        if($isAdmin){echo "<li><b><a href='index.php?section=consult_user'>Consultar Usuarios</a></b></li>";}
        /*Nuevos*/
        if($isAdmin){echo "<li><b><a href='index.php?section=consult_document'>Consultar Documentos</a></b></li>";}
        if(!$isAdmin){echo "<li><b><a href='index.php?section=mis_publicaciones'>Mis Publicaciones</a></b></li>";}
        if(!$isAdmin){echo "<li><b><a href='index.php?section=mis_favoritos'>Favoritos</a></b></li>";}
        if(!$isAdmin){echo "<li><b><a href='index.php?section=perfil_user'>Perfil</a></b></li>";}
        
    ?>
    
      
    
    
</ul>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script>
$(document).on('change','#cat', function(){
     var $this = $(this);
     
      var $categoria = {
         'categoria' :$this.val()
      };
      console.log($this.id);
      $.ajax({
            url: "scripts/getSubCategoriasDataList.php",
            type: "POST",
            data: $categoria,
            success: function(data) {
               console.log(data);
               
               document.getElementById('sub_categorias').innerHTML = data;
               
              
            },
            error: function() {
              alert("errores");
            }
          });
});
$(document).ready(function() {
    $('#Document').on('click', function(){
        var categoria = document.getElementById('cat').value;    
        var sub_categoria = document.getElementById('sub_categorias').value;    
        // similar behavior as an HTTP redirect
        window.location.replace("/index.php?category=".concat(categoria).concat("&sub_category=").concat(sub_categoria));  
        
    });
});
</script>