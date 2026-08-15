<?php
require_once dirname(__DIR__) . '/Entity/Produit.php';
require_once dirname(__DIR__, 2) . '/Core/Database.php';

class ProduitRepository {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function findById(int $id): ?Produit {
        $stmt = $this->pdo->prepare("SELECT * FROM produits WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        if (!$row) return null;

        return new Produit(
            $row['id'],
            $row['nom'],
            $row['prix_vente'],
            $row['prix_achat'],
            $row['quantite_stock'],
            $row['seuil_alerte']
        );
    }

    public function getAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM produits ORDER BY nom");
        $produits = [];

        foreach ($stmt->fetchAll() as $row) {
            $produits[] = new Produit(
                $row['id'],
                $row['nom'],
                $row['prix_vente'],
                $row['prix_achat'],
                $row['quantite_stock'],
                $row['seuil_alerte']
            );
        }

        return $produits;
    }

    public function save(Produit $produit): void {
        $sql = "INSERT INTO produits (nom, prix_vente, prix_achat, quantite_stock, seuil_alerte)
                VALUES (:nom, :prix_vente, :prix_achat, :quantite_stock, :seuil_alerte)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nom' => $produit->getNom(),
            ':prix_vente' => $produit->getPrixVente(),
            ':prix_achat' => $produit->getPrixAchat(),
            ':quantite_stock' => $produit->getQuantiteStock(),
            ':seuil_alerte' => $produit->getSeuilAlerte()
        ]);
    }

    public function updateStock(int $produitId, int $nouvelleQuantite): void {
    $sql = "UPDATE produits SET quantite_stock = :quantite WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':quantite' => $nouvelleQuantite, ':id' => $produitId]);
}
}