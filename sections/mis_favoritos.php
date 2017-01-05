 <?php
            include_once('../clases/Utilities.php'); 
            $db = Utilities::getConnection();
           
            
            $listDoc = $db->getListFav($_SESSION['user']);
            if(sizeof($listDoc)==0){
                echo "<div class='publicacion'>";
                echo "<h2>Aun no tiene Favoritos agregados</h2>";
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
                /*echo '<img src =\'resources/like.png\' id=\''.$listDoc[$i]['cod_documento'].'\'  class = \'like\' width=20px  height = 20px>';
                echo '<img src =\'resources/dislike.png\' id=\''.$listDoc[$i]['cod_documento'].'\' class = \'dislike\'  width=20px  height = 20px>';
                echo '<img src =\'resources/Button-Favorite-icon.png\' id=\''.$listDoc[$i]['cod_documento'].'\' class = \'favorito\'  width=20px  height = 20px>';*/
                echo '<p id=\''.($listDoc[$i]['cod_documento']-1000).'\'> <b>Valoracion</b>:'.$listDoc[$i]['valoracion'].'</p>';
                ?>
                </div>   
                <?php
                
            }
            
    ?>   