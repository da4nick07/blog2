<?php

require_once '../boot/init.php';
use Services\Db;
/*
//$dsn = "mysql:host=127.0.0.1;port=3306;dbname=blog_db;charset=utf8";
$dsn = "mysql:host=localhost;port=3306;dbname=blog_db;charset=utf8";
$user = 'root';
$password = '@root';
$db = new PDO($dsn, $user, $password);
// throw exceptions, when SQL error is caused источник https://learntutorials.net/ru/php/topic/5828/pdo
$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
// prevent emulation of prepared statements
$db->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);

$sth = $db->prepare('SELECT * FROM `articles`;');
$res = $sth->execute();
if (false === $res) {
    echo 'Ошибка запроса';
}
$res = $sth->fetchAll(PDO::FETCH_ASSOC);

print_r( $res );
*/
$connection = Db::getInstance();
$articles = $connection->execQuery('SELECT * FROM `articles`;');
print_r( $articles );
