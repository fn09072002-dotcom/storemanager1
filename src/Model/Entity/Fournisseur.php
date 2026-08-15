<?php

class Fournisseur {
    private int $id;
    private string $nom;
    private string $telephone;
    private string $adresse;

    public function __construct(int $id, string $nom, string $telephone, string $adresse) {
        $this->id = $id;
        $this->nom = $nom;
        $this->telephone = $telephone;
        $this->adresse = $adresse;
    }

    public function getId(): int { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function getTelephone(): string { return $this->telephone; }
    public function getAdresse(): string { return $this->adresse; }
}