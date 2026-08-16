<?php

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StoreManager Pro — Connexion</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0b0f19;
            --border-color: rgba(45, 212, 191, 0.12);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --accent: #2dd4bf;
            --accent-glow: rgba(45, 212, 191, 0.1);
            --danger: #f87171;
            --font-family: 'Plus Jakarta Sans', sans-serif;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: var(--font-family); }
    </style>
</head>
<body>

    <div style="position: fixed; inset: 0; background: var(--bg-color); display: grid; grid-template-columns: 1.1fr 1fr; color: var(--text-main);">

        <!-- Colonne Gauche : Branding -->
        <div style="background: linear-gradient(135deg, #0b0f19 0%, #111827 50%, #0d1b2a 100%); border-right: 1px solid var(--border-color); padding: 48px; display: flex; flex-direction: column; justify-content: space-between; position: relative; overflow: hidden;">
            <div style="position: absolute; width: 650px; height: 650px; border-radius: 50%; border: 1px solid rgba(45, 212, 191, 0.08); bottom: -200px; left: -100px; pointer-events: none;"></div>
            <div style="position: absolute; width: 250px; height: 250px; border-radius: 50%; background: radial-gradient(circle, rgba(45, 212, 191, 0.15) 0%, transparent 70%); top: 20%; right: 10%; pointer-events: none;"></div>

            <div style="display: flex; align-items: center; gap: 12px; z-index: 2;">
                <div style="background: rgba(22, 30, 49, 0.8); border: 1px solid var(--border-color); padding: 10px 20px; border-radius: 14px; display: flex; align-items: center; gap: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                    <span style="font-size: 26px;">📦</span>
                    <div>
                        <div style="font-weight: 800; color: var(--accent); font-size: 17px; line-height: 1.1;">StoreManager Pro</div>
                        <div style="font-size: 9px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Gérez aujourd'hui, réussissez demain.</div>
                    </div>
                </div>
            </div>

            <div style="max-width: 520px; z-index: 2; margin: 60px 0;">
                <div style="display: inline-block; background: var(--accent-glow); border: 1px solid var(--accent); border-radius: 20px; padding: 6px 14px; font-size: 11px; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 24px; color: var(--accent);">
                    COMMERCE • SÉNÉGAL
                </div>
                <h1 style="font-size: 42px; font-weight: 800; line-height: 1.15; margin-bottom: 20px; color: #ffffff;">
                    Une boutique mieux pilotée,<br>
                    <span style="color: var(--accent);">une rentabilité optimisée.</span>
                </h1>
                <p style="font-size: 15px; color: var(--text-muted); line-height: 1.6; font-weight: 400;">
                    Ventes, stock, dettes clients et suivi fournisseurs réunis dans un espace clair, rapide et taillé pour le commerce moderne.
                </p>
            </div>

            <div style="font-size: 11px; color: var(--text-muted); z-index: 2;">
                Conçu pour les commerces et boutiques au Sénégal.
            </div>
        </div>

        <!-- Colonne Droite : Formulaire -->
        <div style="background: #0f1523; padding: 48px 64px; display: flex; flex-direction: column; justify-content: center; overflow-y: auto;">
            <div style="max-width: 420px; width: 100%; margin: 0 auto;">

                <div style="font-size: 11px; font-weight: 800; color: var(--accent); letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 6px;">
                    RAVI DE VOUS REVOIR
                </div>
                <h2 style="font-size: 30px; font-weight: 800; color: #ffffff; margin-bottom: 8px;">
                    Connexion à StoreManager
                </h2>
                <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 28px;">
                    Saisissez vos identifiants pour accéder à votre espace.
                </p>

                <?php if (!empty($erreur)): ?>
                    <div style="background: rgba(248,113,113,0.1); border-left: 4px solid var(--danger); color: var(--danger); padding: 12px 16px; border-radius: 10px; font-size: 13px; margin-bottom: 20px;">
                        <?= htmlspecialchars($erreur) ?>
                    </div>
                <?php endif; ?>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 24px;">
                    <div onclick="remplir('admin@storemanager.sn')" style="background: rgba(22, 30, 49, 0.4); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 14px; display: flex; align-items: center; gap: 12px; cursor: pointer;">
                        <div style="width: 36px; height: 36px; background: rgba(45, 212, 191, 0.15); border: 1px solid var(--accent); border-radius: 10px; font-size: 11px; font-weight: 800; color: var(--accent); display: flex; align-items: center; justify-content: center;">AB</div>
                        <div>
                            <div style="font-weight: 700; font-size: 12px; color: #ffffff;">Admin Boutique</div>
                            <div style="font-size: 9px; color: var(--text-muted);">Pilotage complet</div>
                        </div>
                    </div>
                    <div onclick="remplir('vente@storemanager.sn')" style="background: rgba(22, 30, 49, 0.4); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 14px; display: flex; align-items: center; gap: 12px; cursor: pointer;">
                        <div style="width: 36px; height: 36px; background: rgba(56, 189, 248, 0.15); border: 1px solid #38bdf8; border-radius: 10px; font-size: 11px; font-weight: 800; color: #38bdf8; display: flex; align-items: center; justify-content: center;">CV</div>
                        <div>
                            <div style="font-weight: 700; font-size: 12px; color: #ffffff;">Chargé de Vente</div>
                            <div style="font-size: 9px; color: var(--text-muted);">Caisse & Dettes</div>
                        </div>
                    </div>
                    <div onclick="remplir('stock@storemanager.sn')" style="background: rgba(22, 30, 49, 0.4); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 14px; display: flex; align-items: center; gap: 12px; cursor: pointer;">
                        <div style="width: 36px; height: 36px; background: rgba(251, 191, 36, 0.15); border: 1px solid #fbbf24; border-radius: 10px; font-size: 11px; font-weight: 800; color: #fbbf24; display: flex; align-items: center; justify-content: center;">CS</div>
                        <div>
                            <div style="font-weight: 700; font-size: 12px; color: #ffffff;">Chargé de Stock</div>
                            <div style="font-size: 9px; color: var(--text-muted);">Appro & Réception</div>
                        </div>
                    </div>
                    <div onclick="remplir('inventaire@storemanager.sn')" style="background: rgba(22, 30, 49, 0.4); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 14px; display: flex; align-items: center; gap: 12px; cursor: pointer;">
                        <div style="width: 36px; height: 36px; background: rgba(192, 132, 252, 0.15); border: 1px solid #c084fc; border-radius: 10px; font-size: 11px; font-weight: 800; color: #c084fc; display: flex; align-items: center; justify-content: center;">IV</div>
                        <div>
                            <div style="font-weight: 700; font-size: 12px; color: #ffffff;">Inventaire</div>
                            <div style="font-size: 9px; color: var(--text-muted);">Consultation</div>
                        </div>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                    <div style="flex: 1; height: 1px; background: rgba(255,255,255,0.08);"></div>
                    <div style="font-size: 11px; color: var(--text-muted);">ou entrez vos identifiants</div>
                    <div style="flex: 1; height: 1px; background: rgba(255,255,255,0.08);"></div>
                </div>

                <form method="POST" action="/login" style="display: flex; flex-direction: column; gap: 16px;">
                    <div>
                        <label style="font-size: 11px; font-weight: 700; color: var(--text-muted); display: block; margin-bottom: 6px; text-transform: uppercase;">Adresse email</label>
                        <input type="email" name="email" id="login-email" placeholder="vous@storemanager.sn" style="width: 100%; padding: 12px 14px; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 10px; color: #ffffff; font-size: 13px;" required>
                    </div>

                    <div>
                        <label style="font-size: 11px; font-weight: 700; color: var(--text-muted); display: block; margin-bottom: 6px; text-transform: uppercase;">Mot de passe</label>
                        <input type="password" name="password" id="login-password" placeholder="Votre mot de passe" style="width: 100%; padding: 12px 14px; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 10px; color: #ffffff; font-size: 13px;" required>
                    </div>

                    <button type="submit" style="background: linear-gradient(135deg, var(--accent) 0%, #0d9488 100%); color: #0b0f19; border: none; border-radius: 12px; padding: 14px; font-weight: 800; font-size: 14px; text-transform: uppercase; letter-spacing: 1px; cursor: pointer; margin-top: 8px; box-shadow: 0 10px 25px rgba(45, 212, 191, 0.25);">
                        Se connecter ➔
                    </button>
                </form>

                <div style="text-align: center; margin-top: 18px; font-size: 11px; color: var(--text-muted);">
                    ✓ Tous les comptes utilisent le mot de passe : <strong style="color: var(--accent);">demo1234</strong>
                </div>

                <script>
                    function remplir(email) {
                        document.getElementById('login-email').value = email;
                        document.getElementById('login-password').value = 'demo1234';
                    }
                </script>

            </div>
        </div>
    </div>
</body>
</html>