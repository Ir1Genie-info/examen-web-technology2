<?php
    ob_start();
    include('../upload/connexion.php');
    $titre = "Gestion des Entrées des produits";
    $database = new Connexion();
    $con = $database->get_connexion();
    $req = $con->prepare("SELECT entree_produit.id,produit.description, entree_produit.date AS date, entree_produit.id_produit AS produit, entree_produit.quantite AS quantite, entree_produit.prix_achat AS prix_achat,entree_produit.prix_vente AS prix_vente, entree_produit.date_expiration AS date_expiration FROM entree_produit JOIN produit ON entree_produit.id_produit=produit.id ");

    $req1 = $con->prepare("SELECT * FROM produit");
    $req->execute();
    $req1->execute();

?>

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ajouter_categorie"><?=$titre?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="../upload/upload-entree-produit.php",id="Form" method="post">
                        <input type="text" name="idUp" id="idUp" hidden>
                        <div class="form-group">
                            <label for="quantite">Produits</label>

                            <select name="produit" id="produit" class="form-control">
                                <?php
                                    while($row = $req1->fetch()){
                                        ?>
                                         <option value="<?=$row->id?>"><?=$row->description?></option>
                                   <?php }?>
                                
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="quantite">Quantité</label>
                            <input name="quantite" type="number" class="form-control" id="quantite"  required>
                        </div>
                        <div class="form-group">
                            <label for="prix_achat">Prix d'achat</label>
                            <input name="prix_achat" type="number" class="form-control" id="prix_achat"  required>
                        </div>
                        <div class="form-group">
                            <label for="prix_vente">Prix de Vente</label>
                            <input name="prix_vente" type="number" class="form-control" id="prix_vente"  required>
                        </div>

                        <div class="form-group">
                            <label for="date_expiration">Date d'expiration</label>
                            <input name="date_expiration" type="date" class="form-control" id="date_expriration"  required>
                        </div>


                        <input type="submit" name="submit"class=" btn btn-primary" value="Enregistrer">
                        <!-- <button type="submit" class="btn btn-primary">Enregistrer</button> -->
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- modale de suppression -->
<div class="modal fade" id="modalSuppression" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Suppression</h1>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="card-body">
            <p class="card-text">Confirmez-vous la suppression définitive de <b id="label" class="text-dark"> </b> ? Il
                est important de noter que cette action est définitive et ne peut être révoquée.</p>
        </div>
      <div class="modal-footer">
        <form action="../upload/upload-entree-produit.php" method="post">
            <input type="text" id="idDel" name="idDel" hidden>
            <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Non</button>
            <input type="submit" class="btn btn-primary" value="Oui">
        </form>
        
      </div>2
    </div>
  </div>
</div>


    
    <div class="content">
        <h3 class="mt-5"><?=$titre?></h3>
        <button class="btn btn-primary mt-3" data-toggle="modal" data-target="#addModal">Ajouter</button>

        <?php if(isset($_GET['msg']) && isset($_GET['status'])){?>
        <div class="alert alert-<?=$_GET['status']?> alert-dismissible fade show mt-1" role="alert">
            <?=$_GET['msg']?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">x</button>
        </div>
        <?php }?>

        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Date</th>
                    <th>produit</th>
                    <th>Quantité</th>
                    <th>Prix d'achat</th>
                    <th>Prix de vente</th>
                    <th>Date d'expiration</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    while ($data=$req->fetch()){
                        ?>
                            <tr>
                                <td><?=$data->id ?></td>
                                <td><?=$data->date ?></td>
                                <td><?=$data->description ?></td>
                                <td><?=$data->quantite ?></td>
                                <td><?=$data->prix_achat ?></td>
                                <td><?=$data->prix_vente ?></td>
                                <td><?=$data->date_expiration ?></td>
                                <td>
                                    <button data-toggle="modal" data-target="#addModal" class="btn btn-warning btn-sm editbtn" id="editbtn">Mod</button>
                                    <a class="btn btn-danger btn-sm deletebtn" data-toggle="modal" data-target="#modalSuppression">sup</a>
                                </td>
                                
                            </tr>
                        <?php
                    }
                

                ?>
                
            </tbody>
        </table>
       
    </div>
    
    <?php
        $contenu = ob_get_clean();
        require('temblate.php')
    ?>

<script>
   var form = $('#Form');
    $(document).ready(function() {
        $(".editbtn").on("click", function() {
            $tr = $(this).closest('tr');var data = $tr.children('td').map(function(){return $(this).text();}).get();

            $('#idUp').val(data[0]);
            $('#id').val(data[0]);
            $('#id_produit').val(data[1]);
            $('#date').val(data[2]);
            $('#quantite').val(data[3]);
            $('#prix_achat').val(data[4]);
            $('#prix_vente').val(data[5]);
            $('#date_expiration').val(data[6]);

           
        });
        $(".deletebtn").on("click", function() {
            var label = document.getElementById('label');
            $tr = $(this).closest('tr');var data = $tr.children('td').map(function() {return $(this).text();}).get();

            $("#idDel").val(data[0]);

            var valeur = data[1];
            setModal(label, valeur);
        });
    });
    function setModal(label, valeur){var modal = $('#modalSuppression');var btn = $('.deletebtn');var span = $('.close');label.innerHTML = valeur;showElement(modal);$('.close').on('click', ()=> {hideElement(modal);});$('.non').on('click', ()=> {hideElement(modal);});window.addEventListener("click", function(event) {if(event.target === modal){hideElement(modal);}});}
</script>