<?php
require_once dirname(__DIR__) . '/src/Core/SessionManager.php';
require_once dirname(__DIR__) . '/src/Core/Database.php';
require_once dirname(__DIR__) . '/src/Core/Router.php';

SessionManager::init();
$router = new Router();
$router->dispatch();