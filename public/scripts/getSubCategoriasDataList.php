
<?php

include '../../clases/Utilities.php';
$db = Utilities::getConnection();
$listSubCat=$db->getlistSubCategorybyName($_POST['categoria']);
//var_dump($listSubCat);
echo "<option>    </option>";
while(list($cod_sub_cat,$name_sub_cat,$cod_cat,$desc) =     
            $listSubCat->fetch(PDO::FETCH_NUM)){
         echo "<option value =".$name_sub_cat.">".$name_sub_cat."</option>";
    }
    
?>
 
