<?php
    if(!empty($_POST['description'])){
        $description = $_POST['description'];
        header('Location:../views/categorie-produit.php?description='.$description);
     }
    else {
        header('Location:../categorie-produit.php');
    }
    
?>