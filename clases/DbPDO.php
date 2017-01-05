<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//ini_set("xdebug.var_display_max_children", -1);
//ini_set("xdebug.var_display_max_data", -1);
//ini_set("xdebug.var_display_max_depth", -1);
//error_reporting(E_ALL);
    class DbPDO{
        private $host;
        private $password;
        private $dbname;
        private $port;
        private $username;
        private $conn;
        private $driverName;
        
        private $extensions= array("jpeg","png","gif","jpg");
        private $path="uploads/";
        
        public function __construct($driverName, $host, $port, $username, $password, $dbname) {
                $this->host = $host;
                $this->password = $password;
                $this->dbname = $dbname;
                $this->port = $port;
                $this->username = $username;
                $this->driverName = $driverName;
                $this->connect();
        }
        
        private function connect() {
            switch($this->driverName) {
                case 'sqlsrv':
                    $dsn = $this->driverName . ":Server=" . $this->host . ";Database=" . $this->dbname;
                    break;
                default:
                    $dsn = $this->driverName.':dbname='.$this->dbname.';host='.$this->host.'; port = '.$this->port.'';
            }

            try {
                $this->conn = new PDO($dsn, $this->username, $this->password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            } catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
                return false;
            }

        }
        
        public function execSelect ($sql){
            return $this->conn->query($sql);
        }
        
        public function exec ($sql){
            return $this->conn->exec($sql);
        }
        
        public function closeConn(){
            $this->conn=null;
        }
        
        public function getConn(){
            return $this->conn;
        }
        /*Entities:Categorias Methods*/
        public function insertCategory($array){
            try{ 
                
                $prep = $this->conn->prepare('INSERT INTO categorias
                                            (name_categoria,descripcion)
                                                         VALUES (?,?);');
                $prep->bindParam(1,$array["name"]);
                $prep->bindParam(2,$array["description"]);
                $prep->execute(); 
            }
            catch(PDOException $e){
                echo 'Connection failed:<br><br> ' . $e->getMessage();
                return false;
            }
            return $prep;
        }
        
        public function getCategory($name){
            $sql = 'SELECT cod_categoria,name_categoria
                 FROM categorias where name_categoria = \''.$name.'\';';
            try{
                $result = $this->execSelect($sql);
                
                return $result ? $result->fetch(PDO::FETCH_ASSOC) : null;         
            }
            catch(PDOException $e){
                echo 'Connection failed:<br><br> ' . $e->getMessage();
                return false;
            }
        }
        public function getlistCategory(){
            $sql = 'SELECT cod_categoria,name_categoria ,descripcion
                    FROM categorias;';
            try{
                $result = $this->execSelect($sql);
                
                return $result ;      
            }
            catch(PDOException $e){
                echo 'Connection failed:<br><br> ' . $e->getMessage();
                return false;
            }
        }
        /*Entities:sub_categorias Methods*/
        public function insertSubCategory($array){
            try{ 
                
                $prep = $this->conn->prepare('INSERT INTO sub_categorias
                                            (name_sub_categoria
                                            ,cod_categoria
                                            ,descripcion)
                                              VALUES (?,?,?);');
                $prep->bindParam(1,$array["name_sub_categoria"]);
                $prep->bindParam(2,$array["cod_categoria"]);
                $prep->bindParam(3,$array["description"]);
                $prep->execute(); 
            }
            catch(PDOException $e){
                echo 'Connection failed:<br><br> ' . $e->getMessage();
                return false;
            }
            return $prep;
        }
        
        public function getlistSubCategory($cod_category){
            $sql = 'SELECT cod_sub_categoria
                    ,name_sub_categoria
                    ,cod_categoria
                    ,descripcion
                FROM sub_categorias
                where cod_categoria ='.$cod_category.';';
            
            try{
                $result = $this->execSelect($sql);
                return $result ;      
            }
            catch(PDOException $e){
                echo 'Connection failed:<br><br> ' . $e->getMessage();
                return false;
            }
        }
        public function getlistSubCategorybyName($name_category){
            $sql = 'SELECT cod_sub_categoria
                    ,name_sub_categoria
                    ,cod_categoria
                    ,descripcion
                FROM sub_categorias
                where cod_categoria = (
                select cod_categoria from categorias where name_categoria = \''.$name_category.'\' );';
            
            try{
                $result = $this->execSelect($sql);
                return $result ;      
            }
            catch(PDOException $e){
                echo 'Connection failed:<br><br> ' . $e->getMessage();
                return false;
            }
        }
        
        
        public function getSubCategory($name_subcategory){
            $sql = 'SELECT cod_sub_categoria 
                FROM sub_categorias
                where name_sub_categoria =\''.$name_subcategory.'\';';
          
            try{
                $result = $this->execSelect($sql);
                $id = $result->fetch(PDO::FETCH_NUM);
                
                return $id[0] ;      
            }
            catch(PDOException $e){
                echo 'Connection failed:<br><br> ' . $e->getMessage();
                return false;
            }
        }
        
        public function getNameCategoryandSubCategory($codSubCategory){
            $sql="select c.name_categoria,sc.name_sub_categoria 
                from sub_categorias sc inner join categorias c 
                on c.cod_categoria = sc.cod_categoria 
                where sc.cod_sub_categoria = ".$codSubCategory."";
            
            try{
                $statement = $this->conn->prepare($sql);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                //var_dump($result);exit();
                return $result[0]['name_categoria']." ->".$result[0]['name_sub_categoria'];  
                return $result;    
            }
            catch(PDOException $e){
                echo 'Connection failed:<br><br> ' . $e->getMessage();
                return false;
            }
              
            
        }
        
        /*Entities:Usuario Methods-----------------------------------------*/
        public function registrarUsuario($array){
            try{
                $result = $this->execSelect('select max(cod_usuario) from usuarios');
                //print("Return next row as an array indexed by column name\n <br>");
                $result= $result->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT);
                
                if($result[0]==null){
                    $cod = 1;
                }
                else{
                    $cod=$result[0]+1;
                }
                
                $prep = $this->conn->prepare('INSERT INTO usuarios
                                                (nombre
                                                ,tipo_usuario
                                                ,sexo
                                                ,correo
                                                ,clave
                                                ,estado)
                                          VALUES
                                               (?,?,?,?,?,?);');
              
                $prep->bindParam(1,$array["name"]);
                $prep->bindParam(2,$array["userType"]);
                $prep->bindParam(3,$array["gender"]);
                $prep->bindParam(4,$array["email"]);
                $prep->bindParam(5,$array["password"]);
                $prep->bindParam(6,$array["status"]);
                
        
                //var_dump($array["status"]);exit();
                $prep->execute(); 
            }
            catch(PDOException $e){
                echo 'Connection failed:<br><br> ' . $e->getMessage();
                return false;
            }
            return $prep;
        }
        
        public function modificarUsuariofull($array,$file){
        $file_n = null;
        $file_ext= null;
       
        $ubicacion=($array['direccion']!="" ? $this->insertUbicacion($array['direccion'],$array['direccion_provincia']):null);
        if($file['size']!=0){

            $file_name = $file['name'];
            $file_size = $file['size'];
            $file_tmp = $file['tmp_name'];
            $file_type = $file['type'];

            //pick the file extens
            //ion
            $part = explode('.',$file_name);
            $file_n = strtolower($part[0]);
            $file_ext=strtolower($part[1]);

            if(in_array($file_ext,$this->extensions)===false){
                 return -1;
             }
             else{
                //abrir la foto original
                if($file_ext == 'jpeg' || $file_ext == 'jpg'){
                    $original=  imagecreatefromjpeg($file_tmp);
                }
                else if ($file_ext == 'png'){
                    $original= imagecreatefrompng($file_tmp);
                }
                else if ($file_ext == 'gif'){
                    $original = imagecreatefromgif($file_tmp);
                    var_dump($original);exit();
                }
                else{
                    /*no se pudo generar la imagen*/
                    return -2;
                }
             }

            $ancho_original = imagesx($original);
            $alto_original = imagesy($original);

            //crear un lienzo vacio
            $ancho_nuevo = 100;
            $alto_nuevo = round($ancho_nuevo * $alto_original / $ancho_original);
            $copia = imagecreatetruecolor($ancho_nuevo, $alto_nuevo);

            //copiar original -> copia
            $dst_image= $copia;
            $src_image = $original;  
            $dst_x = 0;        
            $dst_y = 0;
            $src_x = 0;
            $src_y = 0;
            $dst_w = $ancho_nuevo;
            $dst_h = $alto_nuevo;
            $src_w = $ancho_original;
            $src_h =$alto_original;           

            imagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

            //exportar/guardar imagen
             if($file_ext == 'jpeg' || $file_ext == 'jpg'){
                 imagejpeg($copia, $this->path.$file_n.'2.'.$file_ext);
             }
             else if ($file_ext == 'png'){
                 imagepng($copia, $this->path.$file_n.'2.'.$file_ext);

             }
             else if ($file_ext == 'gif'){
                 imagegif($copia,$this->path.$file_n.'2.'.$file_ext);
             }
             else{
                 die('no grabo nada');exit();
             }

            imagedestroy($original);
            imagedestroy($copia);

            /*Insert into database here*/
            move_uploaded_file($file_tmp,$this->path.$file_name);
            //var_dump($postdata);exit();
        }
        try{

            
            $prep = $this->conn->prepare('UPDATE usuarios
                                            SET nombre = ?
                                               ,direccion = ?
                                               ,sexo = ?
                                               ,correo = ?
                                               ,foto = ?
                                               ,telefono = ?
                                               ,whatsapp = ?
                                               ,web = ?
                                               ,fecha_nacimiento = ?
                                               ,estado_civil = ?
                                               ,identificacion = ?
                                               ,cod_ubicacion = ?
                                          WHERE cod_usuario = ?;');
            
            $prep->bindParam(1,$array["nombre"]);
            $prep->bindParam(2,$ubicacion);
            $prep->bindParam(3,$array["gender"]);
            $prep->bindParam(4,$array["correo"]);
            //echo $this->path.$file_n.'2.'.$file_ext;exit();
            $fotopic = ($file['size'] != 0 ?$this->path.$file_n.'2.'.$file_ext:null);
            $prep->bindParam(5, $fotopic);
            $prep->bindParam(6,$array["telefono"]);
            $prep->bindParam(7,$array["whatsapp"]);
            $prep->bindParam(8,$array["web"]);
            $prep->bindParam(9,$array["fecha_nacimiento"]);
            $prep->bindParam(10,$array["estado"]);
            $prep->bindParam(11,$array["identificacion"]);
            $prep->bindParam(12,$ubicacion);
            $prep->bindParam(13,$_SESSION['user']);
            //var_dump($array["status"]);exit();
            $prep->execute(); 
        }
        catch(PDOException $e){
            echo 'Connection failed:<br><br> ' . $e->getMessage();
            return false;
        }
        return $prep;
        }

        public function modificarUsuario($array){
            try{
                $prep = $this->conn->prepare('UPDATE usuarios
                                               SET 
                                                  nombre = ?
                                                  ,sexo = ?
                                                  ,correo = ?
                                                  ,clave = ?
                                                  ,web = ?
                                                  ,fehca_registro = ?
                                                  ,estado = ?
                                             WHERE codigo_usuario = ?');
                $prep->bindParam(1,$array[0]);
                $prep->bindParam(2,$array[1]);
                $prep->bindParam(3,$array[2]);
                $prep->bindParam(4,$array[3]);
                $prep->bindParam(5,$array[4]);
                $prep->bindParam(6,$array[5]);
                $prep->bindParam(7,$array[6]);
                $prep->bindParam(8,$array[7]);
                $prep->execute();
            }
            catch(PDOException $e){
                echo 'Connection failed:<br><br> ' . $e->getMessage();
                return false;
            }
            return $prep;
        }

        public function getUsuarios(){
            $result;
            $sql = 'SELECT *
                    FROM usuarios where estado = 0';
            try{
                $statement = $this->conn->prepare($sql);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                //var_dump($result);exit();
                return $result;  
                return $result;    
            }
            catch(PDOException $e){
                echo 'Connection failed:<br><br> ' . $e->getMessage();
                return false;
            }
              
        }

        public function getUsuario($cod){
            $result;
            $sql = 'SELECT *
                    FROM usuarios where estado = 0 and cod_usuario ='.$cod;
            try{
                $result = $this->execSelect($sql); 
                //var_dump($result);    
                return $result ? $result->fetch(PDO::FETCH_ASSOC) : null;     
            }
            catch(PDOException $e){
                echo 'Connection failed:<br><br> '. $e->getMessage();
                return false;
            }
              
        }
        
        public function isAdminUser($cod){
            $result;
            $sql = 'SELECT *
                    FROM usuarios where estado = 0 and cod_usuario ='.$cod;
            try{
                $result = $this->execSelect($sql); 
                //var_dump($result);    
                $result = $result ? $result->fetch(PDO::FETCH_ASSOC) : null;    
                if((!$result == null)&& $result['tipo_usuario']==2){
                    return true;
                }
            }
            catch(PDOException $e){
                echo 'Connection failed:<br><br> '. $e->getMessage();
                return false;
            }
            return false;
        }

        public function login($user,$pass){
            $result;
            $sql = 'SELECT cod_usuario
                    FROM usuarios where estado = 0 and nombre =\''.$user.'\' and clave=\''.$pass.'\'';
             //echo $sql;   
            try{
                $result = $this->execSelect($sql); 
                //var_dump($result);    
                return $result ? $result->fetch(PDO::FETCH_ASSOC) : null;     
            }
            catch(PDOException $e){
                
                return null;
            }

        }
        
        public function getUsers($array){
            try{
            $prep = $this->conn->prepare ("SELECT cod_usuario ,nombre,direccion
                        ,sexo,correo,foto,telefono
                        ,whatsapp,web,fecha_nacimiento
                        ,fecha_registro,estado_civil
                        ,identificacion,estado
                    FROM usuarios 
                    WHERE tipo_usuario = 1
                     AND correo like '".$array['correo']."%' 
                     AND nombre  like '".$array['nombre']."%'
                     AND sexo like '".$array['sexo']."%'
                     AND estado_civil like '".$array['estado_civil']."%'
                     AND cod_ubicacion in 
                     (select cod_ubicacion from ubicaciones where cod_pais in 
                     (select cod_pais from pais where nicename like '".$array['pais']."%'))");
            
            $prep->execute();
            //$prep->debugDumpParams();
            $result = $prep->fetchAll(PDO::FETCH_ASSOC);
            }
            catch(PDOException $e){
                echo 'Connection failed:<br><br> ' . $e->getMessage();
                return false;
            }
            return $result;
        }
        
        public function thereAreUser($email){
            $sql = "select cod_usuario,nombre,detalle,estado from usuarios where correo = '".$email."'";
            try{
                $prep = $this->conn->prepare($sql);
                $prep->execute();
                $result = $prep->fetchAll(PDO::FETCH_ASSOC);
                if(sizeof($result)==0){
                    return false;
                }
                else{
                    $Detalle = $this->setChangePassStatus($email);
                    $prep = $this->conn->prepare($sql);
                    $prep->execute();
                    $result = $prep->fetchAll(PDO::FETCH_ASSOC);
                    return $result;
                }
            }
            catch(PDOException $e){
                echo 'Connection failed:<br><br> ' . $e->getMessage();
                return false;
            }
        }
        
        public function checkSecurityUserCode($userName,$securityCode){
            $sql = "select cod_usuario,nombre,detalle,estado from usuarios "
                    . "where nombre = '".$userName."' "
                    . "and detalle = '".$securityCode."' and estado = 2 ";
            try{
                $prep = $this->conn->prepare($sql);
                $prep->execute();
                $result = $prep->fetchAll(PDO::FETCH_ASSOC);
                if(sizeof($result)==0){
                    return false;
                }
                else{
                    return true;
                }
            }
            catch(PDOException $e){
                echo 'Connection failed:<br><br> ' . $e->getMessage();
                return false;
            }
            
        }
        
        public function setChangePassStatus($email){
            $Detalle = rand(100,9000); 
            $sql = "UPDATE usuarios
                    SET 
                      estado = 2,
                      detalle = '".$Detalle."'
                  WHERE correo = '".$email."'";
            try{
                $prep = $this->conn->prepare($sql);
                $prep->execute();
               
            }
            catch(PDOException $e){
                echo 'Connection failed:<br><br> ' .$e->getMessage();
                return false;
            }
        }
        
        public function changePass($userName,$clave,$securityCode){
            $Detalle = rand(100,9000); 
            $sql = "UPDATE usuarios
                    SET 
                      estado = 0,
                      clave = '".$clave."',
                      detalle = '".$Detalle."'    
                  WHERE nombre = '".$userName."' "
                    ."AND detalle = '".$securityCode."'";
            try{
                $prep = $this->conn->prepare($sql);
                $prep->execute();
                return true;
            }
            catch(PDOException $e){
                echo 'Connection failed:<br><br> ' .$e->getMessage();
                return false;
            }
        }
        public function getUserStatus($email){
            $sql = "select  estado from usuarios where correo = '".$email."'";
            try{
                $prep = $this->conn->prepare($sql);
                $prep->execute();
                $result = $prep->fetchAll(PDO::FETCH_ASSOC);
                if(sizeof($result)==0){
                    return false;
                }
                else
                    return $result;
            }
            catch(PDOException $e){
                echo 'Connection failed:<br><br> ' . $e->getMessage();
                return false;
            }
        }
        /*Entities:documentos Methods-----------------------------------------*/
        
         public function insertDocumento($array){
            try{
                $prep = $this->conn->prepare('INSERT INTO documento
                                                (cod_usuario
                                                ,cod_tipo_documento
                                                ,texto
                                                ,ubicacion
                                                ,foto
                                                ,valoracion
                                                ,cod_sub_categoria
                                                ,vinculo
                                                ,estado
                                                ,tumbnail)
                                             VALUES
                                                (?
                                                ,?
                                                ,?
                                                ,?
                                                ,?
                                                ,?
                                                ,?
                                                ,?
                                                ,?
                                                ,?)');
              
                $prep->bindParam(1,$array["cod_usuario"]);
                $prep->bindParam(2,$array["cod_tipo_documento"]);
                $prep->bindParam(3,$array["texto"]);
                $prep->bindParam(4,$array["ubicacion"]);
                $prep->bindParam(5,$array["foto"]);
                $prep->bindParam(6,$array["valoracion"]);
                $prep->bindParam(7,$array["cod_sub_categoria"]);
                $prep->bindParam(8,$array["vinculo"]);
                $prep->bindParam(9,$array["estado"]);
                $prep->bindParam(10,$array["tumbnail"]);
                //var_dump($array["status"]);exit();
                $prep->execute(); 
            }
            catch(PDOException $e){
                echo 'Connection failed:<br><br> ' . $e->getMessage();
                return false;
            }
            return $prep;
        }
        
        function parms($string,$data) {
        $indexed=$data==array_values($data);
        foreach($data as $k=>$v) {
            if(is_string($v)) $v="'$v'";
            if($indexed) $string=preg_replace('/\?/',$v,$string,1);
            else $string=str_replace(":$k",$v,$string);
        }
        return $string;
    }
        
        private function checkValoracion($cod_usuario,$cod_documento){
            $sql = "SELECT cod_documento,valoracion
                    FROM documento 
                    WHERE cod_usuario  = :cod_usuario AND 
                    id_doc_original =:cod_documento AND cod_tipo_documento= 6;";
            $sth =$this->conn->prepare($sql);
            $sth->bindParam(':cod_usuario',$cod_usuario);    
            $sth->bindParam(':cod_documento',$cod_documento); 
            $sth->execute();
            $data=array('cod_usuario'=>$cod_usuario,'cod_documento'=>$cod_documento);
             //echo "<script type='text/javascript'>alert('".$this->parms($sql, $data)."')</script>";
            if($sth->fetchColumn()>0){
                return true;
            }
            else{
                return false;
            }
            
        }
        
        public function getDocument($cod_documento){
            $sql = "select * from "
            . "documento where cod_documento =".$cod_documento;
            //metodo #1 recomendado para un solo resultado
            try {
                $stmt =$this->conn->prepare($sql);
                $stmt->execute();
                return $row = $stmt->fetch();
            }catch(PDOException $e){
                echo 'Connection failed:<br><br> ' . $e->getMessage();
                return false;
            }
        }
        public function consultTipoDocument($array){
            $sql ="SELECT cod_documento
                    ,fecha
                    ,cod_usuario
                    ,cod_tipo_documento
                    ,texto
                    ,ubicacion
                    ,foto
                    ,valoracion
                    ,id_doc_original
                    ,cod_sub_categoria
                    ,vinculo
                        ,estado
                    ,tumbnail
                FROM documento 
                WHERE cod_tipo_documento = ".$array['tipoDoc']."
                AND texto ilike '%".$array['texto']."%'
                AND vinculo ilike '%".$array['correo']."%'
                order by valoracion ".$array['orden']." ";
                
                try{
                    $statement = $this->conn->prepare($sql);
                    $statement->execute();
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                    return $result;
                }
                catch(PDOException $e){
                    echo 'Connection failed:<br><br> ' . $e->getMessage();
                    return false;
                }
        }
        public function consultTipoDocumentWithUbicacion($array){
            $sql ="SELECT cod_documento
                    ,fecha
                    ,cod_usuario
                    ,cod_tipo_documento
                    ,texto
                    ,ubicacion
                    ,foto
                    ,valoracion
                    ,id_doc_original
                    ,cod_sub_categoria
                    ,vinculo
                        ,estado
                    ,tumbnail
                FROM documento 
                WHERE cod_tipo_documento = ?
                AND texto ilike '%?%'
                AND vinculo ilike '%?%'
                AND ubicacion  in 
                      (select cod_ubicacion from ubicaciones where cod_pais in 
                      (select cod_pais from pais where nicename ilike '?%')) 
                      order by valoracion ".$array['orden']."";
            try{
                $statement = $this->conn->prepare($sql);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            }
            catch(PDOException $e){
                echo 'Connection failed:<br><br> ' . $e->getMessage();
                return false;
            }
        }
        
        public function consultDocument($array){
            $sql ="SELECT cod_documento
                    ,fecha
                    ,cod_usuario
                    ,cod_tipo_documento
                    ,texto
                    ,ubicacion
                    ,foto
                    ,valoracion
                    ,id_doc_original
                    ,cod_sub_categoria
                    ,vinculo
                    ,estado
                    ,tumbnail
                FROM documento 
                WHERE cod_tipo_documento < 6
                AND texto ilike '%".$array['texto']."%'
                AND vinculo ilike '%".$array['correo']."%' 
                order by valoracion ".$array['orden']." ";
                    
            try{
                $statement = $this->conn->prepare($sql);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            }
            catch(PDOException $e){
                echo 'Connection failed:<br><br> ' . $e->getMessage();
                return false;
            }
        }
        
        public function consultDocumentWithUbicacion($array){
            $sql ="SELECT cod_documento
                    ,fecha
                    ,cod_usuario
                    ,cod_tipo_documento
                    ,texto
                    ,ubicacion
                    ,foto
                    ,valoracion
                    ,id_doc_original
                    ,cod_sub_categoria
                    ,vinculo
                        ,estado
                    ,tumbnail
                FROM documento 
                WHERE cod_tipo_documento < 6
                AND texto ilike '%?%'
                AND vinculo ilike '%?%' 
                AND ubicacion  in 
                      (select cod_ubicacion from ubicaciones where cod_pais in 
                      (select cod_pais from pais where nicename ilike '?%')) 
                      order by valoracion ".$array['orden']."";
                 //echo $sql;     
            try{
                $statement = $this->conn->prepare($sql);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            }
            catch(PDOException $e){
                echo 'Connection failed:<br><br> ' . $e->getMessage();
                return false;
            }
        }
        
        
        public function insertValoracion($cod_documento,$valoracion){
            session_start();
            $array =  $this ->getDocument($cod_documento);
            
            if($this->checkValoracion($_SESSION['user'],$cod_documento)){
                //Update valoracion
                try{
                    $prep = $this->conn->prepare('UPDATE documento
                                                SET fecha = ?
                                                   ,valoracion = ?
                                                   ,estado = ?
                                              WHERE cod_usuario = ?
                                              AND cod_tipo_documento = ?
                                              AND id_doc_original = ?');
                    $date = date("Y-m-d h:i:sa");
                    $tipo_doc = 6;
                    $prep -> bindParam(1, $date);                          
                    $prep->bindParam(2,$valoracion);
                    $prep->bindParam(3,$array['estado']);
                    $prep->bindParam(4,$_SESSION['user']);
                    $prep->bindParam(5,$tipo_doc);
                    $prep->bindParam(6,$array["cod_documento"]);
                   
                    //var_dump($array["status"]);exit();
                    $prep->execute(); 
                }
                catch(PDOException $e){
                    echo 'Connection failed:<br><br> ' . $e->getMessage();
                    return false;
                }
            }
            else{
                try{
                    $prep = $this->conn->prepare('INSERT INTO documento
                                                    (cod_usuario
                                                    ,cod_tipo_documento
                                                    ,valoracion
                                                    ,cod_sub_categoria
                                                    ,id_doc_original
                                                    ,estado)
                                                 VALUES
                                                    (?,?,?,?,?,?)');
                    $tipo_doc = 6;
                    $estado = 0;
                    
                    $prep->bindParam(1,$_SESSION['user']);
                    $prep->bindParam(2,$tipo_doc);
                    $prep->bindParam(3,$valoracion);
                    $prep->bindParam(4,$array["cod_sub_categoria"]);
                    $prep->bindParam(5,$array["cod_documento"]);
                    $prep->bindParam(6,$estado);

                    //var_dump($array["status"]);exit();
                    $prep->execute(); 
                }
                catch(PDOException $e){
                    echo 'Connection failed:<br><br> ' . $e->getMessage();
                    return false;
                }
                
                
                
            }
            $result = $this->exec('UPDATE documento
                    SET 
                    valoracion = (SELECT SUM (valoracion) FROM documento 
                    where cod_tipo_documento = 6 and id_doc_original = '.$cod_documento.') 
                    WHERE cod_documento = '.$cod_documento.' and cod_tipo_documento < 6');  
        }
        
        public function getValoracion($cod_documento){
            $sql = "SELECT SUM (valoracion) FROM documento 
                where cod_tipo_documento = 6 and id_doc_original = ".$cod_documento.";";
            //metodo #1 recomendado para un solo resultado
            $stmt =$this->conn->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch();
            //echo "<script type='text/javascript'>alert('".  var_dump($row)."')</script>";
            return $row[0];
                
            //metodo #2 para mas de un resultado
            /*foreach ($this->execSelect($sql) as $row) {
                if($tipo_valoracion == '+'){
                    $valoracion = $row['valoracion']+1;
                }
                else if($tipo_valoracion == '-'){
                    $valoracion = $row['valoracion']-1;
                }   
            }*/
        }
       
        public function addFavorito($cod_documento,$cod_usuario){
            $sql="INSERT INTO favoritos
                    (cod_documento
                    ,cod_usuario)
              VALUES
                    (?,?)";
            try{
                $stmt =$this->conn->prepare($sql);
                $stmt->bindParam(1,$cod_documento);
                $stmt->bindParam(2,$cod_usuario);
                $stmt->execute();
                return true;
            }
            catch(PDOException $e){
                echo 'Connection failed:<br><br> ' . $e->getMessage();
                    return false;
            }
            
        }
        
        public function getUbicacionText($codUbicacion){
            
            $sql = "SELECT cod_ubicacion
                ,(select nicename from pais p where p.cod_pais = u.cod_pais)
                ,nombre
            FROM ubicaciones u
                  Where cod_ubicacion = ".$codUbicacion."";
            
             $stmt =$this->conn->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch();
            
            return $row[1].",".$row[2];
        }
        
        
        public function getUbicacion($codUbicacion){
            
            $sql = "SELECT cod_ubicacion
                ,(select nicename from pais p where p.cod_pais = u.cod_pais)
                ,nombre
            FROM ubicaciones u
                  Where cod_ubicacion = ".(int)$codUbicacion."";
            
             $stmt =$this->conn->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch();
            //var_dump($row);
            return $row;
        }
        
        
        public function getListDocSubC($subCat){
            
            $cod_subCat = (int)$this->getSubCategory($subCat);
            
            $sql = 'SELECT cod_documento
                        ,fecha
                        ,cod_usuario
                        ,cod_tipo_documento
                        ,texto
                        ,ubicacion
                        ,foto
                        ,valoracion
                        ,id_doc_original
                        ,cod_sub_categoria
                        ,vinculo
                        ,estado
                        ,tumbnail
                    FROM documento where cod_sub_categoria = '.$cod_subCat.'
                    AND cod_tipo_documento < 6 and estado = 0  order by valoracion desc';
            
            try{
                //var_dump($sql);exit();
                $statement = $this->conn->prepare($sql);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                //var_dump($result);exit();
                return $result;
            }
            catch(PDOException $e){
                echo 'Connection failed:<br><br> ' . $e->getMessage();
                return false;
            }
            
        }
        
        public function desabilitarDoc($codDoc){
            
            $sql="UPDATE documento
                    SET estado = 1
                  WHERE cod_documento = ".$codDoc."";
            try{
                $prep = $this->conn->prepare($sql);
                $prep->execute();
                return true;
            }
            catch(PDOException $e){
                echo 'Connection failed:<br><br> ' . $e->getMessage();
                return false;
            }
        }
        public function getListDocCat($Cat){
            
            $cod_Cat = (int)$this->getCategory($Cat);
            
            $sql = "SELECT cod_documento
                ,fecha
                ,cod_usuario
                ,cod_tipo_documento
                ,texto
                ,ubicacion
                ,foto
                ,valoracion
                ,id_doc_original
                ,cod_sub_categoria
                ,vinculo
                ,estado
                ,tumbnail
            FROM documento
            WHERE cod_sub_categoria in (select cod_sub_categoria from sub_categorias where cod_categoria = (select cod_categoria from categorias where name_categoria = '".$Cat."')) 
            AND cod_tipo_documento < 6 and estado = 0 order by valoracion desc";
            
            try{
                //var_dump($sql);exit();
                $statement = $this->conn->prepare($sql);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                //var_dump($result);exit();
                return $result;
            }
            catch(PDOException $e){
                echo 'Connection failed:<br><br> ' . $e->getMessage();
                return false;
            }
            
        }
        
        public function getListUserDoc($id_user){
            $sql = "SELECT cod_documento
                ,fecha
                ,cod_usuario
                ,cod_tipo_documento
                ,texto
                ,ubicacion
                ,foto
                ,valoracion
                ,id_doc_original
                ,cod_sub_categoria
                ,vinculo
                ,estado
                ,tumbnail
            FROM documento
            WHERE cod_usuario = ".$id_user." and cod_tipo_documento < 6 and estado = 0 order by valoracion desc";
            try{
                $statement = $this->conn->prepare($sql);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            }
            catch(PDOException $e){
                echo 'Connection failed:<br><br> ' . $e->getMessage();
                return false;
            }
        }
        
         public function getListFav($id_user){
            $sql = "SELECT cod_documento
                ,fecha
                ,cod_usuario
                ,cod_tipo_documento
                ,texto
                ,ubicacion
                ,foto
                ,valoracion
                ,id_doc_original
                ,cod_sub_categoria
                ,vinculo
                ,estado
                ,tumbnail
            FROM documento
            where cod_documento in (SELECT cod_documento
            FROM favoritos where cod_usuario = ".$id_user.") and estado = 0 "
                    . "order by valoracion desc";
            try{
                $statement = $this->conn->prepare($sql);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            }
            catch(PDOException $e){
                echo 'Connection failed:<br><br> ' . $e->getMessage();
                return false;
            }
        }
        
        public function getListDoc(){
            $sql = "SELECT cod_documento
                ,fecha
                ,cod_usuario
                ,cod_tipo_documento
                ,texto
                ,ubicacion
                ,foto
                ,valoracion
                ,id_doc_original
                ,cod_sub_categoria
                ,vinculo
                ,estado
                ,tumbnail
            FROM documento
            WHERE cod_tipo_documento < 6 and estado = 0 order by valoracion desc";
            try{
                $statement = $this->conn->prepare($sql);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            }
            catch(PDOException $e){
                echo 'Connection failed:<br><br> ' . $e->getMessage();
                return false;
            }
        }


        public function getListPaises(){
            
            $sql = 'SELECT cod_pais
                    ,nicename
                FROM pais';
            
            
            
            try{
                //var_dump($sql);exit();
                $statement = $this->conn->prepare($sql);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                //var_dump($result);exit();
                return $result;
            }
            catch(PDOException $e){
                echo 'Connection failed:<br><br> ' . $e->getMessage();
                return false;
            }
            
        }
        /*Metodos de ubicacion----------------*/
        public function getPais($nicename){
            $sql="SELECT cod_pais
                ,iso
                ,nombre
                ,nicename
                ,iso3
                ,numcode
                ,phonecode
            FROM pais
            WHERE nicename='".$nicename."'";

            try{
                $prep=$this->conn->prepare($sql);
                $prep->execute();
                $result = $prep->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            }
            catch(PDOException $e){
                echo 'Connection failed:<br><br> ' . $e->getMessage();
                return false;
            }
            
        }

        public function insertUbicacion($cod_pais,$name){
            $sql = "INSERT INTO ubicaciones
                        (cod_pais
                        ,nombre)
                  VALUES (?,?);";
            
            /*$query = "Select nicename from pais where cod_pais =".$cod_pais;
            $sth = $this->conn->prepare($query);
            $sth->execute();
            $nicename = $sth->fetch(PDO::FETCH_ASSOC);
            $cod_pais=$this->getPais($nicename)[0]['cod_pais'];*/
            try{
                $prep=$this->conn->prepare($sql);
                $prep->bindParam(1,$cod_pais);
                $prep->bindParam(2,$name);
                $prep->execute();
                /*-------------------------------------*/
                $sql2 = "SELECT  cod_ubicacion
                        ,cod_pais
                        ,nombre
                    FROM ubicaciones
                    order by cod_ubicacion desc limit 1"; 
                
                $prep2 = $this->conn->prepare($sql2);
                $prep2->execute();
                $result = $prep2->fetchAll(PDO::FETCH_ASSOC)[0]['cod_ubicacion'];
                
                return $result;
            }
            catch(PDOException $e){
                echo 'Connection :p failed:<br><br> ' . $e->getMessage();
                return false;
            }
        }
        /*------------------------------------*/
        
    }
?>  