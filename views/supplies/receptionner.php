<?php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réception BL — StoreManager Pro</title>
    <style>
        :root {
            --bg-color: #0b0f19; --panel-bg: rgba(22, 30, 49, 0.65);
            --border-color: rgba(45, 212, 191, 0.12); --text-main: #f8fafc; --text-muted: #94a3b8;
            --accent: #2dd4bf; --success: #34d399; --danger: #f87171; --warning: #fbbf24;
        }
        * { box-sizing: border-box; }
        body { background-color: var(--bg-color); color: var(--text-main); font-family: 'Segoe UI', sans-serif; margin: 0; padding: 24px; }
        .panel-card { background: var(--panel-bg); border: 1px solid var(--border-color); border-radius: 24px; padding: 28px; }
        .panel-title { font-size: 16px; font-weight: 700; margin-bottom: 20px; border-left: 4px solid var(--accent); padding-left: 12px; display: flex; justify-content: space-between; align-items: center; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; margin-bottom: 16px; }
        th { color: var(--text-muted); font-size: 11px; text-transform: uppercase; text-align: left; padding-bottom: 12px; border-bottom: 1px solid var(--border-color); }
        td { padding: 10px 6px; border-bottom: 1px solid rgba(255,255,255,0.03); }
        .form-control { background: #0b0f19; border: 1px solid var(--border-color); border-radius: 10px; padding: 8px 10px; color: white; font-size: 13px; width: 100%; }
        .btn-quick { background: rgba(255,255,255,0.03); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 8px; padding: 6px 12px; font-size: 11px; font-weight: 700; cursor: pointer; }
        .btn-submit { background: linear-gradient(135deg, var(--accent) 0%, #0d9488 100%); color: #0b0f19; border: none; padding: 11px 24px; border-radius: 10px; font-weight: 800; font-size: 12px; text-transform: uppercase; cursor: pointer; margin-top: 12px; }
        a.back-link { color: var(--accent); text-decoration: none; font-size: 12px; font-weight: 700; }
    </style>
</head>
<body>

    <?php $currentPage = 'supplies'; require_once dirname(__DIR__) . '/partials/navbar.php'; ?>

    <p><a href="/supplies" class="back-link">&larr; Retour aux approvisionnements</a></p>

    <div class="panel-card">
        <div class="panel-title">
            <span>Réceptionner le BL <?= htmlspecialchars($appro->getNumeroBL()) ?></span>
        </div>

        <table id="lignes-table">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Quantité reçue</th>
                    <th>Prix d'achat unitaire (FCFA)</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="lignes-body">
                <!-- lignes ajoutées dynamiquement -->
            </tbody>
        </table>

        <button type="button" id="ajouter-ligne" class="btn-quick">+ Ajouter un produit</button>

        <form method="POST" action="/supplies/receptionner/valider" id="reception-form">
            <input type="hidden" name="approvisionnement_id" value="<?= $appro->getId() ?>">
            <input type="hidden" name="lignes_json" id="lignes_json">
            <br>
            <button type="submit" class="btn-submit">✓ Confirmer la réception (incrémente le stock)</button>
        </form>
    </div>

    <script>
        const produits = <?= json_encode(array_map(
            fn($p) => ['id' => $p->getId(), 'nom' => $p->getNom()],
            $produits
        )) ?>;

        const body = document.getElementById('lignes-body');

        function ajouterLigne() {
            const tr = document.createElement('tr');
            const optionsHtml = produits.map(p => `<option value="${p.id}">${p.nom}</option>`).join('');

            tr.innerHTML = `
                <td><select class="produit-id form-control">${optionsHtml}</select></td>
                <td><input type="number" class="quantite form-control" min="1" value="1"></td>
                <td><input type="number" class="prix form-control" min="0" step="0.01" value="0"></td>
                <td><button type="button" class="supprimer-ligne btn-quick">Supprimer</button></td>
            `;

            tr.querySelector('.supprimer-ligne').addEventListener('click', () => tr.remove());
            body.appendChild(tr);
        }

        document.getElementById('ajouter-ligne').addEventListener('click', ajouterLigne);
        ajouterLigne();

        document.getElementById('reception-form').addEventListener('submit', function (e) {
            const lignes = [];
            body.querySelectorAll('tr').forEach(tr => {
                lignes.push({
                    produit_id: parseInt(tr.querySelector('.produit-id').value, 10),
                    quantite: parseInt(tr.querySelector('.quantite').value, 10),
                    prix_achat_unitaire: parseFloat(tr.querySelector('.prix').value)
                });
            });

            if (lignes.length === 0) {
                e.preventDefault();
                alert('Ajoute au moins un produit avant de valider.');
                return;
            }

            document.getElementById('lignes_json').value = JSON.stringify(lignes);
        });
    </script>
</body>
</html>