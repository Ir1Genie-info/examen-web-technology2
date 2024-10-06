<?php
    ob_start(); 
    include('../upload/connexion.php');
    $facture = $_GET['facture'];
   # include('modal.php');
    $titre = "Ventes";
    $database = new Connexion();
    $con = $database->get_connexion();
    $req = $con->prepare("SELECT * FROM facture ORDER BY id DESC LIMIT 1;");
    $req_add = $con->prepare("SELECT entree_produit.id,produit.description AS description_produit,entree_produit.quantite FROM entree_produit join produit on produit.id=entree_produit.id_produit");

    $req_afficher_facture = $con->prepare("select vente_produit.id,vente_produit.facture,vente_produit.id_entree_produit,vente_produit.pvt,
    vente_produit.pvu,vente_produit.quantite,produit.description,(vente_produit.pvu * 
    vente_produit.quantite) as total from vente_produit join 
    entree_produit ON entree_produit.id = vente_produit.id_entree_produit JOIN produit on 
    produit.id = entree_produit.id_produit where vente_produit.facture=?");

    $req_vente = $con->prepare("SELECT SUM(vente_produit.pvu * vente_produit.quantite) as somme  FROM pharmacie.vente_produit where vente_produit.facture = ?");
    $req->execute();
    $req_add->execute();
    $req_afficher_facture->execute([$facture]);
    $req_vente->execute([$facture]);
?>

<div class="content">
<h2>Formulaire de Vente</h2>

<a class="btn btn-primary" href="../upload/ventes/upload-nouveau-facture.php" onclick="viderChamp()">Nouvelle Facture</a>


        <form id="apartmentForm" method="post" action="../upload/ventes/upload-ajouter-panier.php">
            <div class="row mt-3">
                <div class="mb-3 col-6">
                    <label for="facture">Facture</label>
                    <input  type="text" name="facture" id="facture" value="<?php 
                    if($_GET['action']=='ajout_facture')
                    {while($data = $req->fetch()){
                        echo $data->id;
                        }}?>" 
                        class="form-control" id="facture" required>
                </div>
                <div class="mb-3 col-6">
                    <label for="product" class="form-label">Produit</label>
                    <select name="entree" id="entree" class="form-control">
                    <?php while($data = $req_add->fetch()){?>
                    <option value="<?=$data->id?>">Designation:<?=$data->description_produit?>, Quantité:<?=$data->quantite?></option>

                    <?php }?>
                    </select>
                </div>
            </div>
           
            <div class="row">
            <div class="mb-3 col-6">
                        <label for="quantite">Quantité</label>
                        <input type="number" class="form-control" id="quantite" name="quantite" required>
                    </div>
                <div class="mb-3 col-6">
                    <label for="price" class="form-label">Prix</label>
                    <input type="number" class="form-control" id="pu" name="pu" required>
                </div>
            </div>
            
            <input type="submit" class="btn btn-secondary mb-3" value="Ajouter au panier">
        </form>

        <h3>Produits ajoutés</h3>
        <table class="table table-bordered" id="productsTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Produit</th>
                    <th>Quatité</th>
                    <th>Prix</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
            $num = 0; 
                    while($data = $req_afficher_facture->fetch()){
                        $num++
                        ?>
                <tr>
                    <td><?=$num?></td>
                    <td><?=$data->description?></td>
                    <td><?=$data->quantite?></td>
                    <td><?=$data->pvu?></td>
                    <td><?=$data->total?></td>
                </tr>

                <?php }?>

            </tbody>
        </table>
        <h4>Total: <span id="total"><?php
            while($data = $req_vente->fetch()){
                ?>
                <?=$data->somme?>
                <?php }?>
        </span> €</h4>

        <button type="button" class="btn btn-primary" onclick="generateInvoice()">Générer la Facture</button>

</div>
<div class="container mt-5">
            </div>

    <?php
        $contenu = ob_get_clean();
        require('temblate.php')
    ?>

<script>

document.getElementById('facture').addEventListener('keydown', function(event) {
    event.preventDefault();
  });

function toggleForm() {
            var form = document.getElementById('apartmentForm');
           // if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
           // } /* else {
             //   form.style.display = 'none';
           // } */
        }
        function blockForm(){
            var form = document.getElementById('apartmentForm');
            form.style.display = 'none';
        }

        function cancelForm() {
            document.getElementById('apartmentForm').reset();
            blockForm();
            editIndex = -1;
        }
        let total = 0;

        function addProduct() {
            const product = document.getElementById('product').value;
            const price = parseFloat(document.getElementById('price').value);

            if (product && price) {
                const table = document.getElementById('productsTable').getElementsByTagName('tbody')[0];
                const newRow = table.insertRow();

                const productCell = newRow.insertCell(0);
                const priceCell = newRow.insertCell(1);

                productCell.textContent = product;
                priceCell.textContent = price.toFixed(2);

                total += price;
                document.getElementById('total').textContent = total.toFixed(2);

                document.getElementById('salesForm').reset();
            } else {
                alert('Veuillez remplir tous les champs.');
            }
        }

        function generateInvoice() {
            const table = document.getElementById('productsTable').getElementsByTagName('tbody')[0];
            const rows = table.getElementsByTagName('tr');
            let invoiceContent = `
                <html>
                <head>
                    <title>Facture</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                </head>
                <body>
                    <div class="container mt-5">
                        <h2>Facture</h2>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Produit</th>
                                    <th>Quatité</th>
                                    <th>Prix</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>`;

            for (let i = 0; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName('td');
                const num = cells[0].textContent;
                const produit = cells[1].textContent;
                const quantite = cells[2].textContent;
                const prix = cells[3].textContent;
                const total = cells[4].textContent;

                invoiceContent += `<tr><td>${num}</td><td>${produit}</td><td>${quantite}</td><td>${prix} $</td><td>${total} $</td></tr>`;
            } 
            const tolals = document.getElementById('total').innerHTML;

            invoiceContent += `
                            </tbody>
                        </table>
                        <h4>Total:${tolals}€</h4>
                        <button onclick="window.print()" class="btn btn-secondary">Imprimer</button>
                    </div>
                </body>
                </html>`;

            const invoiceWindow = window.open('', '_blank');
            invoiceWindow.document.write(invoiceContent);
            invoiceWindow.document.close();
            
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


