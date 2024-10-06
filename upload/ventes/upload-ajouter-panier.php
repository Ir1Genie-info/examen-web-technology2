<?php
     include('../connexion.php');
     $conn = new Connexion();
     $con = $conn->get_connexion();
     if(isset($_POST['facture']) && isset($_POST['entree']) && isset($_POST['quantite']) && isset($_POST['pu'])){
        $facture = $_POST['facture'];
        $entree = $_POST['entree'];
        $quantite = $_POST['quantite'];
        $pu = $_POST["pu"];
        $req = $con->prepare("INSERT INTO vente_produit(facture,id_entree_produit,quantite,pvu,pvt) VALUES(?,?,?,?,0)");

        if($req->execute([$facture,$entree,$quantite,$pu])){
            header('Location:../../views/vente.php?action=ajout_facture&facture='.$facture);
        }
     }
     