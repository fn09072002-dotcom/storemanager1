<?php
$message = $this->session->get('flash_message') ?? null;
$this->session->unset('flash_message');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>StoreManager Pro — Caisse</title>
    <style>
        :root {
            --bg-color: #0b0f19;
            --panel-bg: rgba(22, 30, 49, 0.65);
            --border-color: rgba(45, 212, 191, 0.12);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --accent: #2dd4bf;
            --accent-glow: rgba(45, 212, 191, 0.1);
            --success: #34d399;
            --danger: #f87171;
        }
        * { box-sizing: border-box; }
        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 24px;
        }
        .navbar {
            display: flex; justify-content: space-between; align-items: center;
            background: rgba(8, 12, 24, 0.7);
            border: 1px solid var(--border-color);
            padding: 16px 24px; border-radius: 20px; margin-bottom: 24px;
        }
        .nav-logo { font-size: 20px; font-weight: 800; }
        .nav-logo span { color: var(--accent); }
        .layout { display: grid; grid-template-columns: 600px 1fr; gap: 32px; align-items: start; }
        .panel-card {
            background: var(--panel-bg);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 28px;
        }
        .panel-title {
            font-size: 16px; font-weight: 700; margin-bottom: 20px;
            border-left: 4px solid var(--accent); padding-left: 12px;
        }
        .form-group { margin-bottom: 14px; }
        label { display: block; font-size: 11px; text-transform: uppercase; margin-bottom: 6px; color: var(--text-muted); font-weight: 700; }
        .form-control {
            width: 100%; padding: 12px 14px; border-radius: 12px;
            border: 1px solid var(--border-color); background: rgba(8,12,24,0.7); color: white;
        }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; font-size: 13px; }
        th { color: var(--text-muted); font-size: 11px; text-transform: uppercase; text-align: left; padding-bottom: 8px; border-bottom: 1px solid var(--border-color); }
        td { padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.03); }
        .btn {
            background: linear-gradient(135deg, var(--accent) 0%, #0d9488 100%);
            color: #0b0f19; border: none; padding: 14px; border-radius: 12px;
            font-weight: 800; width: 100%; cursor: pointer; text-transform: uppercase; font-size: 13px;
        }
        .btn-add { width: 42px; height: 42px; padding: 0; font-size: 18px; flex-shrink: 0; }
        .flash { padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; background: rgba(45,212,191,0.08); border-left: 4px solid var(--accent); font-size: 13px; }
        .total-display { background: rgba(45,212,191,0.06); border: 1px solid var(--border-color); border-radius: 16px; padding: 16px; text-align: center; margin: 20px 0; }
        .total-display .label { font-size: 10px; color: var(--text-muted); text-transform: uppercase; }
        .total-display .value { font-size: 24px; font-weight: 900; color: var(--accent); }
        .badge { padding: 4px 10px; border-radius: 8px; font-size: 10px; font-weight: 700; text-transform: uppercase; }
        .badge.credit { background: rgba(244,63,94,0.1); color: var(--danger); }
        .badge.paye { background: rgba(16,185,129,0.1); color: var(--success); }
    </style>
</head>
<body>

    <div class="navbar">
        <div class="nav-logo">📦 StoreManager <span>Pro</span> — Ventes / POS</div>
    </div>

    <?php if ($message): ?>
        <div class="flash"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="layout">
        <!-- Formulaire de vente -->
        <div class="panel-card">
            <div class="panel-title">🛒 Nouvelle Vente</div>

            <form method="POST" action="/pos/encaisser" id="order-creation-form">

                <div class="form-group">
                    <label for="client_id">Client Acheteur</label>
                    <select name="client_id" id="client-select" class="form-control" onchange="updateClientLimitInfo()">
                        <?php foreach ($clients as $client): ?>
                            <option value="<?= $client->getId() ?>" data-limit="<?= $client->getLimiteCredit() ?>">
                                <?= htmlspecialchars($client->getNom()) ?> (<?= htmlspecialchars($client->getTelephone()) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span id="credit-limit-info" style="font-size:11px; color:var(--text-muted); display:block; margin-top:6px;"></span>
                </div>

                <div class="form-group">
                    <label>Sélection des Articles</label>
                    <div style="display:flex; gap:8px; align-items:flex-end;">
                        <select id="pos-item-select" class="form-control">
                            <?php foreach ($produits as $produit): ?>
                                <option
                                    value="<?= $produit->getId() ?>"
                                    data-price="<?= $produit->getPrixVente() ?>"
                                    data-name="<?= htmlspecialchars($produit->getNom()) ?>"
                                    data-stock="<?= $produit->getQuantiteStock() ?>"
                                >
                                    <?= $produit->estEnRupture() ? '🔴' : '🟢' ?>
                                    <?= htmlspecialchars($produit->getNom()) ?> (<?= $produit->getQuantiteStock() ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="number" id="pos-qty" class="form-control" value="1" min="1" style="max-width:70px;">
                        <button type="button" class="btn btn-add" onclick="addToCart(event)">+</button>
                    </div>
                </div>

                <table>
                    <thead><tr><th>Produit</th><th>Qté</th><th>Total</th><th></th></tr></thead>
                    <tbody id="cart-rows">
                        <tr id="empty-cart-row"><td colspan="4" style="text-align:center; color:var(--text-muted);">Panier vide.</td></tr>
                    </tbody>
                </table>
                <div id="hidden-cart-inputs"></div>

                <div class="total-display">
                    <div class="label">Montant Total Net à Payer</div>
                    <div class="value"><span id="montant_total_display_text">0</span> FCFA</div>
                </div>

                <div class="form-group">
                    <label for="pos-montant-verse">Versé (Avance)</label>
                    <input type="number" name="montant_verse" id="pos-montant-verse" class="form-control" value="0" min="0">
                </div>

                <button type="submit" class="btn">Valider la Vente</button>
            </form>
        </div>

        <!-- Registre des ventes -->
        <div class="panel-card">
            <div class="panel-title">Registre Général des Ventes</div>
            <table>
                <thead>
                    <tr><th>ID</th><th>Client</th><th>Total</th><th>Statut</th></tr>
                </thead>
                <tbody>
                    <?php if (empty($commandes)): ?>
                        <tr><td colspan="4" style="text-align:center; color:var(--text-muted); padding:16px 0;">Aucune vente enregistrée.</td></tr>
                    <?php else: ?>
                        <?php foreach (array_reverse($commandes) as $commande): ?>
                            <?php $clientNom = isset($clientsParId[$commande->getClientId()]) ? $clientsParId[$commande->getClientId()]->getNom() : 'Client #' . $commande->getClientId(); ?>
                            <tr>
                                <td style="color:var(--text-muted); font-weight:700;">#CMD-<?= $commande->getId() ?></td>
                                <td style="font-weight:700;"><?= htmlspecialchars($clientNom) ?></td>
                                <td style="font-weight:800; color:var(--accent);"><?= number_format($commande->getMontantTotal(), 0, ',', ' ') ?> F</td>
                                <td>
                                    <span class="badge <?= $commande->estValidee() ? 'paye' : 'credit' ?>">
                                        <?= htmlspecialchars($commande->getStatut()) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const cart = [];

        function addToCart(event) {
            event.preventDefault();
            const select = document.getElementById("pos-item-select");
            const price = parseFloat(select.options[select.selectedIndex].dataset.price);
            const name = select.options[select.selectedIndex].dataset.name;
            const stock = parseInt(select.options[select.selectedIndex].dataset.stock);
            const id = select.value;
            const qty = parseInt(document.getElementById("pos-qty").value);

            if (qty <= 0) return;
            if (qty > stock) { alert(`Stock insuffisant pour ${name} (${stock} disponible)`); return; }

            const existing = cart.find(item => item.id === id);
            if (existing) {
                if (existing.qty + qty > stock) { alert(`Stock insuffisant (${stock} disponible)`); return; }
                existing.qty += qty;
                existing.total = existing.qty * price;
            } else {
                cart.push({ id, name, price, qty, total: qty * price });
            }
            renderCart();
        }

        function removeCartItem(index) {
            cart.splice(index, 1);
            renderCart();
        }

        function renderCart() {
            const body = document.getElementById("cart-rows");
            const textDisplay = document.getElementById("montant_total_display_text");
            const hiddenInputs = document.getElementById("hidden-cart-inputs");

            if (cart.length === 0) {
                body.innerHTML = `<tr id="empty-cart-row"><td colspan="4" style="text-align:center; color:var(--text-muted);">Panier vide.</td></tr>`;
                textDisplay.innerText = "0";
                hiddenInputs.innerHTML = "";
                document.getElementById("pos-montant-verse").value = 0;
                return;
            }

            body.innerHTML = "";
            hiddenInputs.innerHTML = "";
            let total = 0;

            cart.forEach((item, index) => {
                total += item.total;
                body.innerHTML += `
                    <tr>
                        <td style="font-weight:700;">${item.name}</td>
                        <td>${item.qty}</td>
                        <td style="font-weight:800; color:var(--accent);">${new Intl.NumberFormat('fr-FR').format(item.total)} F</td>
                        <td style="text-align:right;"><button type="button" onclick="removeCartItem(${index})" style="background:none;border:none;color:var(--danger);cursor:pointer;">🗑️</button></td>
                    </tr>`;
                hiddenInputs.innerHTML += `
                    <input type="hidden" name="product_ids[]" value="${item.id}">
                    <input type="hidden" name="product_qtys[]" value="${item.qty}">`;
            });

            textDisplay.innerText = new Intl.NumberFormat('fr-FR').format(total);
            document.getElementById("pos-montant-verse").value = total;
        }

        function updateClientLimitInfo() {
            const select = document.getElementById("client-select");
            const opt = select.options[select.selectedIndex];
            if (!opt) return;
            const limit = parseFloat(opt.dataset.limit);
            document.getElementById("credit-limit-info").innerText = `Limite de crédit : ${new Intl.NumberFormat('fr-FR').format(limit)} FCFA`;
        }

        document.addEventListener("DOMContentLoaded", updateClientLimitInfo);
    </script>
</body>
</html>