<?php

class Commande {
    private int $id;
    private Client $client;
    private Utilisateur $utilisateur;
    private string $dateCommande;
    private string $statut;
    private float $montantTotal;

    public function __construct(int $id, Client $client, Utilisateur $utilisateur, string $dateCommande, string $statut, float $montantTotal) {
        $this->id = $id;
        $this->client = $client;
        $this->utilisateur = $utilisateur;
        $this->dateCommande = $dateCommande;
        $this->statut = $statut;
        $this->montantTotal = $montantTotal;
    }
    public function estValidee(): bool {
        return $this->statut === 'VALIDEE';
    }

    public function getId(): int { return $this->id; }
    public function getClient(): Client { return $this->client; }
    public function getUtilisateur(): Utilisateur { return $this->utilisateur; }
    public function getDateCommande(): string { return $this->dateCommande; }
    public function getStatut(): string { return $this->statut; }
    public function getMontantTotal(): float { return $this->montantTotal; }
}