<?php
    include('connexion.php');
    $conn = new Connexion();
    $con = $conn->get_connexion();
    if(isset($_POST['description']) && isset($_POST['id_entree_produit']) && isset($_POST['quantite'])){
        $idUp = $_POST['idUp'];
        $description = $_POST['description'];
        $id_entree_produit= $_POST['id_entree_produit'];
        $quantite = $_POST['quantite'];
        
        // $rq =$con->prepare("SELECT *FROM sortie_produit where description='$id'");
        // $rq->execute();
        // $date = $rq->fetch();
        if(empty($idUp)){
            $req = $con->prepare("INSERT INTO sortie_produit(date,description,id_entree_produit,quantite) VALUES(NOW(),?,?,?)");
            if($req->execute([$description,$id_entree_produit,$quantite])){
                header('Location:../views/sortie-produit.php?msg=Enregistrement effectuer avec succès&status=success');
            }
            else{
                header("Location:../views/sortie-produit.php?msg=Echec d'enregistrement&status=error");
                 
            }
        } 
        else{
            $req = $con->prepare("UPDATE sortie_produit SET description =?,id_entree_produit=?,quantite=? WHERE id =?");
            if($req->execute([$description,$id_entree_produit,$quantite,$idUp])){
                header('Location:../views/sortie-produit.php?msg=La modification effectuer avec succès&status=success');
            }
            else{
                header("Location:../views/sortie-produit.php?msg=Echec d'enregistrement&status=error");
            }
        }

    }
    else if(isset($_POST['idDel'])){
        $idDel = $_POST['idDel'];
        $req = $con->prepare("DELETE FROM sortie_produit WHERE id= ?");
            if($req->execute([$idDel])){
                header('Location:../views/sortie-produit.php?msg=Suppression effectuer avec succès&status=success');
            }
            else{
                header('Location:../views/sortie-produit.php?msg=Echec de suppression&status=error');
            }

    }

    else{
        echo 'Erreur';
    }
    
?>