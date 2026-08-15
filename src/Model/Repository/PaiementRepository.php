<?php
require_once dirname(__DIR__) . '/Entity/Paiement.php';
require_once dirname(__DIR__, 2) . '/Core/Database.php';

class PaiementRepository {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function findById(int $id): ?Paiement {
        $stmt = $this->pdo->prepare("SELECT * FROM paiements WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        if (!$row) return null;

        return new Paiement($row['id'], $row['commande_id'], $row['dette_id'], $row['montant'], $row['date_paiement'], $row['mode_paiement']);
    }

    public function getAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM paiements ORDER BY date_paiement DESC");
        $paiements = [];

        foreach ($stmt->fetchAll() as $row) {
            $paiements[] = new Paiement($row['id'], $row['commande_id'], $row['dette_id'], $row['montant'], $row['date_paiement'], $row['mode_paiement']);
        }

        return $paiements;
    }

    public function save(Paiement $paiement): void {
        $sql = "INSERT INTO paiements (commande_id, dette_id, montant, date_paiement, mode_paiement)
                VALUES (:commande_id, :dette_id, :montant, :date_paiement, :mode_paiement)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':commande_id' => $paiement->getCommandeId(),
            ':dette_id' => $paiement->getDetteId(),
            ':montant' => $paiement->getMontant(),
            ':date_paiement' => $paiement->getDatePaiement(),
            ':mode_paiement' => $paiement->getModePaiement()
        ]);
    }
}