<?php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>StoreManager Pro — Dettes</title>
    <style>
        :root {
            --bg-color: #0b0f19; --panel-bg: rgba(22, 30, 49, 0.65);
            --border-color: rgba(45, 212, 191, 0.12); --text-main: #f8fafc; --text-muted: #94a3b8;
            --accent: #2dd4bf; --success: #34d399; --danger: #f87171; --warning: #fbbf24;
        }
        * { box-sizing: border-box; }
        body { background-color: var(--bg-color); color: var(--text-main); font-family: 'Segoe UI', sans-serif; margin: 0; padding: 24px; }
        .kpi-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px; }
        .kpi-card { background: var(--panel-bg); border: 1px solid var(--border-color); border-radius: 16px; padding: 16px; display: flex; justify-content: space-between; align-items: center; }
        .kpi-label { font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700; }
        .kpi-val { font-size: 18px; font-weight: 800; margin-top: 4px; }
        .panel-card { background: var(--panel-bg); border: 1px solid var(--border-color); border-radius: 24px; padding: 28px; }
        .panel-title { font-size: 16px; font-weight: 700; margin-bottom: 20px; border-left: 4px solid var(--accent); padding-left: 12px; display: flex; justify-content: space-between; align-items: center; }
        .search-control { background: rgba(255,255,255,0.03); border: 1px solid var(--border-color); border-radius: 8px; padding: 6px 12px; color: white; font-size: 12px; width: 220px; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th { color: var(--text-muted); font-size: 11px; text-transform: uppercase; text-align: left; padding-bottom: 12px; border-bottom: 1px solid var(--border-color); }
        td { padding: 14px 0; border-bottom: 1px solid rgba(255,255,255,0.03); }
        .btn-quick { background: rgba(255,255,255,0.03); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 8px; padding: 6px 12px; font-size: 11px; font-weight: 700; cursor: pointer; margin-right: 4px; }
        .btn-quick.paiements { border-color: var(--accent); color: var(--accent); }
        .btn-quick.rembourser { border-color: var(--warning); color: var(--warning); }
        .flash { padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; background: rgba(45,212,191,0.08); border-left: 4px solid var(--accent); font-size: 13px; }
        .badge { padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
        .badge.non-soldee { background: rgba(244,63,94,0.1); color: var(--danger); }
        .badge.soldee { background: rgba(16,185,129,0.1); color: var(--success); }
        .details-drawer { display: none; background: rgba(255,255,255,0.012); border: 1px solid rgba(255,255,255,0.03); border-radius: 16px; padding: 20px; margin-top: 10px; }
        .details-drawer.open { display: block; }
        .btn-submit { background: linear-gradient(135deg, var(--accent) 0%, #0d9488 100%); color: #0b0f19; border: none; padding: 11px 24px; border-radius: 10px; font-weight: 800; font-size: 12px; text-transform: uppercase; cursor: pointer; }
        .form-control { background: #0b0f19; border: 1px solid var(--border-color); border-radius: 10px; padding: 10px 12px; color: white; font-size: 13px; }
    </style>
</head>
<body>

    <?php $currentPage = 'dettes'; require_once dirname(__DIR__) . '/partials/navbar.php'; ?>

    <?php if ($message): ?>
        <div class="flash"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="kpi-grid">
        <div class="kpi-card" style="border-left: 4px solid var(--danger);">
            <div>
                <div class="kpi-label">Créances Actives</div>
                <div class="kpi-val" style="color: var(--danger);"><?= number_format($creancesActives, 0, ',', ' ') ?> F</div>
            </div>
            <span style="font-size:24px;">💸</span>
        </div>
        <div class="kpi-card" style="border-left: 4px solid var(--warning);">
            <div>
                <div class="kpi-label">Clients Débiteurs</div>
                <div class="kpi-val"><?= $clientsDebiteursCount ?> client<?= $clientsDebiteursCount > 1 ? 's' : '' ?></div>
            </div>
            <span style="font-size:24px;">👥</span>
        </div>
        <div class="kpi-card" style="border-left: 4px solid var(--success);">
            <div>
                <div class="kpi-label">Total Recouvrements</div>
                <div class="kpi-val" style="color: var(--success);"><?= number_format($totalRecouvrements, 0, ',', ' ') ?> F</div>
            </div>
            <span style="font-size:24px;">📈</span>
        </div>
    </div>

    <div class="panel-card">
        <div class="panel-title">
            <span>Registre des Dettes</span>
            <input type="text" id="debt-search" class="search-control" placeholder="Rechercher un client..." onkeyup="filterDebtsTable()">
        </div>
        <table id="debts-main-table">
            <thead>
                <tr>
                    <th>ID Dette</th><th>Client</th><th>Montant Initial</th>
                    <th>Montant Payé</th><th>Reste Dû</th><th>Statut</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($dettesAffichage)): ?>
                    <tr><td colspan="7" style="text-align:center; color:var(--text-muted); padding:16px 0;">Aucune dette enregistrée.</td></tr>
                <?php else: ?>
                    <?php foreach ($dettesAffichage as $item): ?>
                        <?php
                            $dette = $item['dette'];
                            $client = $item['client'];
                            $nomClient = $client ? $client->getNom() : 'Client inconnu';
                            $telClient = $client ? $client->getTelephone() : '';
                            $searchKey = strtolower($nomClient . ' ' . $telClient);
                        ?>
                        <tr data-client-name="<?= htmlspecialchars($searchKey) ?>">
                            <td style="color:var(--text-muted); font-weight:700;">
                                #DT-<?= $dette->getId() ?>
                                <?php if ($item['commande']): ?>
                                    <div style="font-size:10px; color:var(--text-muted); font-weight:normal;">#CMD-<?= $item['commande']->getId() ?></div>
                                <?php endif; ?>
                            </td>
                            <td style="font-weight:700;">
                                <?= htmlspecialchars($nomClient) ?>
                                <div style="font-size:11px; color:var(--text-muted); font-weight:normal;">Tél : <?= htmlspecialchars($telClient) ?></div>
                            </td>
                            <td><?= number_format($dette->getMontantInitial(), 0, ',', ' ') ?> F</td>
                            <td style="color:var(--success); font-weight:700;"><?= number_format($item['montantPaye'], 0, ',', ' ') ?> F</td>
                            <td style="color:var(--danger); font-weight:800;"><?= number_format($dette->getMontantRestant(), 0, ',', ' ') ?> F</td>
                            <td>
                                <span class="badge <?= $dette->estSoldee() ? 'soldee' : 'non-soldee' ?>">
                                    <?= $dette->estSoldee() ? 'Soldée' : 'Non Soldée' ?>
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn-quick" onclick="toggleDrawer('lignes-<?= $dette->getId() ?>')">Articles</button>
                                <button type="button" class="btn-quick paiements" onclick="toggleDrawer('paiements-<?= $dette->getId() ?>')">💳 Paiements</button>
                                <?php if (!$dette->estSoldee()): ?>
                                    <button type="button" class="btn-quick rembourser" onclick="toggleDrawer('rembourser-<?= $dette->getId() ?>')">Rembourser</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="7" style="padding:0; border:none;">

                                <!-- Tiroir : Articles de la vente -->
                                <div class="details-drawer" id="lignes-<?= $dette->getId() ?>">
                                    <div style="font-weight:700; font-size:12px; color:var(--accent); margin-bottom:8px;">Articles de la Vente à Crédit :</div>
                                    <table style="font-size:11px;">
                                        <thead><tr><th>Produit</th><th>Qté</th><th>P.U.</th><th>Sous-total</th></tr></thead>
                                        <tbody>
                                            <?php if (empty($item['lignes'])): ?>
                                                <tr><td colspan="4" style="text-align:center; color:var(--text-muted);">Aucun détail disponible.</td></tr>
                                            <?php else: ?>
                                                <?php foreach ($item['lignes'] as $l): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($l['produit_nom']) ?></td>
                                                        <td><?= $l['quantite'] ?></td>
                                                        <td><?= number_format($l['prix_unitaire'], 0, ',', ' ') ?> F</td>
                                                        <td style="color:var(--accent); font-weight:700;"><?= number_format($l['quantite'] * $l['prix_unitaire'], 0, ',', ' ') ?> F</td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Tiroir : Historique des paiements -->
                                <div class="details-drawer" id="paiements-<?= $dette->getId() ?>">
                                    <div style="font-weight:700; font-size:12px; color:var(--accent); margin-bottom:8px;">Paiements enregistrés :</div>
                                    <table style="font-size:11px;">
                                        <thead><tr><th>Date</th><th>Versement</th><th>Mode</th></tr></thead>
                                        <tbody>
                                            <?php if (empty($item['paiements'])): ?>
                                                <tr><td colspan="3" style="text-align:center; color:var(--text-muted);">Aucun acompte versé.</td></tr>
                                            <?php else: ?>
                                                <?php foreach ($item['paiements'] as $p): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($p->getDatePaiement()) ?></td>
                                                        <td style="color:var(--success); font-weight:700;"><?= number_format($p->getMontant(), 0, ',', ' ') ?> F</td>
                                                        <td><?= htmlspecialchars($p->getModePaiement()) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Tiroir : Formulaire de remboursement -->
                                <?php if (!$dette->estSoldee()): ?>
                                <div class="details-drawer" id="rembourser-<?= $dette->getId() ?>">
                                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px; border-bottom:1px dashed var(--border-color); padding-bottom:10px;">
                                        <span style="font-weight:800; font-size:13px;">💳 Nouveau Remboursement — <span style="color:var(--accent);"><?= htmlspecialchars($nomClient) ?></span></span>
                                        <div style="background: rgba(244,63,94,0.12); border:1px solid rgba(244,63,94,0.3); padding:4px 12px; border-radius:20px; font-size:11px; font-weight:800; color:var(--danger);">
                                            Reste dû : <?= number_format($dette->getMontantRestant(), 0, ',', ' ') ?> FCFA
                                        </div>
                                    </div>

                                    <div style="display:flex; gap:8px; align-items:center; margin-bottom:16px;">
                                        <span style="font-size:10px; text-transform:uppercase; color:var(--text-muted); font-weight:700;">Raccourcis :</span>
                                        <button type="button" onclick="setRepayAmount(<?= $dette->getId() ?>, <?= $dette->getMontantRestant() ?>)" class="btn-quick">Tout solder (<?= number_format($dette->getMontantRestant(), 0, ',', ' ') ?> F)</button>
                                        <button type="button" onclick="setRepayAmount(<?= $dette->getId() ?>, <?= $dette->getMontantRestant() / 2 ?>)" class="btn-quick">50% (<?= number_format($dette->getMontantRestant() / 2, 0, ',', ' ') ?> F)</button>
                                    </div>

                                    <form method="POST" action="/dettes/rembourser" style="display:flex; gap:16px; align-items:flex-end; flex-wrap:wrap;">
                                        <input type="hidden" name="dette_id" value="<?= $dette->getId() ?>">

                                        <div style="flex:1; min-width:200px;">
                                            <label style="font-size:10px; color:var(--text-muted); display:block; margin-bottom:6px; text-transform:uppercase; font-weight:700;">Montant du Versement (FCFA)</label>
                                            <input type="number" name="montant_verse" id="repay-input-<?= $dette->getId() ?>" class="form-control" max="<?= $dette->getMontantRestant() ?>" value="<?= $dette->getMontantRestant() ?>" min="1" required style="width:100%;">
                                        </div>

                                        <div style="flex:1; min-width:200px;">
                                            <label style="font-size:10px; color:var(--text-muted); display:block; margin-bottom:6px; text-transform:uppercase; font-weight:700;">Canal de Paiement</label>
                                            <select name="mode_paiement" class="form-control" required style="width:100%;">
                                                <option value="Orange Money">🟠 Orange Money</option>
                                                <option value="Wave">🌊 Wave</option>
                                                <option value="Especes">💵 Espèces</option>
                                                <option value="Virement">🏦 Virement</option>
                                            </select>
                                        </div>

                                        <button type="submit" class="btn-submit">✓ Enregistrer le Remboursement</button>
                                    </form>
                                </div>
                                <?php endif; ?>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        function toggleDrawer(id) {
            document.getElementById(id).classList.toggle('open');
        }

        function setRepayAmount(detteId, amount) {
            const input = document.getElementById('repay-input-' + detteId);
            if (input) input.value = Math.round(amount);
        }

        function filterDebtsTable() {
            const query = document.getElementById("debt-search").value.toLowerCase();
            document.querySelectorAll("#debts-main-table tbody tr[data-client-name]").forEach(row => {
                const match = row.getAttribute("data-client-name").includes(query);
                row.style.display = match ? "" : "none";
                const detailRow = row.nextElementSibling;
                if (detailRow && !detailRow.hasAttribute('data-client-name')) {
                    detailRow.style.display = match ? "" : "none";
                }
            });
        }
    </script>
</body>
</html>