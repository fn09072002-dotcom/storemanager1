<?php

class Paiement {
    private int $id;
    private ?int $commandeId;
    private ?int $detteId;
    private float $montant;
    private string $datePaiement;
    private string $modePaiement;

    public function __construct(int $id, ?int $commandeId, ?int $detteId, float $montant, string $datePaiement, string $modePaiement) {
        $this->id = $id;
        $this->commandeId = $commandeId;
        $this->detteId = $detteId;
        $this->montant = $montant;
        $this->datePaiement = $datePaiement;
        $this->modePaiement = $modePaiement;
    }

    public function getId(): int { return $this->id; }
    public function getCommandeId(): ?int { return $this->commandeId; }
    public function getDetteId(): ?int { return $this->detteId; }
    public function getMontant(): float { return $this->montant; }
    public function getDatePaiement(): string { return $this->datePaiement; }
    public function getModePaiement(): string { return $this->modePaiement; }
}