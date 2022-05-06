<?php
/**
 * Разбор и проверка наличия URL
 *
 * @return bool|array
 */
function getServerUri() : bool|array
{
    if ( !isset($_SERVER['REQUEST_URI'])) {
        return false;
    }

    $serverUri = parse_url($_SERVER['REQUEST_URI']);
    if ( !isset($serverUri['path'])) {
        return false;
    }

    return $serverUri;
}