<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de Vente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Formulaire de Vente</h2>
        <form id="salesForm">
            <div class="mb-3">
                <label for="product" class="form-label">Produit</label>
                <input type="text" class="form-control" id="product" name="product" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Prix</label>
                <input type="number" class="form-control" id="price" name="price" required>
            </div>
            <button type="button" class="btn btn-secondary mb-3" onclick="addProduct()">Ajouter au tableau</button>
        </form>

        <h3>Produits ajoutés</h3>
        <table class="table table-bordered" id="productsTable">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Prix</th>
                </tr>
            </thead>
            <tbody>
                <!-- Les produits ajoutés apparaîtront ici -->
            </tbody>
        </table>
        <h4>Total: <span id="total">0</span> €</h4>

        <button type="button" class="btn btn-primary" onclick="generateInvoice()">Générer la Facture</button>
    </div>

    <script>
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
                                    <th>Produit</th>
                                    <th>Prix</th>
                                </tr>
                            </thead>
                            <tbody>`;

            for (let i = 0; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName('td');
                const product = cells[0].textContent;
                const price = cells[1].textContent;
                invoiceContent += `<tr><td>${product}</td><td>${price} €</td></tr>`;
            }

            invoiceContent += `
                            </tbody>
                        </table>
                        <h4>Total: ${total.toFixed(2)} €</h4>
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
</body>
</html>
