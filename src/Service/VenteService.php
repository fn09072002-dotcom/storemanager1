<?php
require_once dirname(__DIR__) . '/Model/Repository/ProduitRepository.php';
require_once dirname(__DIR__) . '/Model/Repository/CommandeRepository.php';
require_once dirname(__DIR__) . '/Model/Repository/DetteRepository.php';
require_once dirname(__DIR__) . '/Model/Entity/Commande.php';
require_once dirname(__DIR__) . '/Model/Entity/Dette.php';
require_once dirname(__DIR__) . '/Core/Database.php';

class VenteService {
    private PDO $pdo;
    private ProduitRepository $produitRepository;
    private CommandeRepository $commandeRepository;
    private DetteRepository $detteRepository;

    public function __construct() {
        $this->pdo = Database::getInstance();
        $this->produitRepository = new ProduitRepository();
        $this->commandeRepository = new CommandeRepository();
        $this->detteRepository = new DetteRepository();
    }

    public function validerVente(int $clientId, int $utilisateurId, array $panier, float $montantPaye): int {
        $this->pdo->beginTransaction();

        try {
            $montantTotal = 0;
            foreach ($panier as $ligne) {
            $produit = $this->produitRepository->findById($ligne['produit_id']);

            if ($produit === null) {
                throw new Exception("Produit introuvable : ID " . $ligne['produit_id']);
            }

            if ($produit->getQuantiteStock() < $ligne['quantite']) {
                throw new Exception("Stock insuffisant pour : " . $produit->getNom());
            }

            $montantTotal += $ligne['quantite'] * $produit->getPrixVente();
        }   

        $commande = new Commande(
            0,                    
            $clientId,
            $utilisateurId,
            date('Y-m-d H:i:s'),  
            'VALIDEE',
            $montantTotal
        );

        $commandeId = $this->commandeRepository->save($commande);

        foreach ($panier as $ligne) {
    $produit = $this->produitRepository->findById($ligne['produit_id']);
    $nouvelleQuantite = $produit->getQuantiteStock()-$ligne['quantite'];
    $this->produitRepository->updateStock($ligne['produit_id'], $nouvelleQuantite);
}

        if ($montantPaye < $montantTotal) {
            $resteAPayer = $montantTotal - $montantPaye;

            $dette = new Dette(
                0,                  
                $commandeId,
                $montantTotal,     
                 $resteAPayer,        
                'EN_COURS'
            );

            $this->detteRepository->save($dette);
        }

        $this->pdo->commit();
        return $commandeId;

            

        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}