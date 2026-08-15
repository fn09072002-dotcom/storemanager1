<?php

class Commande {
    private int $id;
    private int $clientId;
    private int $utilisateurId;
    private string $dateCommande;
    private string $statut;
    private float $montantTotal;

    public function __construct(int $id, int $clientId, int $utilisateurId, string $dateCommande, string $statut, float $montantTotal) {
        $this->id = $id;
        $this->clientId = $clientId;
        $this->utilisateurId = $utilisateurId;
        $this->dateCommande = $dateCommande;
        $this->statut = $statut;
        $this->montantTotal = $montantTotal;
    }
    public function estValidee(): bool {
    return $this->statut === 'VALIDEE';
    }

    public function getId(): int { return $this->id; }
    public function getClientId(): int { return $this->clientId; }
    public function getUtilisateurId(): int { return $this->utilisateurId; }
    public function getDateCommande(): string { return $this->dateCommande; }
    public function getStatut(): string { return $this->statut; }
    public function getMontantTotal(): float { return $this->montantTotal; }
}