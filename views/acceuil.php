<?php
ob_start();
$titre="acceuil"
?>

<div class="content">
        <div class="row justify-content-center">
       
            <div class="col-md-8 text-center ">
            <h1 class="my-4 text-primary">Bienvenue à notre Application</h1>
                <h2 class="my-4 bg-success text-white p-3">POLY-PHARMACY</h2>
                <div class="text-center">
                
            <img src=" images/pharmacy-logo.png" class="img-fluid rounded">
            </div>
            <p class="mt-4">Gérez facilement votre pharmacie avec notre application.</p>
            <div class="container float-left">
            <img src="images/client-3.png" class="img-fluid float-left" style="width:48%; margin-right:2%">
            
            </div>
            
                
                
               
            </div>
            
        </div>
    </div>

    <footer class="bg-dark text-white pt-4 content">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>Pharmacie</h5>
                <p>Votre santé, notre priorité. Nous offrons une large gamme de médicaments et de produits de santé.</p>
            </div>
            <div class="col-md-4">
                <h5>Liens Utiles</h5>
                <ul class="list-unstyled">
                    <li><a href="index.php" class="text-white">Accueil</a></li>
                    <li><a href="produit.php" class="text-white">Produits</a></li>
                    <li><a href="contact.php" class="text-white">Contact</a></li>
                    <li><a href="apropos.php" class="text-white">À propos</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Contact</h5>
                <ul class="list-unstyled">
                    <li><i class="fas fa-map-marker-alt"></i> RDC Nord-Kivu/Butembo N°123 Rue Kinshasa</li>
                    <li><i class="fas fa-phone"></i> +243 974  970 0986</li>
                    <li><i class="fas fa-envelope"></i> rokaspharma@gmail.com</li>
                </ul>
            </div>
        </div>
        <div class="text-center py-3">
            © 2024
        </div>
    </div>
</footer>


<?php
$contenu = ob_get_clean();
require('temblate.php')
?>