<?php

require '../../lib/checkUri.php';
require_once '../../boot/init_api.php';

if ( is_bool( $serverUri = getServerUri())) {
    displayJson(['error' => 'Запрошен некорректный uri'], 500);
    return;
}

$route  = $serverUri['path'];

use Exceptions\DbException;
use Exceptions\NotFoundException;
use Exceptions\UnauthorizedException;
use Exceptions\Forbidden;


try {
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

    unset($matches[0]);

    $controllerName = $controllerAndAction[0];
    $actionName = $controllerAndAction[1];

    $controller = new $controllerName();
    $controller->$actionName(...$matches);
} catch (Exceptions\DbException $e) {
    displayJson(['error' => $e->getMessage()], 500);
} catch (Exceptions\NotFoundException $e) {
//    echo $e->getMessage();
    displayJson(['error' => $e->getMessage()], 404);
} catch (Exceptions\UnauthorizedException $e) {
    displayJson(['error' => $e->getMessage()], 401);
}