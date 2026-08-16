<?php
require_once dirname(__DIR__) . '/Core/AuthManager.php';
class Router {
    private array $routes = [
        ['/login',  'AuthController.php', 'AuthController', 'login'],
        ['/logout', 'AuthController.php', 'AuthController', 'logout'],
        ['/dettes',            'DettesController.php', 'DettesController', 'afficherDettes'],
        ['/dettes/rembourser', 'DettesController.php', 'DettesController', 'enregistrerPaiement'],
        ['/pos',            'POSController.php',    'POSController',   'afficherCaisse'],
        ['/pos/encaisser',  'POSController.php',    'POSController',   'encaisser'],
        ['/dettes',  'DettesController.php',    'DettesController',   'afficherDettes'],
        ['/supplies',                    'SupplyController.php', 'SupplyController', 'afficherApprovisionnements'],
        ['/supplies/creer',              'SupplyController.php', 'SupplyController', 'creerBonLivraison'],
        ['/supplies/receptionner',       'SupplyController.php', 'SupplyController', 'afficherReception'],
        ['/supplies/receptionner/valider','SupplyController.php', 'SupplyController', 'receptionner'],
    ];

    private array $routesPubliques = ['/login']; 
    private SessionManager $session;

    public function __construct(SessionManager $session) {
        $this->session = $session;
    }

    public function dispatch(): void {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

           if (!in_array($uri, $this->routesPubliques, true) && $this->session->get('utilisateur') === null) {
        header('Location: /login');
        exit;
    }

    $utilisateur = $this->session->get('utilisateur');
    if ($utilisateur !== null && !in_array($uri, $this->routesPubliques, true)) {
        if (!AuthManager::peutAcceder($utilisateur['role'], $uri)) {
            http_response_code(403);
            echo "<h1>403 - Accès refusé pour votre profil</h1>";
            exit;
        }
    }

        $routeFound = false;

        foreach ($this->routes as $route) {
            [$path, $fichier, $classe, $methode] = $route;

            if ($path === $uri) {
                $controllerFile = dirname(__DIR__) . '/Controller/' . $fichier;

                if (file_exists($controllerFile)) {
                    require_once $controllerFile;

                    if (method_exists($classe, $methode)) {
                        $controller = new $classe($this->session);
                        $controller->$methode();
                        $routeFound = true;
                        break;
                    }
                }
            }
        }

        if (!$routeFound) {
            http_response_code(404);
            echo "<h1>404 - Page non trouvée</h1>";
        }
    }
}