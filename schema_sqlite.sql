CREATE TABLE clients (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(100) NOT NULL,
    telephone VARCHAR(20),
    email VARCHAR(150),
    adresse TEXT,
    limite_credit NUMERIC(10,2) NOT NULL DEFAULT 0 CHECK (limite_credit >= 0)
);

CREATE TABLE produits (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(150) NOT NULL,
    prix_vente NUMERIC(10,2) NOT NULL CHECK (prix_vente >= 0),
    prix_achat NUMERIC(10,2) NOT NULL CHECK (prix_achat >= 0),
    quantite_stock INTEGER NOT NULL DEFAULT 0 CHECK (quantite_stock >= 0),
    seuil_alerte INTEGER NOT NULL DEFAULT 0 CHECK (seuil_alerte >= 0)
);

CREATE TABLE fournisseurs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(100) NOT NULL,
    telephone VARCHAR(20),
    adresse TEXT
);

CREATE TABLE utilisateurs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL CHECK (role IN ('admin', 'vente', 'stock', 'inventaire'))
);

CREATE TABLE commandes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id INTEGER NOT NULL REFERENCES clients(id),
    utilisateur_id INTEGER NOT NULL REFERENCES utilisateurs(id),
    date_commande TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    statut VARCHAR(20) NOT NULL DEFAULT 'EN_COURS' CHECK (statut IN ('EN_COURS', 'VALIDEE', 'ANNULEE')),
    montant_total NUMERIC(10,2) NOT NULL DEFAULT 0 CHECK (montant_total >= 0)
);

CREATE TABLE lignes_commande (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    commande_id INTEGER NOT NULL REFERENCES commandes(id),
    produit_id INTEGER NOT NULL REFERENCES produits(id),
    quantite INTEGER NOT NULL CHECK (quantite > 0),
    prix_unitaire NUMERIC(10,2) NOT NULL CHECK (prix_unitaire >= 0)
);

CREATE TABLE dettes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    commande_id INTEGER NOT NULL REFERENCES commandes(id),
    montant_initial NUMERIC(10,2) NOT NULL CHECK (montant_initial >= 0),
    montant_restant NUMERIC(10,2) NOT NULL CHECK (montant_restant >= 0 AND montant_restant <= montant_initial),
    statut VARCHAR(20) NOT NULL DEFAULT 'EN_COURS' CHECK (statut IN ('EN_COURS', 'SOLDEE'))
);

CREATE TABLE paiements (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    commande_id INTEGER REFERENCES commandes(id),
    dette_id INTEGER REFERENCES dettes(id),
    montant NUMERIC(10,2) NOT NULL CHECK (montant > 0),
    date_paiement TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    mode_paiement VARCHAR(20) NOT NULL CHECK (mode_paiement IN ('ESPECES', 'MOBILE_MONEY', 'CHEQUE'))
);

CREATE TABLE approvisionnements (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    fournisseur_id INTEGER NOT NULL REFERENCES fournisseurs(id),
    utilisateur_id INTEGER NOT NULL REFERENCES utilisateurs(id),
    date_reception TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    numero_bl VARCHAR(50) NOT NULL
);

CREATE TABLE lignes_approvisionnement (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    approvisionnement_id INTEGER NOT NULL REFERENCES approvisionnements(id),
    produit_id INTEGER NOT NULL REFERENCES produits(id),
    quantite_recue INTEGER NOT NULL CHECK (quantite_recue > 0),
    prix_achat_unitaire NUMERIC(10,2) NOT NULL CHECK (prix_achat_unitaire >= 0)
);

NTO utilisateurs (nom, email, mot_de_passe, role) VALUES ('Test Admin', 'test@storemanager.sn', 'x', 'admin');


INSERT INTO clients (nom, telephone, email, adresse, limite_credit) VALUES
('Diallo Ibrahima', '776543210', 'ibrahima@test.sn', 'Dakar', 100000);

INSERT INTO produits (nom, prix_vente, prix_achat, quantite_stock, seuil_alerte) VALUES
('Sac de riz 50kg', 25000, 21000, 100, 10);

-- Utilisateurs
INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES
('Admin Test', 'admin@storemanager.sn', 'demo1234', 'admin');

INSERT INTO clients (nom, telephone, email, adresse, limite_credit) VALUES
('Diallo Ibrahima', '776543210', 'ibrahima@test.sn', 'Dakar', 100000),
('Awa Cisse', '783332211', 'awa@test.sn', 'Pikine', 300000),
('Moussa Sarr', '769876543', 'moussa@test.sn', 'Guediawaye', 250000);

INSERT INTO fournisseurs (nom, telephone, adresse) VALUES
('Comptoir Cerealier Senegalais', '338245678', 'Port de Dakar, Hangar 4'),
('Grossiste Diop et Freres', '773456789', 'Marche Grand Yoff');

INSERT INTO produits (nom, prix_vente, prix_achat, quantite_stock, seuil_alerte) VALUES
('Sac de riz 50kg', 25000, 21000, 100, 10),
('Bidon huile 5L', 8000, 7000, 20, 5),
('Carton de savon', 12000, 10000, 15, 5),
('Paquet de sucre 1kg', 1500, 1200, 200, 20);

SELECT nom FROM clients;

INSERT INTO utilisateurs (id, nom, email, mot_de_passe, role) VALUES
    (1, 'Fatou Admin',     'admin@storemanager.sn',      'admin123',      'admin'),
    (2, 'Moussa Vente',    'vente@storemanager.sn',       'vente123',      'vente'),
    (3, 'Awa Stock',       'stock@storemanager.sn',       'stock123',      'stock'),
    (4, 'Ibou Inventaire', 'inventaire@storemanager.sn',  'inventaire123', 'inventaire');

-- Clients
INSERT INTO clients (id, nom, telephone, adresse, limite_credit) VALUES
    (1, 'Ndeye Diop',    '77 123 45 67', 'Parcelles Assainies, Dakar', 50000),
    (2, 'Cheikh Fall',   '78 234 56 78', 'Sicap Liberté, Dakar',       25000),
    (3, 'Aissatou Sarr', '76 345 67 89', 'Grand Yoff, Dakar',          10000);

INSERT INTO produits (id, nom, prix_vente, prix_achat, quantite_stock, seuil_alerte) VALUES
    (1, 'Sac de riz 50kg',     22500, 20000, 30, 5),
    (2, 'Huile 5L',             6500,  5500, 40, 10),
    (3, 'Sucre 1kg',             800,   650, 100, 20),
    (4, 'Savon Marseille (lot)', 1500,  1100, 15, 5);

INSERT INTO fournisseurs (id, nom, telephone, adresse) VALUES
    (1, 'Grossiste SENEGRAIN', '33 800 11 22', 'Zone Industrielle, Dakar'),
    (2, 'Import Export SAHEL', '33 900 33 44', 'Port de Dakar');

INSERT INTO commandes (id, client_id, utilisateur_id, statut, montant_total) VALUES
    (1, 1, 2, 'VALIDEE', 45000);

INSERT INTO lignes_commande (commande_id, produit_id, quantite, prix_unitaire) VALUES
    (1, 1, 2, 22500);

INSERT INTO commandes (id, client_id, utilisateur_id, statut, montant_total) VALUES
    (2, 2, 2, 'VALIDEE', 19500);

INSERT INTO lignes_commande (commande_id, produit_id, quantite, prix_unitaire) VALUES
    (2, 2, 3, 6500);

INSERT INTO dettes (id, client_id, commande_id, montant_initial, montant_restant, statut) VALUES
    (1, 2, 2, 19500, 12000, 'EN_COURS');

INSERT INTO paiements (dette_id, utilisateur_id, montant, mode_paiement) VALUES
    (1, 2, 7500, 'ESPECES');

INSERT INTO commandes (id, client_id, utilisateur_id, statut, montant_total) VALUES
    (3, 3, 2, 'VALIDEE', 8000);

INSERT INTO lignes_commande (commande_id, produit_id, quantite, prix_unitaire) VALUES
    (3, 3, 10, 800);

INSERT INTO dettes (id, client_id, commande_id, montant_initial, montant_restant, statut) VALUES
    (2, 3, 3, 8000, 0, 'SOLDEE');

INSERT INTO paiements (dette_id, utilisateur_id, montant, mode_paiement) VALUES
    (2, 2, 8000, 'MOBILE_MONEY');

INSERT INTO approvisionnements (id, fournisseur_id, utilisateur_id, numero_bl, statut) VALUES
    (1, 1, 3, 'BL-2026-001', 'COMMANDE'),
    (2, 2, 3, 'BL-2026-002', 'RECEPTIONNE');

INSERT INTO lignes_approvisionnement (approvisionnement_id, produit_id, quantite_recue, prix_achat_unitaire) VALUES
    (1, 1, 0, 20000),   
    (2, 4, 50, 1100);   


ALTER TABLE approvisionnements
    ADD COLUMN statut VARCHAR(20) NOT NULL DEFAULT 'COMMANDE'
    CHECK (statut IN ('COMMANDE', 'RECEPTIONNE'));
 
ALTER TABLE approvisionnements
    ALTER COLUMN date_reception DROP NOT NULL;
 
ALTER TABLE approvisionnements
    ALTER COLUMN date_reception DROP DEFAULT;

UPDATE approvisionnements SET statut = 'RECEPTIONNE' WHERE date_reception IS NOT NULL;
 
