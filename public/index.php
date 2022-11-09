<?php

use Exceptions\DbException;
use Exceptions\MRedisExeption;
use Exceptions\NotFoundException;
use Exceptions\UnauthorizedException;
use Exceptions\Forbidden;

$startTime = microtime(true);

require '../lib/checkUrl.php';

$route = getServerPath();
if ( is_bool( $route )) {
    echo 'Запрошен некорректный url';
    return;
}

// директория проекта
define('ROOT_DIR', dirname( __FILE__, 2) . '/');

// начальные установки + загрузчик классов
require_once ROOT_DIR .  'boot/init_html.php';
require_once ROOT_DIR .  'boot/loader.php';

try {
    $params[ USER] = getUserByToken();

    // а через функцию не быстрее ?
    $routes = require SRC_DIR . 'routes.php';

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
//    unset($matches[0]);
    $params[ MATCHES] = $matches;

    $controllerName = $controllerAndAction[0];
    $actionName = $controllerAndAction[1];

    $controller = new $controllerName();
    $controller->$actionName( $params );
} catch (DbException $e) {
    outException( $params, ROOT_DIR . 'templates/errors/500.php', $e->getMessage(), 500);
} catch (NotFoundException $e) {
    outException( $params, ROOT_DIR . 'templates/errors/404.php', $e->getMessage(), 404);
} catch (UnauthorizedException $e) {
    outException( $params, ROOT_DIR . 'templates/errors/401.php', $e->getMessage(), 401);
} catch (Forbidden $e) {
    outException( $params, ROOT_DIR . 'templates/errors/403.php', $e->getMessage(), 403);
} catch (MRedisExeption $e) {
    outException( $params, ROOT_DIR . 'templates/errors/500.php', $e->getMessage(), 500);
}

$endTime = microtime(true);
printf('<div style="text-align: center; padding: 5px">Время генерации страницы: %f</div>', $endTime - $startTime );

