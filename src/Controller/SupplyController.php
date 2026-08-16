<?php
require_once __DIR__ . '/../Service/SupplyService.php';
require_once __DIR__ . '/../Model/Repository/ApprovisionnementRepository.php';
require_once __DIR__ . '/../Model/Repository/FournisseurRepository.php';   // ← ajoute cette ligne

class SupplyController {
    private SupplyService $supplyService;
    private SessionManager $session;

    public function __construct(SessionManager $session) {
        $this->session = $session;
        $this->supplyService = new SupplyService();
    }

    public function afficherApprovisionnements(): void {
        $bonsEnCours = $this->supplyService->getBonsEnCours();
        $bonsReceptionnes = $this->supplyService->getBonsReceptionnes();
        $erreur = $this->session->get('erreur_supply');
        $this->session->unset('erreur_supply');

        $fournisseurRepo = new FournisseurRepository();
        $fournisseurs = $fournisseurRepo->getAll();
        $fournisseursParId = [];
        foreach ($fournisseurs as $f) {
            $fournisseursParId[$f->getId()] = $f;
        }

        require __DIR__ . '/../../views/supplies/index.php';
    }
    
    public function creerBonLivraison(): void {
        $fournisseurId = (int) ($_POST['fournisseur_id'] ?? 0);
        $utilisateurId = (int) ($this->session->get('utilisateur')['id'] ?? 0);
        $numeroBL = trim($_POST['numero_bl'] ?? '');

        if ($fournisseurId <= 0 || $numeroBL === '') {
            $this->session->set('erreur_supply', "Fournisseur et numéro de BL obligatoires.");
            header('Location: /supplies');
            exit;
        }

        $this->supplyService->creerBonLivraison($fournisseurId, $utilisateurId, $numeroBL);

        header('Location: /supplies');
        exit;
    }

    public function afficherReception(): void {
        $id = (int) ($_GET['id'] ?? 0);
        $approRepo = new ApprovisionnementRepository();
        $appro = $approRepo->findById($id);

        if ($appro === null || $appro->estReceptionne()) {
            header('Location: /supplies');
            exit;
        }

        require __DIR__ . '/../../views/supplies/receptionner.php';
    }

    public function receptionner(): void {
        $id = (int) ($_POST['approvisionnement_id'] ?? 0);
        $lignesJson = $_POST['lignes_json'] ?? '[]';
        $produits = json_decode($lignesJson, true) ?? [];

        try {
            $this->supplyService->receptionner($id, $produits);
        } catch (Exception $e) {
            $this->session->set('erreur_supply', $e->getMessage());
        }

        header('Location: /supplies');
        exit;
    }

}
