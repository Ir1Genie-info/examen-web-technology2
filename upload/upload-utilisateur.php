<?php
    include('connexion.php');
    $conn = new Connexion();
    $con = $conn->get_connexion();
    

    if(isset($_POST['submit']) && isset($_FILES['profilePhoto'])){
        $idUp = htmlspecialchars($_POST['idUp']);
        $firstname = htmlspecialchars($_POST['nom']);
        $lastname = htmlspecialchars($_POST['postnom']);
        $username = htmlspecialchars($_POST['nom_utilisateur']);
        $password = htmlspecialchars($_POST['password']);
        $hash = password_hash($password,PASSWORD_DEFAULT);
        $role = htmlspecialchars($_POST['role']);
        
        $fileInfo = pathInfo($_FILES['profilePhoto']['name']);
        $imgname = "img-$username.".$fileInfo['extension'];
                               
        if(empty($idUp)){
            move_uploaded_file($_FILES['profilePhoto']['tmp_name'],'../views/images/ '.$imgname);
            $req = $con->prepare("INSERT INTO utilisateur(nom_utilisateurs,firstname,lastname,password,role,profilePhoto) VALUES(?,?,?,?,?,?)");
            $rows = $req->execute([$username,$firstname,$lastname,$hash,$role,$imgname]);
            if($rows == 1){
                //echo $imgname;
                header('Location:../views/utilisateur.php?msg=Enregistrement effectué avec succès&status=success');
            }
            else{
                header("Location:../views/utilisateur.php?msg=Echec d'enregistrement&status=error");
                            
            }
        }
        else{
            $imgname = "imgUpdate-$username.".$fileInfo['extension'];
            move_uploaded_file($_FILES['profilePhoto']['tmp_name'],'../views/images/ '.$imgname);
            $req = $con->prepare("UPDATE utilisateur SET nom_utilisateurs = ?,firstname=?,lastname=?,password=?, role=?, profilePhoto=? WHERE id = ?");
            $rows = $req->execute([$username,$firstname,$lastname,$hash,$role,$imgname,$idUp]);
            if($rows == 1){
                header('Location:../views/utilisateur.php?msg=La modification effectuée avec succès&status=success');
            }
            else{
                header("Location:../views/utilisateur.php?msg=Echec de modification&status=error");
            }
        }
            
    }
    else if(isset($_POST['idDel'])){
        $idDel = $_POST['idDel'];
        $req = $con->prepare("DELETE FROM utilisateur WHERE id = ?");
        if($req->execute([$idDel])){
            header('Location:../views/utilisateur.php?msg=Suppression effectuée avec succès&status=success');
        }
        else{
            header("Location:../views/utilisateur.php?msg=Echec de suppression&status=error");
        }
            
    }
    else{
        echo 'Erreur';
    }
                    
        
            
        


        
        
    
?>