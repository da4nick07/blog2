<?php

/**
 *
 * Вывод данных в json
 *
 * @param $data
 * @param int $code
 * @return void
 */

function displayJson($data, int $code = 200)
{
    header('Content-type: application/json; charset=utf-8');
    http_response_code($code);
    echo json_encode($data, true);
}
