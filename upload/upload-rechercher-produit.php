<?php
     if(!empty($_POST['designation'])){
        $designation = $_POST['designation'];
        header('Location:../views/produit.php?designation='.$designation);
        
    }
    else {
        header('Location:../views/produit.php');
    }   
?>