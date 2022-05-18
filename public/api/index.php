<?php

require '../../lib/checkUrl.php';
require_once '../../boot/init_api.php';

if ( is_bool( $route = getServerPath())) {
    displayJson(['error' => 'Запрошен некорректный url'], 500);
    return;
}

use Exceptions\DbException;
use Exceptions\NotFoundException;
use Exceptions\UnauthorizedException;
use Exceptions\Forbidden;

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
}