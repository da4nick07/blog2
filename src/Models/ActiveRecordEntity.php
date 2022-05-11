<?php
namespace Models;

use Services\Db;
use PDO;

abstract class ActiveRecordEntity
{
//  ВНИМАНИЕ! У наследников необходимо задать static string $tableName
//    protected static string $tableName = 'articles';
    protected ?int $id;

    public static array  $insertAr = [];
    public static array  $updateAr = [];
    protected static array  $refreshAr = [];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return static[]
     */
    public static function selectClassAll(): array
    {
        return (Db::getInstance())->selectClassQuery('SELECT * FROM `' . static::$tableName . '`;', [], static::class);
    }

    public static function selectOneByColumn(string $columnName, $value): ?self
    {
        $db = Db::getInstance();
        $result = $db->selectClassQuery(
            'SELECT * FROM `' . static::$tableName . '` WHERE `' . $columnName . '` = :value LIMIT 1;',
            [':value' => $value], static::class
        );
        if ($result === []) {
            return null;
        }
        return $result[0];
    }

    public static function update( int $id, array $properties): bool
    {
        $columns2params = [];
        $params2values = [];
        $index = 1;
        foreach (static::$updateAr as $column => $v) {
            if ( isset( $properties[ $column ] )) {
                $param = ':param' . $index; // :param1
                $columns2params[] = $column . ' = ' . $param; // column1 = :param1
                $params2values[ $param ] = $properties[ $column ]; // [:param1 => value1]
                $index++;
            }
        }
        $sql = 'UPDATE ' . static::$tableName . ' SET ' . implode(', ', $columns2params) . ' WHERE id = ' . $id;
        $db = Db::getInstance();

        return $db->execQuery($sql, $params2values);
        // нужна обработка ошибки БД
    }

    public static function insert(array $properties): int
    {
        $params = [];
        $params2values = [];
        $index = 1;
        foreach (static::$insertAr as $column => $v) {
            if ( isset( $properties[ $column ] )) {
                $params[] = ':param' . $index; // :params
                $params2values[':param' . $index] = $properties[ $column ]; // [:param => value]
                $index++;
            }
        }
        $sql = 'INSERT INTO ' . static::$tableName . '(' . implode(', ', array_keys( $properties )) . ') VALUES (' . implode(', ', $params) . ')';
        $db = Db::getInstance();
        $db->execQuery($sql, $params2values);

        return $db->getLastInsertId();
    }

    public function refresh(): void
    {
        $objFromDb = static::selectOneByColumn( 'id', $this->id);
        foreach (static::$refreshAr as $key=>$value) {
            $this->$key = $objFromDb->$key;
        }
    }

    public function delete(): void
    {
        $db = Db::getInstance();

//        $db->execQuery('DELETE FROM `' . static::$tableName . '` WHERE id = :id', [':id' => $this->id] );
        $sth = $db->getPdo()->prepare('DELETE FROM `' . static::$tableName . '` WHERE id = :id');
        $sth->bindParam( ':id', $this->id, PDO::PARAM_INT );
        $sth->execute();
        $this->id = null;
    }
}