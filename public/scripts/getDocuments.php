<?php
$category = $_POST['category'];
$sub_category = $_POST['sub_category'];
include_once ('../../clases/Utilities.php'); 

$db = Utilities::getConnection();
echo json_encode($db->getListDocSubC($_POST['sub_category']));
//echo 'hello';
/*if($category == null && $sub_category==null) {
    //show all documents
}
else if(isset ($category) && $sub_category==null){
    //show all documents from this category

}
else if($category== null && isset ($sub_category)){
   //echo var_dump($db->getListDocSubC($sub_category));
   echo $db->getListDocSubC($sub_category);
}*/
        
        
       

