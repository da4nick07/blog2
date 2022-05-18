<?php

/**
 * Включает и выполняет указанный HTML-шаблон с PHP переменными
 *
 * @param string $templateName - полный путь до HTML-шаблона
 * @param array $vars - массив переменных
 * @return string
 */
function renderVars(string $templateName, array $vars = []) : string
{
    if ( count($vars) ) {
        extract($vars, EXTR_OVERWRITE);
    }

    ob_start();
    include $templateName;

    return ob_get_clean();
}

/**
 * Формирует и выводит страницу с сообщением об ошибке
 *
 * @param array $params - массив общих параметров (см стр. 40-41 index.php)
 * @param string $template - полный путь до HTML-шаблона для исключения
 * @param string $errMsg - сообщение об ошибке
 * @param int $code - код ошибки для браузера
 * @return void
 */
function outException( array $params, string $template, string $errMsg, int $code = 0): void
{
    if ( $code ) {
        http_response_code($code);
    }
    $vars['_MAIN_ARTICLES_'] = renderVars( $template, ['_ERROR_' => $errMsg]);
    $vars['_USER_'] = $params[ USER ];
    echo renderVars( ROOT_DIR . 'templates/main/main.php', $vars);
}
