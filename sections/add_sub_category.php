<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    if(isset($_POST['submit']))
    {
        
        insert_sub_category();
    }
    
    $name=$description="";
    //$category=$_GET['category'];
    $nameErr=$descriptionErr="";
    $cod_category;
    
    
    
    function insert_sub_category(){
        if(!(empty($_POST['category']) || empty($_POST['subcategory']) ||
                empty($_POST['description']))){
            
            include_once('../clases/Utilities.php');
            $db = Utilities::getConnection();
            $result = $db->getCategory($_POST["category"]);
            
            if($result){
                
                $cod_category=$result['cod_categoria'];
            }
            else{
                echo "Error";exit();
            }
            $array = array(
                "name_sub_categoria"=>$_POST['subcategory'],
                "cod_categoria"=>$cod_category,
                "description"=>$_POST['description']
            );
            $result = $db->insertSubCategory($array);
            
            if(!$result){
                header("Location:index.php?section=add_sub_category");
                exit();
            }
            else{
               $subcategory = $_POST['subcategory'];
               $category = $_GET["category"];
               $_POST = array();
                header("Location:index.php?subcategory=$subcategory");
                exit(); 
            }
        }
        
    }
    
?>
<div class="publicacion">
<h2>Add Sub_Category</h2>
<p><span class="error">* required field.</span></p>
<form method="post" id="usrform" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"].'?section=add_sub_category');?>">  
  <b>Categoria:</b> <br>  
  <select form="usrform" id="category" name="category">
         <?php 
            include_once('../clases/Utilities.php'); 
            $db = Utilities::getConnection();
            $listCat = $db->getlistCategory();
            
            while (list($cod_categoria,$name_categoria,$descripcion) = 
                $listCat->fetch(PDO::FETCH_BOTH)) {
                echo "<option value='".$name_categoria."' id='".$cod_categoria."' class = 'catt'>$name_categoria</option>";
            }
         ?>
    </select>
  <span class="error">* <?php echo $nameErr;?></span>
  <br><br>
  <b>Sub Categoria:</b> <br><input type="text" name="subcategory" value="<?php echo $name;?>">
  <span class="error">* <?php echo $nameErr;?></span>
  <br><br>
  <b>Descripcion:</b> <br><input type="text" name="description" value="<?php echo $description;?>">
  <span class="error">* <?php echo $descriptionErr;?></span>
  <input type="submit" name="submit" value="GRABAR">  
</form>
<div>
