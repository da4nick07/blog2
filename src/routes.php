<?php

return [
    '~^/$~' => [Controllers\MainController::class, 'main'],
    '~^/hello/(.*)$~' => [Controllers\MainController::class, 'sayHello'],
    '~^/bye/(.*)$~' => [Controllers\MainController::class, 'sayBye'],
    '~^/articles/(\d+)$~' => [Controllers\ArticlesController::class, 'view'],
    '~^/articles/(\d+)/edit$~' => [Controllers\ArticlesController::class, 'edit'],
    '~^/articles/add$~' => [Controllers\ArticlesController::class, 'add'],
    '~^/articles/(\d+)/delete$~' => [Controllers\ArticlesController::class, 'delete'],
    '~^/users/register$~' => [Controllers\UsersController::class, 'signUp'],
    '~^/users/login$~' => [Controllers\UsersController::class, 'login'],
    '~^/users/logOut~' => [Controllers\UsersController::class, 'logOut'],
    '~^/test~' => [Controllers\MainController::class, 'test'],
    '~^/(\d+)$~' => [Controllers\MainController::class, 'page']
];
