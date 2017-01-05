<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FileHandle
 *
 * @author Juan Luis Hiciano
 * RETURN CODE
 *  -1 = extension not allowed
 */
class FileHandle {
    //put your code here
    private $extensions= array("jpeg","png","gif","jpg");
    private $path="uploads/";
    /*
     *  RETURN CODE
     *  -1 = extension not allowed  
     *  -2 = Image doesn't could be generate
     *   1 = success
     */
    public function upload($file,$postdata){
       /* ## */
       //var_dump($file);
       //var_dump($postdata);exit();
        
       if($file['size']!=0){
            $id_num = rand(0, 50000);
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
                }
                else{
                    /*no se pudo generar la imagen*/
                    echo "Error";
                    return -2;
                }
                
             }

            $ancho_original = imagesx($original);
            $alto_original = imagesy($original);

            //crear un lienzo vacio
            $ancho_nuevo = 250;
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
                 $result = imagejpeg($copia, $this->path.$id_num.$file_n.'2.'.$file_ext);
             }
             else if ($file_ext == 'png'){
                 $result = imagepng($copia, $this->path.$id_num.$file_n.'2.'.$file_ext);

             }
             else if ($file_ext == 'gif'){
                 $result = imagegif($copia,$this->path.$id_num.$file_n.'2.'.$file_ext);
             }
             else{
                 die('no grabo nada');exit();
             }
            
            imagedestroy($original);
            imagedestroy($copia);

            /*Insert into database here*/
            $res = move_uploaded_file($file_tmp,$this->path.$id_num.$file_name);
            
       }
        




        if(($file['size'] != 0 ||($file['size'] != 0 && $postdata['user_message']!=''))&& !($postdata['user_enlace']!='')){
            $tipo_doc=1;//imagen
        }
        else if($file['size'] == 0 && ($postdata['user_enlace']!='' 
                || ($postdata['user_enlace']!='' && $postdata['user_message']!=''))){
            $tipo_doc=5;//enlace
        }
        else if(!($file['size'] != 0 && $postdata['user_enlace']!='')&& $postdata['user_message']!=''){
            $tipo_doc=3;//texto
        }
        else if( $file['size'] != 0  && $postdata['user_enlace']!='' && $postdata['user_message']!=''){
            $tipo_doc=0;//Variado
        }

        include('../clases/Utilities.php'); 
        $db = Utilities::getConnection();
        $ubicacion=( $postdata['paises']!="" ? $db->insertUbicacion($postdata['paises'],$postdata['user_ubicacion']):null);
        //var_dump($db->insertUbicacion($postdata['paises'],$postdata['user_ubicacion']));  exit();
        $array = array(
            "cod_usuario" => $postdata['id_user'],
            'cod_tipo_documento'=> (int)$tipo_doc,
            'ubicacion'=> $ubicacion,
            'foto'=> ($file['size'] != 0? $this->path.$id_num.$file_name:null),
            'texto'=> $postdata['user_message'],
            'valoracion'=> 0,
            'cod_sub_categoria'=> (int)$db->getSubCategory($postdata['sub_category']),
            'vinculo'=>$postdata['user_enlace'],
            'estado'=>0,
            'tumbnail'=>($file['size'] != 0 ?$this->path.$id_num.$file_n.'2.'.$file_ext:null) 
        );
        $result = $db->insertDocumento($array);
        
        return 1;
       
        
   }
   
   public function getFile($name){}
   
}
