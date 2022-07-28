<?php

require_once '../boot/init.php';

$testTitle = 'Самый главный тест.';
$fileName = $argv[0];

// вывод тестовых сообщений нужен?
$options = getopt('p');
$outTestMsg = !isset($options['p']);
echo $testTitle . '(' . $fileName . ')...';
if ($outTestMsg) {
    echo PHP_EOL;
}

if ($outTestMsg) {
    echo '    Работа с БД...';
}
// доп. строка в вывод ошибки - сюда
//$out[] =  'Ещё один тест...';
exec("php big.php", $out, $code);
if ( $code <> 0 ) {
    print_r($out) ;
    exit(1);
}
if ($outTestMsg) {
    echo '    ОК' . PHP_EOL;
}


// общий OK
echo 'ОК' . PHP_EOL;

