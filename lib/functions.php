<?php

use Models\User;

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

/**
 * Возвращает объект User по $_COOKIE['token'] или null
 *
 * @return User|null
 */
function getUserByToken(): ?User
{
    $token = $_COOKIE['token'] ?? '';

    if (empty($token)) {
        return null;
    }

    [$userId, $authToken] = explode(':', $token, 2);

    /** @var  $user User */
    $user = User::selectOneByColumn( 'id', (int) $userId);

    if ($user === null) {
        return null;
    }

    if ($user->getAuthToken() !== $authToken) {
        return null;
    }

    return $user;
}

/**
 * Формирует и отправляет новый токен (cookie['token']) для указанного пользователя
 *
 * @param User $user
 * @return void
 */
function createToken(User $user): void
{
    $token = $user->getId() . ':' . $user->getAuthToken();
    setcookie('token', $token, 0, '/', '', false, true);
}

