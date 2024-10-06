<?php
    ob_start(); 
    include('../upload/connexion.php');
    $date1 = $_GET['date1'];
    $date2 = $_GET['date2'];
   # include('modal.php');
    $titre = "Ventes";
    $database = new Connexion();
    $con = $database->get_connexion();
    $req = $con->prepare("SELECT * FROM facture ORDER BY id DESC LIMIT 1;");
    $req_add = $con->prepare("SELECT entree_produit.id,produit.description AS description_produit,entree_produit.quantite FROM entree_produit join produit on produit.id=entree_produit.id_produit");

    $req_afficher_vente_date = $con->prepare("SELECT vente_produit.id,vente_produit.facture,vente_produit.id_entree_produit,vente_produit.pvt,
    vente_produit.pvu,vente_produit.quantite,produit.description,(vente_produit.pvu * 
    vente_produit.quantite) as total,facture.date from vente_produit join 
    entree_produit ON entree_produit.id = vente_produit.id_entree_produit JOIN produit on 
    produit.id = entree_produit.id_produit join facture on facture.id=vente_produit.facture where 
    facture.date>=? and facture.date<=?");

    $req_vente = $con->prepare("SELECT SUM(vente_produit.pvu * vente_produit.quantite) as somme  FROM pharmacie.vente_produit where vente_produit.facture = ?");
    $req->execute();
    $req_add->execute();
    $req_afficher_vente_date->execute([$date1,$date2]);
    $req_vente->execute([$facture]);
?>

<div class="content">
<h2>Rapport de Vente</h2>
        <form id="apartmentForm" method="post" action="../upload/ventes/upload-rapport-vente.php">
            <div class="row mt-3">
                <div class="mb-3 col-6">
                   <label for="quantite">Date 1</label>
                    <input type="date" class="form-control" id="date1" name="date1" required>
                    </div>
                <div class="mb-3 col-6">
                    <label for="price" class="form-label">Date2</label>
                    <input type="date" class="form-control" id="date2" name="date2" required>
                </div>    
            </div>            
            <input type="submit" class="btn btn-secondary mb-3" value="Rechercher">
        </form>

        <h3>Produits ajoutés</h3>
        <table class="table table-bordered" id="productsTable">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Facture</th>
                    <th>Produit</th>
                    <th>Quatité</th>
                    <th>Prix</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
            $num = 0; 
                    while($data = $req_afficher_vente_date->fetch()){
                        $num++
                        ?>
                <tr>
                    <td><?=$data->date?></td>
                    <td><?=$data->facture?></td>
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


