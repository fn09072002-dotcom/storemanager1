<?php
require_once dirname(__DIR__) . '/Entity/Client.php';
require_once dirname(__DIR__, 2) . '/Core/Database.php';

class ClientRepository {
private PDO $pdo;

public function __construct() {
$this->pdo = Database::getInstance();
    }

public function findById(int $id): ?Client {
$stmt = $this->pdo->prepare("SELECT * FROM clients WHERE id = :id");
$stmt->execute([':id' => $id]);
$row = $stmt->fetch();
if (!$row) return null;

return new Client(
$row['id'],
$row['nom'],
$row['telephone'], 
$row['email'], 
$row['adresse'], 
$row['limite_credit']);
    }

public function getAll(): array {
$stmt = $this->pdo->query("SELECT * FROM clients ORDER BY nom");
$clients = [];

foreach ($stmt->fetchAll() as $row) {
$clients[] = new Client(
$row['id'], $row['nom'], 
$row['telephone'], 
$row['email'], 
$row['adresse'], 
$row['limite_credit']);
        }

return $clients;
    }


public function save(Client $client): void {
$sql = "INSERT INTO clients (nom, telephone, email, adresse, limite_credit)
VALUES (:nom, :telephone, :email, :adresse, :limite_credit)";

$stmt = $this->pdo->prepare($sql);
$stmt->execute([
':nom' => $client->getNom(),
':telephone' => $client->getTelephone(),
':email' => $client->getEmail(),
':adresse' => $client->getAdresse(),
':limite_credit' => $client->getLimiteCredit()
    ]);
}
}