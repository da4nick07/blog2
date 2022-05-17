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

    public function refresh(): bool
    {
        $objFromDb = static::selectOneByColumn( 'id', $this->id);
        if ( $objFromDb !== null ) {
            foreach (static::$refreshAr as $key=>$value) {
                $this->$key = $objFromDb->$key;
            }
            return true;
        }
        return false;
    }

    public static function update( int $id, array $properties): bool
    {

        $columns2params = [];
        $params2values = [];
        foreach (static::$updateAr as $property => $v) {
            if ( isset( $properties[ $property ] )) {
                $columns2params[] = $property . ' = :' . $property; // param1 = :param1
                $params2values[$property] = $properties[$property];
            }
        }

        $db = Db::getInstance();
        $sth = $db->getPdo()->prepare('UPDATE ' . static::$tableName . ' SET ' . implode(', ', $columns2params) . ' WHERE id = ' . $id);
//        return $sth->execute($params2values);

        foreach ($params2values as $property => $v) {
            $sth->bindValue( $property, $v, static::$updateAr[$property] );
        }
        return $sth->execute();

    }

    public static function insert(array $properties): int
    {

        $params = [];
        $params2values = [];
        foreach (static::$insertAr as $property => $v) {
            $params[] = ':' . $property; // param1 = :param1
            $params2values[$property] = $properties[$property];
        }

        $db = Db::getInstance();
        $sth = $db->getPdo()->prepare('INSERT INTO ' . static::$tableName . '(' . implode(', ', array_keys( $params2values )) . ') VALUES (' . implode(', ', $params) . ')');
//        $sth->execute($params2values);

        foreach ($params2values as $property => $v) {
            $sth->bindValue( $property, $v, static::$insertAr[$property] );
        }
        if ( !$sth->execute() ) {
            return 0;
        }

        return $db->getLastInsertId();
    }

    public function delete(): bool
    {
        $db = Db::getInstance();

//        $db->execQuery('DELETE FROM `' . static::$tableName . '` WHERE id = :id', [':id' => $this->id] );
        $sth = $db->getPdo()->prepare('DELETE FROM `' . static::$tableName . '` WHERE id = :id');
        $sth->bindParam( ':id', $this->id, PDO::PARAM_INT );
        if ( $sth->execute() ) {
            $this->id = null;
            return true;
        }
        return false;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
