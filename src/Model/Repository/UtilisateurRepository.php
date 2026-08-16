<?php
require_once dirname(__DIR__) . '/Entity/Utilisateur.php';
require_once dirname(__DIR__, 2) . '/Core/Database.php';

class UtilisateurRepository {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function findById(int $id): ?Utilisateur {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        if (!$row) return null;

        return new Utilisateur($row['id'], $row['nom'], $row['email'], $row['mot_de_passe'], $row['role']);
    }

    public function findByEmail(string $email): ?Utilisateur {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch();
        if (!$row) return null;

        return new Utilisateur($row['id'], $row['nom'], $row['email'], $row['mot_de_passe'], $row['role']);
    }

    public function getAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM utilisateurs ORDER BY nom");
        $utilisateurs = [];

        foreach ($stmt->fetchAll() as $row) {
            $utilisateurs[] = new Utilisateur($row['id'], $row['nom'], $row['email'], $row['mot_de_passe'], $row['role']);
        }

        return $utilisateurs;
    }

    public function save(Utilisateur $utilisateur): void {
        $sql = "INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES (:nom, :email, :mot_de_passe, :role)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nom' => $utilisateur->getNom(),
            ':email' => $utilisateur->getEmail(),
            ':mot_de_passe' => $utilisateur->getMotDePasse(),
            ':role' => $utilisateur->getRole()
        ]);
    }
}
