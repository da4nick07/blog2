<?php
namespace Services;

use PDO;
use Exceptions\DbException;

class Db
{
    private PDO $pdo;
    private static $instance;

    private function __construct()
    {
        $dbOptions = (require SRC_DIR . '/settings.php')['db'];

        try {
            //$dsn = "mysql:host=127.0.0.1;port=3306;dbname=blog_db;charset=utf8";
            $this->pdo = new PDO(
                $dbOptions['driver'] . ':host=' . $dbOptions['host'] . ';port=' . $dbOptions['port'] . ';dbname=' . $dbOptions['dbname'],
                $dbOptions['user'],
                $dbOptions['password']
            );
            // throw exceptions, when SQL error is caused источник https://learntutorials.net/ru/php/topic/5828/pdo
            // setup PDO to throw an exception if an invalid query is provided
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // prevent emulation of prepared statements
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->pdo->exec('SET NAMES UTF8');
            $this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false); // раз
            $this->pdo->setAttribute(\PDO::ATTR_STRINGIFY_FETCHES, false); // два, для возврата не строк, а int и varchar
        } catch (\PDOException $e) {
            throw new DbException('Ошибка при подключении к базе данных: ' . $e->getMessage());
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @return PDO
     */
    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    /**
     * @param string $sql
     * @param array $params
     * @param string $className
     * @return array|null
     *
     *  Функция возвращает массив ОБЪЕКТОВ, заданных в $className
     */
    public function selectClassQuery(string $sql, array $params = [], string $className = 'stdClass'): ?array
    {
        $sth = $this->pdo->prepare($sql);
        $result = $sth->execute($params);

        if (false === $result) {
            return null;
        }

        return $sth->fetchAll(PDO::FETCH_CLASS, $className);
    }

    /**
     * @param string $sql
     * @param array $params
     * @return array|null
     *
     *  Функция должна возвращает массив ЗАПИСЕЙ, результат запроса
     *  С настройками пока не определился...
     */
    public function fetchQuery(string $sql, array $params = []): ?array
    {
        $sth = $this->pdo->prepare($sql);
        $result = $sth->execute($params);

        if (false === $result) {
            return null;
        }

        // настойчиво рекумендуют ВСЕГДА указывать режим выборки
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param string $sql
     * @param array $params
     * @return bool
     *
     *  Одиночный запрос
     */
    public function execQuery(string $sql, array $params = []): bool
    {
        $sth = $this->pdo->prepare($sql);
        return $sth->execute($params);
    }

    public function getLastInsertId(): int
    {
        return (int) $this->pdo->lastInsertId();
    }

}