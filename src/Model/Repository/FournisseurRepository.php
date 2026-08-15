<?php
require_once dirname(__DIR__) . '/Entity/Fournisseur.php';
require_once dirname(__DIR__, 2) . '/Core/Database.php';

class FournisseurRepository {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function findById(int $id): ?Fournisseur {
        $stmt = $this->pdo->prepare("SELECT * FROM fournisseurs WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        if (!$row) return null;

        return new Fournisseur($row['id'], $row['nom'], $row['telephone'], $row['adresse']);
    }

    public function getAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM fournisseurs ORDER BY nom");
        $fournisseurs = [];

        foreach ($stmt->fetchAll() as $row) {
            $fournisseurs[] = new Fournisseur($row['id'], $row['nom'], $row['telephone'], $row['adresse']);
        }

        return $fournisseurs;
    }

    public function save(Fournisseur $fournisseur): void {
        $sql = "INSERT INTO fournisseurs (nom, telephone, adresse) VALUES (:nom, :telephone, :adresse)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nom' => $fournisseur->getNom(),
            ':telephone' => $fournisseur->getTelephone(),
            ':adresse' => $fournisseur->getAdresse()
        ]);
    }
}