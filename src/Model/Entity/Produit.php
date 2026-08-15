<?php


class Produit {
    private int $id;
    private string $nom;
    private float $prixVente;
    private float $prixAchat;
    private int $quantiteStock;
    private int $seuilAlerte;

    public function __construct(int $id, string $nom, float $prixVente, float $prixAchat, int $quantiteStock, int $seuilAlerte) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prixVente = $prixVente;
        $this->prixAchat = $prixAchat;
        $this->quantiteStock = $quantiteStock;
        $this->seuilAlerte = $seuilAlerte;
    }

    public function estEnRupture(): bool {
        return $this->quantiteStock <= $this->seuilAlerte;
    }
    
    public function getPrixAchat():float{return $this->prixAchat;}
    public function getSeuilAlerte():int{return $this-> seuilAlerte;}
    public function getId(): int { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function getPrixVente(): float { return $this->prixVente; }
    public function getQuantiteStock(): int { return $this->quantiteStock; }
}
