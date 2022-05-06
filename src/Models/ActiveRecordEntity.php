<?php
namespace Models;

use Services\Db;

abstract class ActiveRecordEntity
{
//  ВНИМАНИЕ! У наследников необходимо задать static string $tableName
//    protected static string $tableName = 'articles';
    protected ?int $id;

    public array  $insertAr = [];
    public array  $updateAr = [];
    protected array  $refreshAr = [];

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
        foreach ($properties as $column => $value) {
            $param = ':param' . $index; // :param1
            $columns2params[] = $column . ' = ' . $param; // column1 = :param1
            $params2values[$param] = $value; // [:param1 => value1]
            $index++;
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
        foreach ($properties as $column => $value) {
            $params[] = ':param' . $index; // :params
            $params2values[':param' . $index] = $value; // [:param => value]
            $index++;
        }
        $sql = 'INSERT INTO ' . static::$tableName . '(' . implode(', ', array_keys( $properties )) . ') VALUES (' . implode(', ', $params) . ')';
        $db = Db::getInstance();
        $db->execQuery($sql, $params2values);

        return $db->getLastInsertId();
    }

    public function refresh(): void
    {
        $objFromDb = static::selectOneByColumn( 'id', $this->id);
//        $properties = get_object_vars($objFromDb);
        foreach ($this->refreshAr as $key=>$value) {
//            $this->$key = $value;
            $this->$key = $objFromDb->$key;
        }
    }

    public function delete(): void
    {
        $db = Db::getInstance();
        // зачем тут указание класса ?!
        // а где $sth->bindParam(':id', $calories, PDO::PARAM_INT);
        $db->execQuery('DELETE FROM `' . static::$tableName . '` WHERE id = :id', [':id' => $this->id] );
        $this->id = null;
    }
}