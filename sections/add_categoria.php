<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
 
if(isset($_POST['submit']))
{
    insert_category();
}

$name=$description="";
$nameErr=$descriptionErr="";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(empty($_POST["name"])){$nameErr="name is required";}
    else{
        $name = test_input($_POST["name"]);
        if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
            $nameErr = "Only letters and white space allowed"; 
        }        
    }
    if(empty($_POST["description"])){$descriptionErr="name is required";}
    else{
        $name = test_input($_POST["description"]);
        if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
            $descriptionErr = "Only letters and white space allowed"; 
        }        
    }
    
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function insert_category(){
   include('../clases/Utilities.php'); 
   //var_dump($_POST);exit();
   if(!(empty($_POST["name"]) || empty($_POST["description"]))){
       $array = array(
           "name"=>$_POST["name"],
           "description"=>$_POST["description"]
       );
       
       $db = Utilities::getConnection();
       $result = $db->insertCategory($array);
       //var_dump($result);exit();
       if(!$result){
           
       }
       else{
            $category_name = $_POST["name"];
            $_POST = array();
            header("Location:index.php?section=add_sub_category&category=$category_name");
            exit();     
       }
   } 
}
   
?>
<div class="publicacion">
<h2>add category</h2>
<p><span class="error">* required field.</span></p>
<form method="post" id="usrform" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"].'?section=add_categoria');?>">  
  <b>Name: </b><br><input type="text" name="name" value="<?php echo $name;?>">
  <span class="error">* <?php echo $nameErr;?></span>
  <br><br>
  <b>Descripcion:</b><br> <input type="text" name="description" value="<?php echo $description;?>">
  <span class="error">* <?php echo $descriptionErr;?></span>
  <input type="submit" name="submit" value="GRABAR">  
</form>
</div>

