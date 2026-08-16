<?php
class Approvisionnement {
    private int $id;
    private int $fournisseurId;
    private int $utilisateurId;
    private string $numeroBL;
    private string $statut;
    private ?string $dateReception;

    public function __construct(
        int $id,
        int $fournisseurId,
        int $utilisateurId,
        string $numeroBL,
        string $statut = 'COMMANDE',
        ?string $dateReception = null
    ) {
        $this->id = $id;
        $this->fournisseurId = $fournisseurId;
        $this->utilisateurId = $utilisateurId;
        $this->numeroBL = $numeroBL;
        $this->statut = $statut;
        $this->dateReception = $dateReception;
    }

    public function getId(): int { return $this->id; }
    public function getFournisseurId(): int { return $this->fournisseurId; }
    public function getUtilisateurId(): int { return $this->utilisateurId; }
    public function getNumeroBL(): string { return $this->numeroBL; }
    public function getStatut(): string { return $this->statut; }
    public function getDateReception(): ?string { return $this->dateReception; }

    public function estReceptionne(): bool {
        return $this->statut === 'RECEPTIONNE';
    }

    public function marquerReceptionne(string $dateReception): void {
        if ($this->estReceptionne()) {
            throw new Exception("Le BL {$this->numeroBL} a déjà été réceptionné.");
        }
        $this->statut = 'RECEPTIONNE';
        $this->dateReception = $dateReception;
    }
}