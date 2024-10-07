<?php
    session_start();
    require_once "../upload/connexion.php";
    $conn = new Connexion();
    $con = $conn->get_connexion();
    
    if (isset($_POST["username"]) && isset($_POST['password'])) {
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);
        $req = $con->prepare("SELECT * FROM utilisateur WHERE nom_utilisateurs=?");
        $req->execute([$username]);
        $user = $req->fetch();
        
        $pwd = $user->password;
        //die(var_dump(password_verify($password,strval($pwd))));
        if(password_verify($password,strval($pwd))) {
            $_SESSION['id'] = $user->id;
            $_SESSION['username'] = $user->nom_utilisateurs;
            $_SESSION['firstname'] = $user->firstname;
            $_SESSION['lastname'] = $user->lastname;
            $_SESSION['role'] = $user->role;
            $_SESSION['profilePicture'] = $user->profilePhoto;
            //die(var_dump($_SESSION['profilePicture']));

            header("Location:../views/acceuil.php?success");
            exit();
        } else {
            header("Location:../views/index.php?error&msg='Identifiant incorrect. Reessayer svp!!!'");
            exit();
        }
    }
?>
