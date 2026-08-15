<?php

class Utilisateur {
    private int $id;
    private string $nom;
    private string $email;
    private string $motDePasse;
    private string $role;

    public function __construct(int $id, string $nom, string $email, string $motDePasse, string $role) {
        $this->id = $id;
        $this->nom = $nom;
        $this->email = $email;
        $this->motDePasse = $motDePasse;
        $this->role = $role;
    }

    public function aLeRole(string $role): bool {
        return $this->role === $role;  
    }

    public function getId(): int { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function getEmail(): string { return $this->email; }
    public function getMotDePasse(): string { return $this->motDePasse; }
    public function getRole(): string { return $this->role; }
}