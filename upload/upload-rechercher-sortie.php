<?php
if (!empty($_POST['designation'])){
    $designation = $_POST['designation'];
    header('Location:../views/sortie-produit.php?designation='.$designation);
}
else{
    header('Location:../views/sortie-produit.php');
  }






?>