<?php
/**
 * Разбор и проверка наличия URL
 *
 * @return bool|array
 */

// не работает... ВСЕГДА возвращает bool
//function getServerPath() : bool | array
function getServerPath()
{
    if ( !isset($_SERVER['REQUEST_URI'])) {
        return false;
    }

    $serverUri = parse_url($_SERVER['REQUEST_URI']);
    if ( !isset($serverUri['path'])) {
        return false;
    }

    return $serverUri['path'];
}