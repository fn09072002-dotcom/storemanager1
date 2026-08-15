<?php
require_once dirname(__DIR__) . '/Entity/Commande.php';
require_once dirname(__DIR__, 2) . '/Core/Database.php';

class CommandeRepository {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function findById(int $id): ?Commande {
        $stmt = $this->pdo->prepare("SELECT * FROM commandes WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        if (!$row) return null;

        return new Commande($row['id'], $row['client_id'], $row['utilisateur_id'], $row['date_commande'], $row['statut'], $row['montant_total']);
    }

    public function getAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM commandes ORDER BY date_commande DESC");
        $commandes = [];

        foreach ($stmt->fetchAll() as $row) {
            $commandes[] = new Commande($row['id'], $row['client_id'], $row['utilisateur_id'], $row['date_commande'], $row['statut'], $row['montant_total']);
        }

        return $commandes;
    }

    public function save(Commande $commande): int {
        $sql = "INSERT INTO commandes (client_id, utilisateur_id, date_commande, statut, montant_total)
                VALUES (:client_id, :utilisateur_id, :date_commande, :statut, :montant_total)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':client_id' => $commande->getClientId(),
            ':utilisateur_id' => $commande->getUtilisateurId(),
            ':date_commande' => $commande->getDateCommande(),
            ':statut' => $commande->getStatut(),
            ':montant_total' => $commande->getMontantTotal()
        ]);
        return (int) $this->pdo->lastInsertId();
    }
}