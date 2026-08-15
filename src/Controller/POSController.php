<?php
require_once dirname(__DIR__) . '/Service/VenteService.php';
require_once dirname(__DIR__) . '/Model/Repository/ProduitRepository.php';
require_once dirname(__DIR__) . '/Model/Repository/ClientRepository.php';
require_once dirname(__DIR__) . '/Model/Repository/CommandeRepository.php';
require_once dirname(__DIR__) . '/Core/SessionManager.php';

class POSController {
    private SessionManager $session;
    private VenteService $venteService;
    private ProduitRepository $produitRepository;
    private ClientRepository $clientRepository;
    private CommandeRepository $commandeRepository;

    public function __construct(SessionManager $session) {
        $this->session = $session;
        $this->venteService = new VenteService();
        $this->produitRepository = new ProduitRepository();
        $this->clientRepository = new ClientRepository();
        $this->commandeRepository = new CommandeRepository();
    }

    public function afficherCaisse(): void {
        $produits = $this->produitRepository->getAll();
        $clients = $this->clientRepository->getAll();
        $commandes = $this->commandeRepository->getAll();
        //var_dump(count($produits), count($clients));  
         //exit; 

        $clientsParId = [];
        foreach ($clients as $c) {
            $clientsParId[$c->getId()] = $c;
        }

        require_once dirname(__DIR__, 2) . '/views/pos/index.php';
    }

    public function encaisser(): void {
        $clientId = (int) $_POST['client_id'];
        $utilisateurId = $this->session->get('utilisateur')['id'] ?? 1;  
        $montantPaye = (float) $_POST['montant_verse'];

        $panier = [];
        foreach ($_POST['product_ids'] as $index => $produitId) {
            $quantite = (int) $_POST['product_qtys'][$index];

            if ($quantite > 0) {
                $panier[] = [
                    'produit_id' => (int) $produitId,
                    'quantite' => $quantite
                ];
            }
        }

        try {
            $commandeId = $this->venteService->validerVente($clientId, $utilisateurId, $panier, $montantPaye);
            $this->session->set('flash_message', "Vente enregistrée (commande #{$commandeId})");
        } catch (Exception $e) {
            $this->session->set('flash_message', "Erreur : " . $e->getMessage());
        }

        header('Location: /pos');
        exit;
    }
}