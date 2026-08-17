<?php

class Paiement {
    private int $id;
    private ?Commande $commande;
    private ?Dette $dette;
    private float $montant;
    private string $datePaiement;
    private string $modePaiement;

    public function __construct(int $id, ?Commande $commande, ?Dette $dette, float $montant, string $datePaiement, string $modePaiement) {
        if ($commande === null && $dette === null) {
            throw new InvalidArgumentException('Un paiement doit être lié à une commande ou une dette.');
        }

        $this->id = $id;
        $this->commande = $commande;
        $this->dette = $dette;
        $this->montant = $montant;
        $this->datePaiement = $datePaiement;
        $this->modePaiement = $modePaiement;
    }

    public function getId(): int { return $this->id; }
    public function getCommande(): ?Commande { return $this->commande; }
    public function getDette(): ?Dette { return $this->dette; }
    public function getMontant(): float { return $this->montant; }
    public function getDatePaiement(): string { return $this->datePaiement; }
    public function getModePaiement(): string { return $this->modePaiement; }
}