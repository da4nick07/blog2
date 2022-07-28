<?php

require_once '../boot/init.php';

use Services\Db;
use Exceptions\DbException;


$testTitle = 'Проверка подключения к БД ';
$fileName = $argv[0];

// вывод тестовых сообщений нужен?
$options = getopt('p');
$outTestMsg = !isset($options['p']);
echo $testTitle . '(' . $fileName . ')...';
if ($outTestMsg) {
    echo PHP_EOL;
}


// вдруг простых тестов несколько...
//if ($outTestMsg) {
//    echo 'Ещё один тест...';
//}
try {
    $connection = Db::getInstance();
} catch (DbException $e) {
//    echo $testTitle . '(' . $fileName . ')' . PHP_EOL;
    echo '    Ошибка подключения к БД';
    exit(1);
}
// OK  для простого теста
//if ($outTestMsg) {
//    echo 'ОК' . PHP_EOL;
//}


// общий OK
echo 'ОК' . PHP_EOL;
