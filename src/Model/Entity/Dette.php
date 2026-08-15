<?php

class Dette {
    private int $id;
    private int $commandeId;
    private float $montantInitial;
    private float $montantRestant;
    private string $statut;

    public function __construct(int $id, int $commandeId, float $montantInitial, float $montantRestant, string $statut) {
        $this->id = $id;
        $this->commandeId = $commandeId;
        $this->montantInitial = $montantInitial;
        $this->montantRestant = $montantRestant;
        $this->statut = $statut;
    }

    public function enregistrerRemboursement(float $montant): void {
        $this->montantRestant = $this->montantRestant - $montant;
        if ( $this->montantRestant===0){
             $this->statut='SOLDEE';
        }        

    }
    public function estSoldee(): bool {
    return $this->statut === 'SOLDEE';
    }


    public function getId(): int { return $this->id; }
    public function getCommandeId(): int { return $this->commandeId; }
    public function getMontantInitial(): float { return $this->montantInitial; }
    public function getMontantRestant(): float { return $this->montantRestant; }
    public function getStatut(): string { return $this->statut; }
}