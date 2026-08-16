<?php
class LigneApprovisionnement {
    private ?int $id;
    private int $approvisionnementId;
    private int $produitId;
    private int $quantiteRecue;
    private float $prixAchatUnitaire;

    public function __construct(
        int $approvisionnementId,
        int $produitId,
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

        $this->approvisionnementId = $approvisionnementId;
        $this->produitId = $produitId;
        $this->quantiteRecue = $quantiteRecue;
        $this->prixAchatUnitaire = $prixAchatUnitaire;
        $this->id = $id;
    }

    public function getId(): ?int { return $this->id; }
    public function getApprovisionnementId(): int { return $this->approvisionnementId; }
    public function getProduitId(): int { return $this->produitId; }
    public function getQuantiteRecue(): int { return $this->quantiteRecue; }
    public function getPrixAchatUnitaire(): float { return $this->prixAchatUnitaire; }

    public function calculerSousTotal(): float {
        return $this->quantiteRecue * $this->prixAchatUnitaire;
    }
}