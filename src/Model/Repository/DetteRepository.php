<?php
require_once dirname(__DIR__) . '/Entity/Dette.php';
require_once dirname(__DIR__, 2) . '/Core/Database.php';

class DetteRepository {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function findById(int $id): ?Dette {
        $stmt = $this->pdo->prepare("SELECT * FROM dettes WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        if (!$row) return null;

        return new Dette($row['id'], $row['commande_id'], $row['montant_initial'], $row['montant_restant'], $row['statut']);
    }

    public function getAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM dettes ORDER BY id DESC");
        $dettes = [];

        foreach ($stmt->fetchAll() as $row) {
            $dettes[] = new Dette($row['id'], $row['commande_id'], $row['montant_initial'], $row['montant_restant'], $row['statut']);
        }

        return $dettes;
    }

    public function save(Dette $dette): void {
        $sql = "INSERT INTO dettes (commande_id, montant_initial, montant_restant, statut)
                VALUES (:commande_id, :montant_initial, :montant_restant, :statut)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':commande_id' => $dette->getCommandeId(),
            ':montant_initial' => $dette->getMontantInitial(),
            ':montant_restant' => $dette->getMontantRestant(),
            ':statut' => $dette->getStatut()
        ]);
    }

    // Spécifique aux dettes : après enregistrerRemboursement() côté objet, on répercute en base
    public function update(Dette $dette): void {
        $sql = "UPDATE dettes SET montant_restant = :montant_restant, statut = :statut WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':montant_restant' => $dette->getMontantRestant(),
            ':statut' => $dette->getStatut(),
            ':id' => $dette->getId()
        ]);
    }
}