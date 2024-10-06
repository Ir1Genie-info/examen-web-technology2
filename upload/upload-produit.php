<?php
    include('connexion.php');
    $conn = new Connexion();
    $con = $conn->get_connexion();
    if(isset($_POST['description'])){
        $description = $_POST['description']; 
        $categorie_produit = $_POST['categorie_produit'];
        $idUp = $_POST['idUp'];
        
        $rq = $con->prepare("SELECT * FROM produit where description='$description'");
        $rq->execute();
        $data = $rq->fetch();
        if(empty($idUp)){
            if ($data >0) {
                header("Location:../views/produit.php?msg=Ce produit existe deja&status=error");
            }else {
                $req = $con->prepare("INSERT INTO produit(description,id_categorie_produit) VALUES(?,?)");
            if($req->execute([$description,$categorie_produit])){
                header('Location:../views/produit.php?msg=Enregistrement effectuée avec succès&status=success');
            }
            else{
                header("Location:../views/produit.php?msg=Echec d'enregistrement&status=error");
                
            }
            }
        }
        else{
            $req = $con->prepare("UPDATE produit SET description = ?, id_categorie_produit=? WHERE id = ?");
            if($req->execute([$description,$categorie_produit,$idUp])){
                header('Location:../views/produit.php?msg=La modification effectuée avec succès&status=success');
            }
            else{
                header("Location:../views/produit.php?msg=Echec d'enregistrement&status=error");
            }
        }

    }
    else if(isset($_POST['idDel'])){
        $idDel = $_POST['idDel'];
        $req = $con->prepare("DELETE FROM produit WHERE id = ?");
            if($req->execute([$idDel])){
                header('Location:../views/produit.php?msg=Suppression effectuée avec succès&status=success');
            }
            else{
                header("Location:../views/produit.php?msg=Echec de suppression&status=error");
            }

    }
    else{
        echo 'Erreur';
    }
    
?>