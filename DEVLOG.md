
- **Ce qui a été fait** :
  Modélisation UML du projet à partir du prototype HTML existant :
  diagramme de cas d'utilisation (4 profils : Admin, Vente, Stock,
  Inventaire) et diagramme de classes (Client, Produit, Fournisseur,
  Commande, LigneCommande, Dette, Paiement, Approvisionnement,
  LigneApprovisionnement, Utilisateur). Fichiers `.puml` 
  placés dans `/docs`.

    - Schéma SQL complet (schema.sql PostgreSQL + schema_sqlite.sql fallback), avec
    contraintes CHECK sur tous les montants/quantités et FK entre les 10 tables
- **Difficultés / Obstacles** :
  - Cardinalité Commande-Dette : j'ai d'abord pensé qu'une commande pouvait avoir
    plusieurs dettes, mais en fait une commande génère AU PLUS UNE dette (0..1) —
    c'est le remboursement de cette dette qui peut se faire en plusieurs paiements
    (relation Dette 0..* Paiement)
  - Distinction entre les profils Stock et Inventaire pas encore claire (à revoir)
  - CHECK combinés avec AND : montant_restant doit être >= 0 ET <= montant_initial,
    pas juste >= 0 tout seul
  - Différence entre une FK obligatoire (NOT NULL, ex: commande_id dans lignes_commande)
    et une FK optionnelle (nullable, ex: paiements.dette_id qui peut être NULL si
    le paiement règle directement une commande sans dette)


