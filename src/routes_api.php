<?php

return [
    '~^/api/articles/(\d+)$~' => [Controllers\Api\ArticlesApiController::class, 'view'],
    '~^/api/articles/add$~' => [Controllers\Api\ArticlesApiController::class, 'add'],
];