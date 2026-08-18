<?php
require_once dirname(__DIR__) . '/Entity/Approvisionnement.php';
require_once dirname(__DIR__) . '/Entity/LigneApprovisionnement.php';
require_once dirname(__DIR__) . '/Repository/FournisseurRepository.php';
require_once dirname(__DIR__) . '/Repository/UtilisateurRepository.php';
require_once dirname(__DIR__) . '/Repository/ProduitRepository.php';
require_once dirname(__DIR__, 2) . '/Core/Database.php';

class ApprovisionnementRepository {
    private PDO $pdo;
    private FournisseurRepository $fournisseurRepository;
    private UtilisateurRepository $utilisateurRepository;
    private ProduitRepository $produitRepository;

    public function __construct() {
        $this->pdo = Database::getInstance();
        $this->fournisseurRepository = new FournisseurRepository();
        $this->utilisateurRepository = new UtilisateurRepository();
        $this->produitRepository = new ProduitRepository();
    }

    private function mapRow(array $row): Approvisionnement {
        $fournisseur = $this->fournisseurRepository->findById((int) $row['fournisseur_id']);
        $utilisateur = $this->utilisateurRepository->findById((int) $row['utilisateur_id']);

        return new Approvisionnement(
            (int) $row['id'],
            $fournisseur,
            $utilisateur,
            $row['numero_bl'],
            $row['statut'],
            $row['date_reception']
        );
    }

    public function findById(int $id): ?Approvisionnement {
        $stmt = $this->pdo->prepare("SELECT * FROM approvisionnements WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        if (!$row) return null;

        return $this->mapRow($row);
    }

    public function getAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM approvisionnements ORDER BY id DESC");
        $liste = [];
        foreach ($stmt->fetchAll() as $row) {
            $liste[] = $this->mapRow($row);
        }
        return $liste;
    }

    public function findByStatut(string $statut): array {
        $stmt = $this->pdo->prepare("SELECT * FROM approvisionnements WHERE statut = :statut ORDER BY id DESC");
        $stmt->execute([':statut' => $statut]);
        $liste = [];
        foreach ($stmt->fetchAll() as $row) {
            $liste[] = $this->mapRow($row);
        }
        return $liste;
    }

    public function save(Fournisseur $fournisseur, Utilisateur $utilisateur, string $numeroBL): int {
        $sql = "INSERT INTO approvisionnements (fournisseur_id, utilisateur_id, numero_bl, statut)
                VALUES (:fournisseur_id, :utilisateur_id, :numero_bl, 'COMMANDE')
                RETURNING id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':fournisseur_id' => $fournisseur->getId(),
            ':utilisateur_id' => $utilisateur->getId(),
            ':numero_bl' => $numeroBL,
        ]);

        return (int) $stmt->fetchColumn();
    }

    public function marquerReceptionne(int $approvisionnementId, string $dateReception): void {
        $sql = "UPDATE approvisionnements
                SET statut = 'RECEPTIONNE', date_reception = :date_reception
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':date_reception' => $dateReception,
            ':id' => $approvisionnementId,
        ]);
    }

    public function saveLigne(LigneApprovisionnement $ligne): void {
        $sql = "INSERT INTO lignes_approvisionnement (approvisionnement_id, produit_id, quantite_recue, prix_achat_unitaire)
                VALUES (:approvisionnement_id, :produit_id, :quantite_recue, :prix_achat_unitaire)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':approvisionnement_id' => $ligne->getApprovisionnement()->getId(),
            ':produit_id' => $ligne->getProduit()->getId(),
            ':quantite_recue' => $ligne->getQuantiteRecue(),
            ':prix_achat_unitaire' => $ligne->getPrixAchatUnitaire(),
        ]);
    }

    public function findLignesByApprovisionnement(Approvisionnement $approvisionnement): array {
        $stmt = $this->pdo->prepare("SELECT * FROM lignes_approvisionnement WHERE approvisionnement_id = :id");
        $stmt->execute([':id' => $approvisionnement->getId()]);
        $lignes = [];
        foreach ($stmt->fetchAll() as $row) {
            $produit = $this->produitRepository->findById((int) $row['produit_id']);

            $lignes[] = new LigneApprovisionnement(
                $approvisionnement,
                $produit,
                (int) $row['quantite_recue'],
                (float) $row['prix_achat_unitaire'],
                (int) $row['id']
            );
        }
        return $lignes;
    }
}