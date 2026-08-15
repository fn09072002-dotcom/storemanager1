<?php

class Router {
    private array $routes = [
        ['/pos',            'POSController.php',    'POSController',   'afficherCaisse'],
        ['/pos/encaisser',  'POSController.php',    'POSController',   'encaisser'],
    ];

        private array $routesPubliques = [ '/pos'];
    private SessionManager $session;

    public function __construct(SessionManager $session) {
        $this->session = $session;
    }

    public function dispatch(): void {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

        if (!in_array($uri, $this->routesPubliques, true) && $this->session->get('utilisateur') === null) {
            header('Location: /pos');
            exit;
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