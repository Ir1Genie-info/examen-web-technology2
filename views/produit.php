<?php
    ob_start(); 
    include('../upload/connexion.php');
    $titre = "Produits";

    $database = new Connexion();
    $con = $database->get_connexion();

    if(isset($_GET['designation'])){
        if (!empty($_GET['designation'])) {
            $designation = $_GET['designation'];
             $req = $con->prepare("SELECT produit.id AS id, produit.description AS description, categorie_produit.id AS id_categorie_produit, categorie_produit.description AS categorie FROM produit JOIN categorie_produit ON categorie_produit.id = produit.id_categorie_produit WHERE produit.description = ?");
           $req->execute([$designation]);
        }
        else{

            $req = $con->prepare("SELECT produit.id AS id, produit.description AS description, categorie_produit.id AS id_categorie_produit, categorie_produit.description AS categorie FROM produit JOIN categorie_produit ON categorie_produit.id = produit.id_categorie_produit");
            $req->execute();
        }
    }
    else{

        $req = $con->prepare("SELECT produit.id AS id, produit.description AS description, categorie_produit.id AS id_categorie_produit, categorie_produit.description AS categorie FROM produit JOIN categorie_produit ON categorie_produit.id = produit.id_categorie_produit");
        $req->execute();
    }  

    

    $rq = $con->prepare("SELECT * FROM categorie_produit");
    $rq->execute();

    $r = $con->prepare("SELECT * FROM categorie_produit");
    $r->execute();


?>

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ajouter_produit">Ajouter un produit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="Form" method="post" action="../upload/upload-produit.php">
                        <div class="form-group">
                            <input type="text" name="idUp" id="idUp" hidden>
                            <label for="description">Description</label>
                            <input type="text" class="form-control" id="description" name="description" required>
                        </div>
                        <div class="form-group">
                            <label for="categorie_produit">Categorie_Produit</label>
                            <select class="form-control" name="categorie_produit" id="categorie_produit">
                                <?php while($data = $rq->fetch()){ ?>
                                <option value="<?=$data->id ?>"><?=$data->description ?></option>
                                <?php } ?>
                            </select>
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
        <form action="../upload/upload-produit.php" method="post">
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

        <form id="apartmentForm" method="post" action="../upload/upload-rechercher-produit.php">
            <div class="row mt-3">
                <div class="mb-3 col-6">
                   <label for="quantite">Designation</label>
                    <input type="text" class="form-control" id="designation" name="designation">
                </div>
                        
            </div>            
            <input type="submit" class="btn btn-secondary mb-3" value="Rechercher">
        </form>
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>Numero</th>
                    <th>Description</th>
                    <th>CategorieProduit</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while($data = $req->fetch()){?>
                <tr>
                    <td><?=$data->id?></td>
                    <td><?=$data->description?></td>
                    <td><?=$data->categorie?></td>
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
            $('#description').val(data[1]);
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