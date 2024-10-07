<?php
    if(!empty($_POST['produit'])){
        $produit = $_POST['produit'];
        header('Location:../views/entree-produit.php?produit='.$produit);
    }
    else{
        header('Location:../views/entree-produit.php');
    }
   