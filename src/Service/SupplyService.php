<?php
require_once __DIR__ . '/../Model/Repository/ApprovisionnementRepository.php';
require_once __DIR__ . '/../Model/Entity/LigneApprovisionnement.php';
require_once __DIR__ . '/../Core/Database.php';

class SupplyService {
    private PDO $pdo;
    private ApprovisionnementRepository $approRepo;

    public function __construct() {
        $this->pdo = Database::getInstance();
        $this->approRepo = new ApprovisionnementRepository();
    }

  
    public function creerBonLivraison(int $fournisseurId, int $utilisateurId, string $numeroBL): int {
        return $this->approRepo->save($fournisseurId, $utilisateurId, $numeroBL);
    }

 
    public function receptionner(int $approvisionnementId, array $produits): void {
        $appro = $this->approRepo->findById($approvisionnementId);

        if ($appro === null) {
            throw new Exception("Bon de livraison introuvable (id={$approvisionnementId}).");
        }

        if ($appro->estReceptionne()) {
            throw new Exception("Le BL {$appro->getNumeroBL()} a déjà été réceptionné.");
        }

        if (empty($produits)) {
            throw new Exception("Impossible de réceptionner un BL sans aucune ligne de produit.");
        }

        $this->pdo->beginTransaction();

        try {
            foreach ($produits as $item) {
             $ligne = new LigneApprovisionnement(
                    0,                                      
                    $approvisionnementId,
                    (int) $item['produit_id'],
                    (int) $item['quantite'],
                    (float) $item['prix_achat_unitaire']
                );
                $this->approRepo->saveLigne($ligne);

                $stmt = $this->pdo->prepare(
                    "UPDATE produits SET quantite_stock = quantite_stock + :quantite WHERE id = :produit_id"
                );
                $stmt->execute([
                    ':quantite' => $ligne->getQuantiteRecue(),
                    ':produit_id' => $ligne->getProduitId(),
                ]);
            }

            $this->approRepo->marquerReceptionne($approvisionnementId, date('Y-m-d H:i:s'));

            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function getBonsEnCours(): array {
        return $this->approRepo->findByStatut('COMMANDE');
    }

    public function getBonsReceptionnes(): array {
        return $this->approRepo->findByStatut('RECEPTIONNE');
    }
}