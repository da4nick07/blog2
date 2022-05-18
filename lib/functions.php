<?php

use Models\User;

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

