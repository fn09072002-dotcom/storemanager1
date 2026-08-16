<?php
require_once dirname(__DIR__) . '/Model/Repository/UtilisateurRepository.php';
require_once dirname(__DIR__) . '/Core/SessionManager.php';

class AuthController {
    private SessionManager $session;
    private UtilisateurRepository $utilisateurRepository;

    public function __construct(SessionManager $session) {
        $this->session = $session;
        $this->utilisateurRepository = new UtilisateurRepository();
    }

    public function login(): void {
        $erreur = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $motDePasse = $_POST['password'] ?? '';

            $utilisateur = $this->utilisateurRepository->findByEmail($email);

            if ($utilisateur !== null && password_verify($motDePasse, $utilisateur->getMotDePasse())) {
                $this->session->set('utilisateur', [
                    'id' => $utilisateur->getId(),
                    'nom' => $utilisateur->getNom(),
                    'email' => $utilisateur->getEmail(),
                    'role' => $utilisateur->getRole()
                ]);
                header('Location: /pos');
                exit;
            }

            $erreur = "Email ou mot de passe incorrect.";
        }

        require_once dirname(__DIR__, 2) . '/views/auth/login.php';
    }

    public function logout(): void {
        $this->session->destroy();
        header('Location: /login');
        exit;
    }
}