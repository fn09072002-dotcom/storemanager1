<?php
require_once dirname(__DIR__) . '/Entity/Approvisionnement.php';
require_once dirname(__DIR__, 2) . '/Core/Database.php';

class ApprovisionnementRepository {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function findById(int $id): ?Approvisionnement {
        $stmt = $this->pdo->prepare("SELECT * FROM approvisionnements WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        if (!$row) return null;

        return new Approvisionnement($row['id'], $row['fournisseur_id'], $row['utilisateur_id'], $row['date_reception'], $row['numero_bl']);
    }

    public function getAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM approvisionnements ORDER BY date_reception DESC");
        $approvisionnements = [];

        foreach ($stmt->fetchAll() as $row) {
            $approvisionnements[] = new Approvisionnement($row['id'], $row['fournisseur_id'], $row['utilisateur_id'], $row['date_reception'], $row['numero_bl']);
        }

        return $approvisionnements;
    }

    public function save(Approvisionnement $approvisionnement): int {
        $sql = "INSERT INTO approvisionnements (fournisseur_id, utilisateur_id, date_reception, numero_bl)
                VALUES (:fournisseur_id, :utilisateur_id, :date_reception, :numero_bl)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':fournisseur_id' => $approvisionnement->getFournisseurId(),
            ':utilisateur_id' => $approvisionnement->getUtilisateurId(),
            ':date_reception' => $approvisionnement->getDateReception(),
            ':numero_bl' => $approvisionnement->getNumeroBL()
        ]);
        return (int) $this->pdo->lastInsertId();
    }
}