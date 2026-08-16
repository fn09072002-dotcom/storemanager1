
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


###  [ Phase 2] : POO, Repositories & Ventes POS

#### Première étape : les entités

- **Heure de réalisation** :
- **Ce qui a été fait** :
  - Ajout de l'attribut email au Client (diagramme, schéma SQL, entité) — repéré
    en confrontant l'interface (maquette HTML) au modèle existant
  - 8 entités POO créées dans src/Model/Entity/ : Produit, Client, Dette,
    Fournisseur, Utilisateur, Commande, Paiement, Approvisionnement
  - Méthodes métier encapsulées dans les entités : estEnRupture() (Produit),
    peutAcheterACredit() (Client), enregistrerRemboursement()/estSoldee() (Dette),
    aLeRole() (Utilisateur), estValidee() (Commande)
- **Difficultés / Obstacles** :
  - Confusion récurrente entre = (affectation) et ===/== (comparaison) dans
    les conditions if — corrigé sur Dette::enregistrerRemboursement() et
    Dette::estSoldee()
  - Pour estSoldee(), j'ai d'abord comparé montantRestant === 0 au lieu de
    statut === 'SOLDEE' — risque d'imprécision avec les nombres flottants (float),
    le statut texte est plus fiable
  - Confusion sur le type des clés étrangères : mettre le type de la classe
    référencée (ex: Commande $commande) ou juste un int (commandeId) — j'ai
    choisi int, plus simple à gérer pour moi




    #### Deuxième étape : les Repositories


- **Ce qui a été fait** :
  - 8 Repositories créés dans src/Model/Repository/ : ClientRepository,
    ProduitRepository, FournisseurRepository, UtilisateurRepository,
    CommandeRepository, DetteRepository, PaiementRepository,
    ApprovisionnementRepository
  - CommandeRepository et ApprovisionnementRepository::save() retournent
    l'ID généré (lastInsertId()), contrairement aux autres save() qui sont void
  - DetteRepository a une méthode update() en plus des 3 autres (findById,
    getAll, save), car c'est la seule entité dont l'état change après sa
    création (remboursements successifs)
  
- **Difficultés / Obstacles** :
  - Erreur au premier essai de ClientRepository::getAll() : j'avais repris
    le contenu du constructeur (les $this->x = x) à l'intérieur de l'appel
    new Client(...), au lieu de passer juste les valeurs de $row



    #### Troisième étape : VenteService

- **Ce qui a été fait** :
  - VenteService::validerVente() avec transaction SQL (beginTransaction/commit/rollBack)
  - Vérification du stock, calcul du montant total, création de la Commande,
    décrémentation du stock, création d'une Dette si paiement partiel
- **Difficultés / Obstacles** :
  - Plusieurs erreurs de logique (+ au lieu de -, accolade mal fermée)
  - Compréhension du rollBack() : si une étape échoue après qu'une autre a
    déjà été enregistrée, tout est annulé, même ce qui semblait déjà réussi
  

  #### Quatrième étape : POSController et vue caisse

- **Ce qui a été fait** :
  - POSController::afficherCaisse() (GET) et encaisser() (POST), routes /pos et
    /pos/encaisser ajoutées dans Router.php
  - Reconstruction du panier à partir de product_ids[]/product_qtys[] envoyés
    par le formulaire (deux tableaux parallèles, recombinés par index)
  - Vue caisse (views/pos/index.php) : formulaire client + sélection produits
  - SessionManager créé 
- **Difficultés / Obstacles** :
  - Bug le plus long à trouver : les menus déroulants restaient vides alors
    que les données existaient bien en base (confirmé via VS Code). Diagnostic
    par var_dump direct de Database::getInstance() : PHP était connecté à
    storemanager1 comme prévu, mais voyait 0 lignes alors que VS Code en
    voyait 7 sur "la même" base - problème de connexion différente
    

    ###  [ Phase 3] : Dettes, Approvisionnements & Rôles
- **Heure de réalisation** : 09h00 - 11h30
- **Ce qui a été fait** :
  DetteRepository.php (requêtes préparées PDO), DetteService.php avec
  logique de remboursement partiel et mise à jour automatique du statut
  (EN_COURS -> SOLDEE quand montant_restant atteint 0), vue
  views/dettes/index.php affichant le registre des dettes.
- **Difficultés / Obstacles** :
bug rencontrer a cause d'une casse minuscule a la place de majuscule 

mauvais require fichier qui plante


#### Étape : Approvisionnements & Réception BL

- **Ce qui a été fait** :
  - Approvisionnement redesigné avec un vrai statut (COMMANDE -> RECEPTIONNE) et
    date_reception nullable tant que non réceptionné - plus fidèle au métier
    réel qu'un simple champ date toujours rempli
  - Entités/Repository LigneApprovisionnement, SupplyService avec transaction
    SQL (création BL, réception avec incrémentation du stock produit par produit)
  - SupplyController (4 actions : liste, création BL, formulaire réception,
    traitement réception) et vue views/supplies/
- **Difficultés / Obstacles** :
  -
  - Fichier LigneApprovisionement.php créé avec une faute d'orthographe (un seul
    "n") alors que le require_once demandait LigneApprovisionnement.php (deux
    "n") 