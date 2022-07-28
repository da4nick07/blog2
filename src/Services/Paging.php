<?php
namespace Services;

use PDO;

class Paging
{
    /**
     * Возвращает массив id статей, согласно указанной сортировке.
     * Результат кешируется в редис.
     *
     * @param int $sort
     * @return array
     */
    public static function getArticlesId( int $sort = 0): array
    {
        ini_set('memory_limit', '512M');
        $fname = 'art' . REDIS_SEPARATOR . $sort;
        $redis = MRedis::getInstance();
        if ($redis->getRedis()->exists($fname)) {
            return json_decode( $redis->getRedis()->get($fname),true );
        }

        $db = Db::getInstance();
        $articles = $db->fetchQuery('SELECT  id  FROM `articles` ORDER BY id ;');
        $p = [];
        $count = count($articles);
        for ($i = 0; $i < $count; $i++) {
            $p[] = $articles[$i]['id'];
        }
        // большое кол-во ключей (миллион) не берёт (((
//        $redis->getRedis()->hmset( $fname, $p);
        $redis->getRedis()->set( $fname, json_encode($p));

        // Сразу посчитаем / запишем кол-во страниц
        $fname = 'art' . REDIS_SEPARATOR . 'cnt' . REDIS_SEPARATOR . ARTICLES_PER_PAGE;
        $redis->getRedis()->set( $fname, ceil($count / ARTICLES_PER_PAGE));


        return $p;
    }

    /**
     * Возвращает массив id статей для указанной страницы, согласно указанной сортировке и нарезке (кол-ву эл-ов на странице).
     * Результат кешируется в редис.
     *
     * @param int $sort
     * @param int $itemsPerPage
     * @param int $pageNum
     * @return array
     */
    public static function getArticlesIdOnPage( int $sort, int $itemsPerPage, int $pageNum): array
    {
        $fname = 'art' . REDIS_SEPARATOR . $sort . REDIS_SEPARATOR . $itemsPerPage . REDIS_SEPARATOR . $pageNum;
        $redis = MRedis::getInstance();
        if ($redis->getRedis()->exists($fname)) {
            return $redis->getRedis()->hGetAll($fname);
        }

        $articles = static::getArticlesId( $sort);
        $count = count($articles) -1;
        $p = [];
        $start = $itemsPerPage * ($pageNum -1);
        for ($i = 0; $i < $itemsPerPage; $i++) {
            $p[$i] = $articles[ $start +$i];
            if ( ($start +$i) === $count ) {
                break;
            }
        }
        $redis->getRedis()->hMSet( $fname, $p);
        return $p;
    }

    /**
     * Возвращает массив данных для формирования указанной страницы в списке статей, согласно указанной сортировке и нарезке (кол-ву эл-ов на странице).
     *
     * @param int $sort
     * @param int $itemsPerPage
     * @param int $pageNum
     * @return array | null
     */
    public static function getArticlesOnPage( int $sort, int $itemsPerPage, int $pageNum): ?array
    {
        /*
                $db = Db::getInstance();
                return $db->fetchQuery(
               'SELECT  a.id as a_id, a.name, a.text, nickname  FROM `articles` a JOIN `users` u ON a.author_id = u.id
                        ORDER BY a_id LIMIT ' . $itemsPerPage . ' OFFSET ' . ( $pageNum -1) *$itemsPerPage . ';');
        */

        $art = static::getArticlesIdOnPage( $sort, $itemsPerPage, $pageNum);

        $db = Db::getInstance();
//        $sql = 'SELECT a_id, a.name, a.text, nickname FROM ( SELECT  a.id as a_id, a.name, a.text, a.author_id  FROM `articles` a WHERE id IN (' . implode( ',', $art) . ')) a
//                    INNER JOIN `users` u ON a.author_id = u.id  ORDER BY a_id ;';
        // на миллионе записей всё равно 0,001 сек.
        $sql = 'SELECT  a.id as a_id, a.name, a.text, nickname, a.created_at  FROM `articles` a
                    INNER JOIN `users` u ON a.author_id = u.id WHERE a.id IN (' . implode( ',', $art) . ') ORDER BY a_id ;';
        $sth = $db->getPdo()->prepare($sql);
        $res = $sth->execute();
        if ($res === false) {
            return null;
        }

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getPagesCount(int $itemsPerPage): int
    {
        $fname = 'art' . REDIS_SEPARATOR . 'cnt' . REDIS_SEPARATOR . $itemsPerPage;
        $redis = MRedis::getInstance();
        if ($redis->getRedis()->exists($fname)) {
            return $redis->getRedis()->get($fname);
        }
        // по идее мы сюда не должны попадать, только при изменении $itemsPerPage

        // на больших количествах (миллион, например) похоже лучше запрос
//        $db = Db::getInstance();
//        $result = $db->fetchQuery('SELECT COUNT(*) AS cnt FROM ' . static::$tableName . ';');
//        $result = ceil($result[0]['cnt'] / $itemsPerPage);
        $result = ceil(count( static::getArticlesId()) / $itemsPerPage);
        $redis->getRedis()->set( $fname, $result);

        return $result;
    }

    public static function flushCache(bool $recalc = true): void
    {
        $redis = MRedis::getInstance();
        $redis->getRedis()->del( $redis->getRedis()->keys('art*') );
        if ($recalc) {
            static::getArticlesId();
        }
    }

}

