<?php

class Dette {
    private int $id;
    private Commande $commande;
    private float $montantInitial;
    private float $montantRestant;
    private string $statut;

    public function __construct(int $id, Commande $commande, float $montantInitial, float $montantRestant, string $statut) {
        $this->id = $id;
        $this->commande = $commande;
        $this->montantInitial = $montantInitial;
        $this->montantRestant = $montantRestant;
        $this->statut = $statut;
    }

    public function enregistrerRemboursement(float $montant): void {
        $this->montantRestant = $this->montantRestant - $montant;
        if ($this->montantRestant <= 0) {
            $this->montantRestant = 0.0;
            $this->statut = 'SOLDEE';
        }
    }
    public function estSoldee(): bool {
        return $this->statut === 'SOLDEE';
    }


    public function getId(): int { return $this->id; }
    public function getCommande(): Commande { return $this->commande; }
    public function getMontantInitial(): float { return $this->montantInitial; }
    public function getMontantRestant(): float { return $this->montantRestant; }
    public function getStatut(): string { return $this->statut; }
}