<?php

class Approvisionnement {
    private int $id;
    private int $fournisseurId;
    private int $utilisateurId;
    private string $dateReception;
    private string $numeroBL;

    public function __construct(int $id, int $fournisseurId, int $utilisateurId, string $dateReception, string $numeroBL) {
        $this->id = $id;
        $this->fournisseurId = $fournisseurId;
        $this->utilisateurId = $utilisateurId;
        $this->dateReception = $dateReception;
        $this->numeroBL = $numeroBL;
    }

    public function getId(): int { return $this->id; }
    public function getFournisseurId(): int { return $this->fournisseurId; }
    public function getUtilisateurId(): int { return $this->utilisateurId; }
    public function getDateReception(): string { return $this->dateReception; }
    public function getNumeroBL(): string { return $this->numeroBL; }
}