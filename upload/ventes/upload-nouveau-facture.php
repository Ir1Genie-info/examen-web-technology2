<?php
     include('../connexion.php');
     $conn = new Connexion();
     $con = $conn->get_connexion();
     $req = $con->prepare("INSERT INTO facture(date) VALUES(NOW())");
     if($req->execute()){
        header('Location:../../views/vente.php?action=ajout_facture');
    }