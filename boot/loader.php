<?php

// определяем загрузчик классов
function blogClassLoader(string $className)
{
    $obj = str_replace('\\', DIR_SEPARATOR, $className) . '.php';
    if ( file_exists( ROOT_DIR . $obj)) {
        require_once ROOT_DIR . $obj;
    } else {
        require_once SRC_DIR . $obj;
    }
}

spl_autoload_register('blogClassLoader');
