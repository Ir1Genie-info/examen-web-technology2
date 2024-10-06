<?php
    include('../upload/connexion.php');
    $facture = $_GET['facture'];
    $conn = new Connexion();
    $con = $conn->get_connexion();
    $req = $con->prepare("SELECT * FROM facture ORDER BY id DESC LIMIT 1;");
    $req_add = $con->prepare("SELECT entree_produit.id,produit.description AS description_produit,entree_produit.quantite FROM entree_produit join produit on produit.id=entree_produit.id_produit");

    $req_afficher_facture = $con->prepare("select vente_produit.id,vente_produit.facture,vente_produit.id_entree_produit,vente_produit.pvt,
vente_produit.pvu,vente_produit.quantite,produit.description,(vente_produit.pvu * 
vente_produit.quantite) as total from vente_produit join 
entree_produit ON entree_produit.id = vente_produit.id_entree_produit JOIN produit on 
produit.id = entree_produit.id_produit where vente_produit.facture=?");
    $req->execute();
    $req_add->execute();
    $req_afficher_facture->execute([$facture]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .sales-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        @media print {
            #sales-form, .btn {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header sales-header">
                <span>Sales Record</span>
                <button class="btn btn-primary" onclick="printSales()">Print</button>
                <a class="btn btn-primary" href="../upload/ventes/upload-nouveau-facture.php" onclick="viderChamp()">Nouvelle Facture</a>
            </div>
            <div class="card-body">
                <form id="" action="../upload/ventes/upload-ajouter-panier.php" method="post">
                    <div class="form-group">
                        <label for="facture">Facture</label>
                        <input type="text" name="facture" id="facture" value="<?php 
                        if($_GET['action']=='ajout_facture')
                        {while($data = $req->fetch()){
                            echo $data->id;
                        }}?>" 
                        class="form-control" id="facture" required>
                    </div>
                    <div class="form-group">
                        <label for="entree">Produit</label>
                        <select name="entree" id="entree" class="form-control">
                                <?php while($data = $req_add->fetch()){?>
                            <option value="<?=$data->id?>">Designation:<?=$data->description_produit?>, Quantité:<?=$data->quantite?></option>

                <?php }?>
                </select>
                       
                    </div>
                    <div class="form-group">
                        <label for="quantite">Quantité</label>
                        <input type="number" class="form-control" id="quantite" name="quantite" required>
                    </div>
                    <div class="form-group">
                        <label for="unit-price">Prix Unitaire</label>
                        <input type="number" class="form-control" id="pu" name="pu" required>
                    </div>
                    <input type="submit" class="btn btn-success" value="Ajouter Au Panier">
                </form>
                <table class="table mt-4">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="salestable">
                    <?php
                    $num = 0; 
                    while($data = $req_afficher_facture->fetch()){
                        $num++
                        ?>
                <tr>
                    <td><?=$num?></td>
                    <td><?=$data->description?></td>
                    <td><?=$data->quantite?></td>
                    <td><?=$data->pu?></td>
                    <td><?=$data->total?></td>
                </tr>

                <?php }?>
                    </tbody>
                </table>
                <p class="text-right"><strong>Total Sales: $<span id="total-sales">0</span></strong></p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('sales-form').addEventListener('submit', function(event) {
            event.preventDefault();
            addSale();
        });

        let saleCount = 0;
        let totalSales = 0;

        function addSale() {
            const description = document.getElementById('description').value;
            const quantity = parseInt(document.getElementById('quantity').value);
            const unitPrice = parseFloat(document.getElementById('unit-price').value);
            const total = quantity * unitPrice;

            saleCount++;
            totalSales += total;

            const table = document.getElementById('sales-table');
            const row = table.insertRow();
            row.innerHTML = `
                <td>${saleCount}</td>
                <td>${description}</td>
                <td>${quantity}</td>
                <td>$${unitPrice.toFixed(2)}</td>
                <td>$${total.toFixed(2)}</td>
            `;

            document.getElementById('total-sales').innerText = totalSales.toFixed(2);

            document.getElementById('sales-form').reset();
        }

        function printSales() {
            window.print();
        }

        function viderChamp() {
            document.getElementById('facture').value = '';
        }


        document.getElementById('facture').addEventListener('keydown', function(event) {
    event.preventDefault();
  });
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
