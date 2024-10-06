<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interface de Connexion</title>
    <!-- Lien vers le fichier CSS de Bootstrap 5.3 -->
    <link href="../bootstrap/css/bootstrap.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100 ">
            <div class=" col-md-5 text-white border-lg shadow rounded-4" style="background-color:#0f1b27 ;" >
                <h3 class="text-center  mb-4 fs-1 ">Connexion</h3>

                <!-- Afficher l'alerte si l'URL contient ?error -->
                <?php
                    if(isset($_GET['error'])) {
                        ?>
                            <div class="alert alert-danger" role="alert">
                            Identifiants incorrects. Veuillez réessayer.
                            </div>
                        <?php
                    }
                   
                ?>
              

                <form action="../upload/upload-index.php" method="POST" class="row" >
                    <div class="mb-3 mr-4 ml-4">
                        <label for="username" class="form-label fs-4 fw-medium fst-italic">Nom d'utilisateur :</label>
                        <input type="text" class="form-control " id="username" name="username" placeholder="Entrez votre nom d'utilisateur" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label fs-4 fw-medium fst-italic">Mot de passe :</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Entrez votre mot de passe" required>
                    </div>
                    <div class="d-grid mb-5 mt-3">
                        <button type="submit" class="btn btn-success fs-4">Se connecter</button>
                    </div>
                </form>
                <!-- <div class="text-center mt-3">
                    <p class="mb-0">Vous n'avez pas de compte ?</p>
                    <a href="signin.php" class="text-primary">Créer un compte</a>
                </div> -->
            </div>
        </div>
    </div>

    <!-- Lien vers le fichier JS de Bootstrap 5.3 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
