<?php
    include('connexion.php');
    $conn = new Connexion();
    $conn = $conn->get_connexion();
  

    if(isset($_POST['submit'])){
        
        $idUp = htmlspecialchars($_POST['idUp']);
        // $dates = htmlspecialchars($_POST['date']);
        $produit = htmlspecialchars($_POST['produit']);
        $quantite = htmlspecialchars($_POST['quantite']);
        $prix_achat = htmlspecialchars($_POST['prix_achat']);
        $prix_vente = htmlspecialchars($_POST['prix_vente']);
        $date_expiration = htmlspecialchars($_POST['date_expiration']);
       
        if(empty($idUp)){
            $req = $conn->prepare("INSERT INTO entree_produit(date,id_produit,quantite,prix_achat,prix_vente,date_expiration) VALUES(NOW(),?,?,?,?,?)");
            if($req->execute([$produit,$quantite,$prix_achat,$prix_vente,$date_expiration])){
                header('Location:../views/entree-produit.php?msg=Enregistrement effectuer avec succès&status=success');
            }
            else{
                header("Location:../views/entree-produit.php?msg=Echec d'enregistrement&status=error");
                
            }
        }
        else{
            $req = $conn->prepare("UPDATE entree_produit SET id_produit = ?,quantite = ?,prix_achat = ?,prix_vente = ?,date_expiration = ? WHERE id = ?");
            if($req->execute([$produit,$quantite,$prix_achat,$prix_vente,$date_expiration,$idUp])){
                header('Location:../views/entree-produit.php?msg=La modification effectuer avec succès&status=success');
            }
            else{
                header("Location:../views/entree-produit.php?msg=Echec d'enregistrement&status=error");
            }
        }

    }
    else if(isset($_POST['idDel'])){
        $idDel = $_POST['idDel'];
        $req = $conn->prepare("DELETE FROM entree_produit WHERE id = ?");
            if($req->execute([$idDel])){
                header('Location:../views/entree-produit.php?msg=Suppression effectuer avec succès&status=success');
            }
            else{
                header("Location:../views/entree-produit.php?msg=Echec de suppression&status=error");
            }

    }
    else{
        echo 'Erreur';
    }
    
