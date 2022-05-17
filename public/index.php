<?php

require '../lib/checkUri.php';
if ( is_bool( $serverUri = getServerUri())) {
    echo 'Запрошен некорректный uri';
    return;
}

$route  = $serverUri['path'];
if ( $serverUri['path'] ==='/' ) {
    $uri[] = '';
} else {
    $uri = [];
    $tok = strtok($serverUri['path'], "/");
    while ($tok !== false) {
        $uri[] = $tok;
        $tok = strtok("/");
    }
}

// начальные установки + загрузчик классов
require_once '../boot/init_html.php';
use Exceptions\DbException;
use Exceptions\NotFoundException;
use Exceptions\UnauthorizedException;
use Exceptions\Forbidden;

// а через функцию не быстрее ?
$routes = require SRC_DIR . 'routes.php';
/*
if (isset($routes[$uri[0]])) {
    $controller = new $routes[ $uri[0] ][0];
    $action = $routes[ $uri[0] ][1];
    $controller->$action( $uri );
} else {
    echo 'Запрошен неизвестный url = "' . $serverUri['path'] . '" <br>';
}
*/

$params[ URI] = $uri;
$params[ USER] = getUserByToken();

try {
    $isRouteFound = false;
    foreach ($routes as $pattern => $controllerAndAction) {
        preg_match($pattern, $route, $matches);
        if (!empty($matches)) {
            $isRouteFound = true;
            break;
        }
    }

    if (!$isRouteFound) {
        throw new NotFoundException();
    }

    $controllerName = $controllerAndAction[0];
    $actionName = $controllerAndAction[1];

    $controller = new $controllerName();
//  unset($matches[0]);
//  $controller->$actionName(...$matches);
    $controller->$actionName( $params );
} catch (DbException $e) {
    outException( $params, ROOT_DIR . 'templates/errors/500.php', $e->getMessage(), 500);
} catch (NotFoundException $e) {
    outException( $params, ROOT_DIR . 'templates/errors/404.php', $e->getMessage(), 404);
} catch (UnauthorizedException $e) {
    outException( $params, ROOT_DIR . 'templates/errors/401.php', $e->getMessage(), 401);
} catch (Forbidden $e) {
    outException( $params, ROOT_DIR . 'templates/errors/403.php', $e->getMessage(), 403);
}