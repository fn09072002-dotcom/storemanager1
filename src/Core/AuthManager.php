<?php

class AuthManager {

  private static array $permissions = [
    'admin'      => ['/pos', '/pos/encaisser', '/dettes', '/dettes/rembourser', '/supplies', '/supplies/creer', '/supplies/receptionner', '/supplies/receptionner/valider'],
    'vente'      => ['/pos', '/pos/encaisser', '/dettes', '/dettes/rembourser'],
    'stock'      => ['/supplies', '/supplies/creer', '/supplies/receptionner', '/supplies/receptionner/valider'],
    'inventaire' => [],
];

    public static function peutAcceder(string $role, string $route): bool {
        if (!isset(self::$permissions[$role])) {
            return false;
        }

        return in_array($route, self::$permissions[$role], true);
    }
}