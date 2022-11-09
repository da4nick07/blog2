<?php

use Exceptions\DbException;
use Exceptions\MRedisExeption;
use Exceptions\NotFoundException;
use Exceptions\UnauthorizedException;
use Exceptions\Forbidden;

require '../../lib/checkUrl.php';

if ( is_bool( $route = getServerPath())) {
    displayJson(['error' => 'Запрошен некорректный url'], 500);
    return;
}

// директория проекта
define('ROOT_DIR', dirname( __FILE__, 3) . '/');

require_once ROOT_DIR .  'boot/init_api.php';
require_once ROOT_DIR .  'boot/loader.php';

try {
    $params[ USER] = getUserByToken();
    // а через функцию не быстрее ?
    $routes = require SRC_DIR . 'routes_api.php';

    $isRouteFound = false;
    foreach ($routes as $pattern => $controllerAndAction) {
        preg_match($pattern, $route, $matches);
        if (!empty($matches)) {
            $isRouteFound = true;
            break;
        }
    }
    if (!$isRouteFound) {
        throw new NotFoundException('Неверный url');
    }

//    unset($matches[0]);
    $params[ MATCHES] = $matches;

    $controllerName = $controllerAndAction[0];
    $actionName = $controllerAndAction[1];

    $controller = new $controllerName();
    $controller->$actionName( $params );
} catch (DbException $e) {
    displayJson(['error' => $e->getMessage()], 500);
} catch (NotFoundException $e) {
    displayJson(['error' => $e->getMessage()], 404);
} catch (UnauthorizedException $e) {
    displayJson(['error' => $e->getMessage()], 401);
} catch (Forbidden $e) {
    displayJson(['error' => $e->getMessage()], 403);
} catch (MRedisExeption $e) {
    outException( $params, ROOT_DIR . 'templates/errors/500.php', $e->getMessage(), 500);
}