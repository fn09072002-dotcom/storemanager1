<?php
require_once dirname(__DIR__) . '/Model/Repository/DetteRepository.php';
require_once dirname(__DIR__) . '/Model/Repository/PaiementRepository.php';
require_once dirname(__DIR__) . '/Model/Repository/LigneCommandeRepository.php';
require_once dirname(__DIR__) . '/Model/Entity/LigneCommande.php';
require_once dirname(__DIR__) . '/Model/Entity/Paiement.php';
require_once dirname(__DIR__) . '/Core/Database.php';

class DetteService {
    private PDO $pdo;
    private DetteRepository $detteRepository;
    private PaiementRepository $paiementRepository;

    public function __construct() {
        $this->pdo = Database::getInstance();
        $this->detteRepository = new DetteRepository();
        $this->paiementRepository = new PaiementRepository();
    }

public function enregistrerPaiement(int $detteId, float $montant, string $modePaiement): void {
    $this->pdo->beginTransaction();

    try {
        $dette = $this->detteRepository->findById($detteId);
        if ($dette === null) {
            throw new Exception("Dette introuvable : ID {$detteId}");
        }
        $dette->enregistrerRemboursement($montant);
        $this->detteRepository->update($dette);

        $paiement = new Paiement(
            0,                    
            null,         
            $detteId,             
            $montant,
            date('Y-m-d H:i:s'),
            $modePaiement
        );

        $this->paiementRepository->save($paiement);

        $this->pdo->commit();
    } catch (Exception $e) {
        $this->pdo->rollBack();
        throw $e;
    }
}
}