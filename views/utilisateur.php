<?php
    ob_start(); 
    include('../upload/connexion.php');
    $database = new Connexion();
    $con = $database->get_connexion();
    $req = $con->prepare("SELECT * FROM utilisateur");
    $req->execute();


    $titre = "Gestion des Utilisateurs";
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
                    <form action="../upload/upload-utilisateur.php" id="Form" method="post" enctype="multipart/form-data">
                        <input type="text" name="idUp" id="idUp" hidden>
                        <div class="form-group">
                            <label for="nom">Nom</label>
                            <input type="text" class="form-control" name="nom" id="nom"  required>
                        </div>
                        <div class="form-group">
                            <label for="postnom">Postnom</label>
                            <input type="text" class="form-control" name="postnom" id="nom_utilisateur"  required>
                        </div>
                        <div class="form-group">
                            <label for="nom_utilisateurs">Nom utilisateurs</label>
                            <input type="text" class="form-control" name="nom_utilisateur" id="nom_utilisateur"  required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="text" class="form-control" name="password" id="password" required>
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select name="role" id="role" class="form-control">
                                <option value="Comptable">Comptable</option>
                                <option value="Gerant">Gérant</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="photoProfile">Photo de Profil</label>
                            <input type="file" class="form-control" name="profilePhoto" id="profilePhoto" required>
                        </div>
                        <input type="submit" name="submit" class="btn btn-primary" value="Enregistrer" >
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
        <form action="../upload/upload-utilisateur.php" method="post">
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

        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>Numero</th>
                    <th hidden>ID</th>
                    <th>Noms</th>
                    <th>Postnoms</th>
                    <th>Nom Utilisateurs</th>
                    <th>Roles</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $num = 0;
                while($data = $req->fetch()){
                    $num ++;
                    ?>
                        <tr>
                            <td><?=$num ?></td>
                            <td hidden><?=$data->id ?></td>
                            <td><?=$data->firstname ?></td>
                            <td><?=$data->lastname ?></td>
                            <td><?=$data->nom_utilisateurs ?></td>
                            <td><?=$data->role ?></td>
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

            $('#idUp').val(data[1]);
            $('#nom').val(data[2]);
            $('#postnom').val(data[3]);
            $('#nom_utilisateur').val(data[4]);
            $('#role').val(data[5]);        
           
        });
        $(".deletebtn").on("click", function() {
            var label = document.getElementById('label');
            $tr = $(this).closest('tr');var data = $tr.children('td').map(function() {return $(this).text();}).get();

            $("#idDel").val(data[1]);

            var valeur = data[2];
            setModal(label, valeur);
        });
    });
    function setModal(label, valeur){var modal = $('#modalSuppression');var btn = $('.deletebtn');var span = $('.close');label.innerHTML = valeur;showElement(modal);$('.close').on('click', ()=> {hideElement(modal);});$('.non').on('click', ()=> {hideElement(modal);});window.addEventListener("click", function(event) {if(event.target === modal){hideElement(modal);}});}
</script>