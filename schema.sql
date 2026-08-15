CREATE TABLE clients (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    telephone VARCHAR(20),
    email VARCHAR(150),
    adresse TEXT,
    limite_credit NUMERIC(10,2) NOT NULL DEFAULT 0 CHECK (limite_credit >= 0)
);

CREATE TABLE produits (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    prix_vente NUMERIC(10,2) NOT NULL CHECK (prix_vente >= 0),
    prix_achat NUMERIC(10,2) NOT NULL CHECK (prix_achat >= 0),
    quantite_stock INTEGER NOT NULL DEFAULT 0 CHECK (quantite_stock >= 0),
    seuil_alerte INTEGER NOT NULL DEFAULT 0 CHECK (seuil_alerte >= 0)
);

CREATE TABLE fournisseurs (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    telephone VARCHAR(20),
    adresse TEXT
);

CREATE TABLE utilisateurs (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL CHECK (role IN ('admin', 'vente', 'stock', 'inventaire'))
);

CREATE TABLE commandes (
    id SERIAL PRIMARY KEY,
    client_id INTEGER NOT NULL REFERENCES clients(id),
    utilisateur_id INTEGER NOT NULL REFERENCES utilisateurs(id),
    date_commande TIMESTAMP NOT NULL DEFAULT NOW(),
    statut VARCHAR(20) NOT NULL DEFAULT 'EN_COURS' CHECK (statut IN ('EN_COURS', 'VALIDEE', 'ANNULEE')),
    montant_total NUMERIC(10,2) NOT NULL DEFAULT 0 CHECK (montant_total >= 0)
);

CREATE TABLE lignes_commande (
    id SERIAL PRIMARY KEY,
    commande_id INTEGER NOT NULL REFERENCES commandes(id),
    produit_id INTEGER NOT NULL REFERENCES produits(id),
    quantite INTEGER NOT NULL CHECK (quantite > 0),
    prix_unitaire NUMERIC(10,2) NOT NULL CHECK (prix_unitaire >= 0)
);

CREATE TABLE dettes (
    id SERIAL PRIMARY KEY,
    commande_id INTEGER NOT NULL REFERENCES commandes(id),
    montant_initial NUMERIC(10,2) NOT NULL CHECK (montant_initial >= 0),
    montant_restant NUMERIC(10,2) NOT NULL CHECK (montant_restant >= 0 AND montant_restant <= montant_initial),
    statut VARCHAR(20) NOT NULL DEFAULT 'EN_COURS' CHECK (statut IN ('EN_COURS', 'SOLDEE'))
);

CREATE TABLE paiements (
    id SERIAL PRIMARY KEY,
    commande_id INTEGER REFERENCES commandes(id),
    dette_id INTEGER REFERENCES dettes(id),
    montant NUMERIC(10,2) NOT NULL CHECK (montant > 0),
    date_paiement TIMESTAMP NOT NULL DEFAULT NOW(),
    mode_paiement VARCHAR(20) NOT NULL CHECK (mode_paiement IN ('ESPECES', 'MOBILE_MONEY', 'CHEQUE'))
);

CREATE TABLE approvisionnements (
    id SERIAL PRIMARY KEY,
    fournisseur_id INTEGER NOT NULL REFERENCES fournisseurs(id),
    utilisateur_id INTEGER NOT NULL REFERENCES utilisateurs(id),
    date_reception TIMESTAMP NOT NULL DEFAULT NOW(),
    numero_bl VARCHAR(50) NOT NULL
);

CREATE TABLE lignes_approvisionnement (
    id SERIAL PRIMARY KEY,
    approvisionnement_id INTEGER NOT NULL REFERENCES approvisionnements(id),
    produit_id INTEGER NOT NULL REFERENCES produits(id),
    quantite_recue INTEGER NOT NULL CHECK (quantite_recue > 0),
    prix_achat_unitaire NUMERIC(10,2) NOT NULL CHECK (prix_achat_unitaire >= 0)
);