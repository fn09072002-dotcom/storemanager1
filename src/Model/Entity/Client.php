<?php

class Client {
    private int $id;
    private string $nom;
    private string $telephone;
    private string $email;
    private string $adresse;
    private float $limiteCredit;

    public function __construct(int $id, string $nom, string $telephone, string $email, string $adresse, float $limiteCredit) {
        $this->id = $id;
        $this->nom = $nom;
        $this->telephone = $telephone;
        $this->email = $email;
        $this->adresse = $adresse;
        $this->limiteCredit = $limiteCredit;
    }

    public function peutAcheterACredit(float $montant, float $detteActuelle): bool {
        return $montant + $detteActuelle <= $this->limiteCredit;
    }

    public function getId(): int { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function getTelephone(): string { return $this->telephone; }
    public function getEmail(): string { return $this->email; }
    public function getAdresse(): string { return $this->adresse; }
    public function getLimiteCredit(): float { return $this->limiteCredit; }
}