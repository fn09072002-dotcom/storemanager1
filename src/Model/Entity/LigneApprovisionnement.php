<?php
class LigneApprovisionnement {
    private ?int $id;
    private Approvisionnement $approvisionnement;
    private Produit $produit;
    private int $quantiteRecue;
    private float $prixAchatUnitaire;

    public function __construct(
        Approvisionnement $approvisionnement,
        Produit $produit,
        int $quantiteRecue,
        float $prixAchatUnitaire,
        ?int $id = null
    ) {
        if ($quantiteRecue <= 0) {
            throw new InvalidArgumentException("La quantité reçue doit être positive.");
        }
        if ($prixAchatUnitaire < 0) {
            throw new InvalidArgumentException("Le prix d'achat ne peut pas être négatif.");
        }

        $this->approvisionnement = $approvisionnement;
        $this->produit = $produit;
        $this->quantiteRecue = $quantiteRecue;
        $this->prixAchatUnitaire = $prixAchatUnitaire;
        $this->id = $id;
    }

    public function getId(): ?int { return $this->id; }
    public function getApprovisionnement(): Approvisionnement { return $this->approvisionnement; }
    public function getProduit(): Produit { return $this->produit; }
    public function getQuantiteRecue(): int { return $this->quantiteRecue; }
    public function getPrixAchatUnitaire(): float { return $this->prixAchatUnitaire; }

    public function calculerSousTotal(): float {
        return $this->quantiteRecue * $this->prixAchatUnitaire;
    }
}