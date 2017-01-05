<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php 
if(isset($_POST['submit']))
{
  insert_user();
} 

        $nameErr = $emailErr = $genderErr = $websiteErr = $passwordErr ="";
        $name = $email = $gender = $comment = $website = $password = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          if (empty($_POST["name"])) {
            $nameErr = "Name is required";
          } else {
            $name = test_input($_POST["name"]);
            // check if name only contains letters and whitespace
            if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
              $nameErr = "Only letters and white space allowed"; 
            }
          }

          if (empty($_POST["email"])) {
            $emailErr = "Email is required";
          } else {
            $email = test_input($_POST["email"]);
            // check if e-mail address is well-formed
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
              $emailErr = "Invalid email format"; 
            }
          }

          if (empty($_POST["password"])) {
            $passwordErr = "Password is required";
          } else {
            $password = test_input($_POST["password"]);
          }


          if (empty($_post["website"])) {
            $website = "";
          } else {
            $website = test_input($_POST["website"]);
            // check if URL address syntax is valid (this regular expression also allows dashes in the URL)
            if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website)) {
              $websiteErr = "Invalid URL"; 
            }
          }

          if (empty($_POST["comment"])) {
            $comment = "";
          } else {
            $comment = test_input($_POST["comment"]);
          }

          if (empty($_POST["gender"])) {
            $genderErr = "Gender is required";
          } else {
            $gender = test_input($_POST["gender"]);
          }
        }

        function test_input($data) {
          $data = trim($data);
          $data = stripslashes($data);
          $data = htmlspecialchars($data);
          return $data;
        }



        function insert_user(){
            
                $fecha = new DateTime();
                include('../clases/Utilities.php');
                if(!(empty($_POST["name"]) || empty($_POST["email"]) || empty($_POST["password"]) || empty($_POST["gender"])))
                {
                    
                        $array = array(
                                "name"=>$_POST["name"]
                                ,"gender"=>$_POST["gender"]
                                ,"email"=>$_POST["email"]
                                ,"password"=>$_POST["password"]
                                ,"date"=>$fecha->getTimestamp()
                                ,"status"=>0
                                ,"userType"=>1
                            );	
                        $db = Utilities::getConnection();
                        $result = $db->registrarUsuario($array);
                        if(!$result){
                            
                            header("Location:index.php?section=reg_user");
                            exit();
                        }
                        else{
                           $_POST = array();
                           header("Location:index.php?section=login");
                           exit(); 
                        }
                }
        }

        function update_user(){

                include('clases/Utilities.php');
                if(!(empty($_POST["name"]) || empty($_POST["email"]) || empty($_POST["password"]) || empty($_POST["gender"])))
                {
                        $array = array($_POST["name"],$_POST["gender"],$_POST["email"],$_POST["password"],$_POST["website"],date("Y-m-d h:i:sa") ,0);	
                        $db = Utilities::getConnection();
                        $result = $db->registrarUsuario($array);
                        $_POST = array();

                          header("Location:consult_user.php");
                          exit();

                }
        }

?>
<div class="formCenter">
<h2>Registrarse</h2>
<p><span class="error">* required field.</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"].'?section=reg_user');?>">  
  <b>Name:</b><br> <input type="text" name="name" value="<?php echo $name;?>">
  <span class="error">* <?php echo $nameErr;?></span>
  <br><br>
  <b>E-mail:</b><br> <input type="text" name="email" value="<?php echo $email;?>">
  <span class="error">* <?php echo $emailErr;?></span>
  <br><br>
  <b>Password:</b> <br><input type="password" name="password" value="<?php echo $password;?>">
  <span class="error">* <?php echo $passwordErr;?></span>
  <br><br>
 
  <b>Gender:</b><br>   
  <input type="radio" name="gender" <?php if (isset($gender) && $gender=="female") ?> value="female">Female</input>
  <input type="radio" name="gender" <?php if (isset($gender) && $gender=="male") ?> value="male">Male</input>
    <span class="error">* <?php echo $genderErr;?></span>
  <br><br>
  <input type="submit" name="submit" value="GRABAR">  
</form>
</div>