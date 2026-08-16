<?php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>StoreManager Pro — Approvisionnements</title>
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
        .panel-card { background: var(--panel-bg); border: 1px solid var(--border-color); border-radius: 24px; padding: 28px; margin-bottom: 24px; }
        .panel-title { font-size: 16px; font-weight: 700; margin-bottom: 20px; border-left: 4px solid var(--accent); padding-left: 12px; display: flex; justify-content: space-between; align-items: center; }
        .search-control { background: rgba(255,255,255,0.03); border: 1px solid var(--border-color); border-radius: 8px; padding: 6px 12px; color: white; font-size: 12px; width: 220px; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th { color: var(--text-muted); font-size: 11px; text-transform: uppercase; text-align: left; padding-bottom: 12px; border-bottom: 1px solid var(--border-color); }
        td { padding: 14px 0; border-bottom: 1px solid rgba(255,255,255,0.03); }
        .btn-quick { background: rgba(255,255,255,0.03); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 8px; padding: 6px 12px; font-size: 11px; font-weight: 700; cursor: pointer; margin-right: 4px; text-decoration: none; display: inline-block; }
        .btn-quick.receptionner { border-color: var(--accent); color: var(--accent); }
        .flash { padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; background: rgba(45,212,191,0.08); border-left: 4px solid var(--accent); font-size: 13px; }
        .badge { padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
        .badge.commande { background: rgba(251,191,36,0.1); color: var(--warning); }
        .badge.receptionne { background: rgba(16,185,129,0.1); color: var(--success); }
        .details-drawer { display: none; background: rgba(255,255,255,0.012); border: 1px solid rgba(255,255,255,0.03); border-radius: 16px; padding: 20px; margin-top: 10px; }
        .details-drawer.open { display: block; }
        .btn-submit { background: linear-gradient(135deg, var(--accent) 0%, #0d9488 100%); color: #0b0f19; border: none; padding: 11px 24px; border-radius: 10px; font-weight: 800; font-size: 12px; text-transform: uppercase; cursor: pointer; }
        .form-control { background: #0b0f19; border: 1px solid var(--border-color); border-radius: 10px; padding: 10px 12px; color: white; font-size: 13px; }
    </style>
</head>
<body>

    <?php $currentPage = 'supplies'; require_once dirname(__DIR__) . '/partials/navbar.php'; ?>

   <?php if (!empty($erreur)): ?>
    <div class="flash"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>

    <div class="kpi-grid">
        <div class="kpi-card" style="border-left: 4px solid var(--warning);">
            <div>
                <div class="kpi-label">BL en attente</div>
                <div class="kpi-val" style="color: var(--warning);"><?= count($bonsEnCours) ?></div>
            </div>
            <span style="font-size:24px;">📦</span>
        </div>
        <div class="kpi-card" style="border-left: 4px solid var(--success);">
            <div>
                <div class="kpi-label">BL réceptionnés</div>
                <div class="kpi-val" style="color: var(--success);"><?= count($bonsReceptionnes) ?></div>
            </div>
            <span style="font-size:24px;">✅</span>
        </div>
        <div class="kpi-card" style="border-left: 4px solid var(--accent);">
            <div>
                <div class="kpi-label">Fournisseurs</div>
                <div class="kpi-val"><?= count($fournisseursParId) ?></div>
            </div>
            <span style="font-size:24px;">🚚</span>
        </div>
    </div>

    <div class="panel-card">
        <div class="panel-title">
            <span>Nouveau Bon de Livraison</span>
        </div>
        <form method="POST" action="/supplies/creer" style="display:flex; gap:16px; align-items:flex-end; flex-wrap:wrap;">
            <div style="flex:1; min-width:200px;">
                <label style="font-size:10px; color:var(--text-muted); display:block; margin-bottom:6px; text-transform:uppercase; font-weight:700;">Fournisseur</label>
                <select name="fournisseur_id" class="form-control" required style="width:100%;">
                    <?php foreach ($fournisseursParId as $id => $fournisseur): ?>
                        <option value="<?= $id ?>"><?= htmlspecialchars($fournisseur->getNom()) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="flex:1; min-width:200px;">
                <label style="font-size:10px; color:var(--text-muted); display:block; margin-bottom:6px; text-transform:uppercase; font-weight:700;">Numéro BL</label>
                <input type="text" name="numero_bl" class="form-control" required style="width:100%;" placeholder="BL-2026-001">
            </div>
            <button type="submit" class="btn-submit">✓ Créer le BL</button>
        </form>
    </div>

    <div class="panel-card">
        <div class="panel-title">
            <span>Bons en Attente de Réception</span>
            <input type="text" id="supply-search" class="search-control" placeholder="Rechercher un fournisseur..." onkeyup="filterSuppliesTable()">
        </div>
        <table id="supplies-main-table">
            <thead>
                <tr><th>N° BL</th><th>Fournisseur</th><th>Statut</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php if (empty($bonsEnCours)): ?>
                    <tr><td colspan="4" style="text-align:center; color:var(--text-muted); padding:16px 0;">Aucun BL en attente.</td></tr>
                <?php else: ?>
                    <?php foreach ($bonsEnCours as $bon): ?>
                        <?php $nomFournisseur = $fournisseursParId[$bon->getFournisseurId()]->getNom() ?? 'Fournisseur inconnu'; ?>
                        <tr data-fournisseur-name="<?= htmlspecialchars(strtolower($nomFournisseur)) ?>">
                            <td style="font-weight:700;">#<?= $bon->getId() ?> — <?= htmlspecialchars($bon->getNumeroBL()) ?></td>
                            <td><?= htmlspecialchars($nomFournisseur) ?></td>
                            <td><span class="badge commande">Commandé</span></td>
                            <td><a href="/supplies/receptionner?id=<?= $bon->getId() ?>" class="btn-quick receptionner">📥 Réceptionner</a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="panel-card">
        <div class="panel-title"><span>Historique des Réceptions</span></div>
        <table>
            <thead>
                <tr><th>N° BL</th><th>Fournisseur</th><th>Statut</th><th>Date réception</th></tr>
            </thead>
            <tbody>
                <?php if (empty($bonsReceptionnes)): ?>
                    <tr><td colspan="4" style="text-align:center; color:var(--text-muted); padding:16px 0;">Aucun BL réceptionné.</td></tr>
                <?php else: ?>
                    <?php foreach ($bonsReceptionnes as $bon): ?>
                        <tr>
                            <td style="font-weight:700;">#<?= $bon->getId() ?> — <?= htmlspecialchars($bon->getNumeroBL()) ?></td>
                            <td><?= htmlspecialchars($fournisseursParId[$bon->getFournisseurId()]->getNom() ?? 'Fournisseur inconnu') ?></td>
                            <td><span class="badge receptionne">Réceptionné</span></td>
                            <td><?= htmlspecialchars($bon->getDateReception() ?? '') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        function filterSuppliesTable() {
            const query = document.getElementById("supply-search").value.toLowerCase();
            document.querySelectorAll("#supplies-main-table tbody tr[data-fournisseur-name]").forEach(row => {
                const match = row.getAttribute("data-fournisseur-name").includes(query);
                row.style.display = match ? "" : "none";
            });
        }
    </script>
</body>
</html>