<?php

class LigneCommande {
    private int $id;
    private Commande $commande;
    private Produit $produit;
    private int $quantite;
    private float $prixUnitaire;

    public function __construct(int $id, Commande $commande, Produit $produit, int $quantite, float $prixUnitaire) {
        $this->id = $id;
        $this->commande = $commande;
        $this->produit = $produit;
        $this->quantite = $quantite;
        $this->prixUnitaire = $prixUnitaire;
    }

    public function calculerSousTotal(): float {
        return $this->quantite * $this->prixUnitaire;
    }

    public function getId(): int { return $this->id; }
    public function getCommande(): Commande { return $this->commande; }
    public function getProduit(): Produit { return $this->produit; }
    public function getQuantite(): int { return $this->quantite; }
    public function getPrixUnitaire(): float { return $this->prixUnitaire; }
}