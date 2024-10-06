<?php
    ob_start(); 
    include('../upload/connexion.php');
   # include('modal.php');
    $titre = "Gestion de sorties des  Produits";
   
    $database = new Connexion();
    $con = $database->get_connexion();
    $req = $con->prepare("SELECT entree_produit.id,produit.description FROM entree_produit JOIN produit ON produit.id=entree_produit.id_produit");
    
    $req->execute();
    
     if(isset($_GET['designation'])){
        if (!empty($_GET['designation'])){
            $designation = $_GET['designation'];
            $rq = $con->prepare("SELECT sortie_produit.id,sortie_produit.description,sortie_produit.date,sortie_produit.id_entree_produit,sortie_produit.quantite,produit.description AS produit FROM sortie_produit JOIN entree_produit ON entree_produit.id=sortie_produit.id_entree_produit JOIN produit ON produit.id=entree_produit.id_produit WHERE produit.description = ?");
            $rq->execute([$designation]);

        }
        else{
            $rq = $con->prepare("SELECT sortie_produit.id,sortie_produit.description,sortie_produit.date,sortie_produit.id_entree_produit,sortie_produit.quantite,produit.description AS produit FROM sortie_produit JOIN entree_produit ON entree_produit.id=sortie_produit.id_entree_produit JOIN produit ON produit.id=entree_produit.id_produit");
            $rq->execute();
        }
     }
     else{
        $rq = $con->prepare("SELECT sortie_produit.id,sortie_produit.description,sortie_produit.date,sortie_produit.id_entree_produit,sortie_produit.quantite,produit.description AS produit FROM sortie_produit JOIN entree_produit ON entree_produit.id=sortie_produit.id_entree_produit JOIN produit ON produit.id=entree_produit.id_produit");
        $rq->execute();

     }
?>

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ajouter_produit"><?=$titre?></h5>
                    <button type="button" class="close"  data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="Form" action="../upload/upload-sortie-produit.php">
                        <input type="text" name="idUp" id="idUp" hidden>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text"  class="form-control" id="description" name="description" required>
                        </div>
                        <div class="form-group">
                            <label for="id_entree_produit">Produit</label>
                            <select name="id_entree_produit" id="id_entree_produit" class="form-control">
                           <?php while($data = $req->fetch()){?>
                                        <option value="<?=$data->id?>"><?=$data->description?></option>
                                        <?php }?>
                            </select>
                            
                        </div>
                        <div class="form-group">
                            <label for="quantite">Quantité</label>
                            <input type="number"  class="form-control" id="quantite" name="quantite" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
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
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Supprssion</h1>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="card-body">
            <p class="card-text">Confirmez-vous la suppression définitive de <b id="label" class="text-dark"> </b> ? Il
                est important de noter que cette action est définitive et ne peut être révoquée.</p>
        </div>
      <div class="modal-footer">
        <form action="../upload/upload-sortie-produit.php" method="post">
            <input type="text" id="idDel" name="idDel" hidden>
            <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Non</button>
            <input type="submit" class="btn btn-primary" value="Oui">
        </form>
        
      </div>
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
        <form method="post" id="Form" action="../upload/upload-rechercher-sortie.php">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="designation">Description</label>
                    <input type="text"  class="form-control" id="description" name="designation">
                </div>
            </div>
            <input type="submit" class="btn btn-secondary mb-3" value="Rechercher">
        </form>

        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>Numero</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while($data = $rq->fetch()){?>
                <tr>
                    <td><?=$data->id?></td>
                    <td ><?=$data->date?></td>
                    <td><?=$data->description?></td>
                    <td hidden><?=$data->id_entree_produit?></td>
                    <td><?=$data->produit?></td>
                    <td><?=$data->quantite?></td>
                    <td>
                        <button data-toggle="modal" data-target="#addModal" class="btn btn-warning btn-sm editbtn" id="editbtn">Mod</button>
                         <a class="btn btn-danger btn-sm deletebtn" data-toggle="modal" data-target="#modalSuppression">sup</a>
                    </td>
                </tr>

                <?php }?>
                <!-- Les données des médicaments seront insérées ici -->
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
            $("#description").val(data[2]);
            $("#id_entree_produit").val(data[3]);
            $("#quantite").val(data[5]);
          
           
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