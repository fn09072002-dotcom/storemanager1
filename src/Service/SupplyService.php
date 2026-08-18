

<?php
require_once __DIR__ . '/../Model/Repository/ApprovisionnementRepository.php';
require_once __DIR__ . '/../Model/Repository/FournisseurRepository.php';
require_once __DIR__ . '/../Model/Repository/UtilisateurRepository.php';
require_once __DIR__ . '/../Model/Repository/ProduitRepository.php';
require_once __DIR__ . '/../Model/Entity/LigneApprovisionnement.php';
require_once __DIR__ . '/../Core/Database.php';

class SupplyService {

    private static ?ApprovisionnementRepository $approRepo = null;
    private static ?FournisseurRepository $fournisseurRepo = null;
    private static ?UtilisateurRepository $utilisateurRepo = null;
    private static ?ProduitRepository $produitRepo = null;
    private static ?PDO $pdo = null;

    private function __construct() {}

    private static function initDependances(): void {
        if (self::$approRepo === null) {
            self::$approRepo = new ApprovisionnementRepository();
            self::$fournisseurRepo = new FournisseurRepository();
            self::$utilisateurRepo = new UtilisateurRepository();
            self::$produitRepo = new ProduitRepository();
            self::$pdo = Database::getInstance();
        }
    }

    public static function creerBonLivraison(int $fournisseurId, int $utilisateurId, string $numeroBL): int {
        self::initDependances();

        $fournisseur = self::$fournisseurRepo->findById($fournisseurId);
        $utilisateur = self::$utilisateurRepo->findById($utilisateurId);

        if ($fournisseur === null) {
            throw new Exception("Fournisseur introuvable (id={$fournisseurId}).");
        }
        if ($utilisateur === null) {
            throw new Exception("Utilisateur introuvable (id={$utilisateurId}).");
        }

        return self::$approRepo->save($fournisseur, $utilisateur, $numeroBL);
    }

    public static function receptionner(int $approvisionnementId, array $produits): void {
        self::initDependances();

        $appro = self::$approRepo->findById($approvisionnementId);

        if ($appro === null) {
            throw new Exception("Bon de livraison introuvable (id={$approvisionnementId}).");
        }

        if ($appro->estReceptionne()) {
            throw new Exception("Le BL {$appro->getNumeroBL()} a déjà été réceptionné.");
        }

        if (empty($produits)) {
            throw new Exception("Impossible de réceptionner un BL sans aucune ligne de produit.");
        }

        self::$pdo->beginTransaction();

        try {
            foreach ($produits as $item) {
                $produit = self::$produitRepo->findById((int) $item['produit_id']);

                if ($produit === null) {
                    throw new Exception("Produit introuvable (id={$item['produit_id']}).");
                }

                $ligne = new LigneApprovisionnement(
                    $appro,
                    $produit,
                    (int) $item['quantite'],
                    (float) $item['prix_achat_unitaire']
                );
                self::$approRepo->saveLigne($ligne);

                $stmt = self::$pdo->prepare(
                    "UPDATE produits SET quantite_stock = quantite_stock + :quantite WHERE id = :produit_id"
                );
                $stmt->execute([
                    ':quantite' => $ligne->getQuantiteRecue(),
                    ':produit_id' => $produit->getId(),
                ]);
            }

            self::$approRepo->marquerReceptionne($approvisionnementId, date('Y-m-d H:i:s'));

            self::$pdo->commit();
        } catch (Exception $e) {
            self::$pdo->rollBack();
            throw $e;
        }
    }

    public static function getBonsEnCours(): array {
        self::initDependances();
        return self::$approRepo->findByStatut('COMMANDE');
    }

    public static function getBonsReceptionnes(): array {
        self::initDependances();
        return self::$approRepo->findByStatut('RECEPTIONNE');
    }
}