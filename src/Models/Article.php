<?php

namespace Models;

//use Models\User;
//use Models\ActiveRecordEntity;

// каскадная загрузка классов
//require_once  'User.php';

use Exceptions\InvalidArgumentException;
use PDO;

class Article extends ActiveRecordEntity
{
    protected static int $count = 0;
    protected static string $tableName = 'articles';

    public static array $updateAr = array( 'name'=>PDO::PARAM_STR,
        'text'=>PDO::PARAM_STR);

    public static array $insertAr = array( 'author_id'=>PDO::PARAM_INT,
        'name'=>PDO::PARAM_STR,
        'text'=>PDO::PARAM_STR);

    public static array $refreshAr = array( '$created_at'=>'');


    protected int $author_id;
    protected User $author;
    protected string $name;
    protected string $text;
    // в БД - timestamp
    protected string  $created_at;

    public function __construct()
    {
        self::$count++;

    }

    public function getCount() : int
    {
        return self::$count;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName( string $name): void
    {
        $this->name = $name;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText( string $text): void
    {
        $this->text = $text;
    }

    public function getAuthor(): ActiveRecordEntity
    {
        return User::selectOneByColumn( 'id', $this->author_id);
    }

    public function getAuthorId(): int
    {
        return $this->author_id;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }


    public function isEditable( ?User $user): bool
    {
        if ( $user === null) {
            return false;
        }
        return ( ($user->getRole() === 'admin') or ($this->author_id === $user->getId()));
    }


    public static function createFromArray(array $fields, User $author): Article
    {
        if (empty($fields['name'])) {
            throw new InvalidArgumentException('Не передано название статьи');
        }

        if (empty($fields['text'])) {
            throw new InvalidArgumentException('Не передан текст статьи');
        }

        $article = new Article();

        $article->author_id = $author->getId();
        $article->setName($fields['name']);
        $article->setText($fields['text']);

        return $article;
    }

    public static function insertFromArray(array $fields, User $author): Article
    {
        $article = Article::createFromArray( $fields, $author );
        $ar = [];
        foreach (static::$insertAr as $property => $v) {
            $ar[$property] = $article->$property;
        }

        $article->id =  Article::insert( $ar );

        return $article;
    }

    public function changeFromArray(array &$fields): Article
    {
        if (empty($fields['name'])) {
            throw new InvalidArgumentException('Не передано название статьи');
        }

        if (empty($fields['text'])) {
            throw new InvalidArgumentException('Не передан текст статьи');
        }

        $this->setName($fields['name']);
        $this->setText($fields['text']);

        return $this;
    }

    public function updateFromArray(array $fields): bool
    {
        $this->changeFromArray( $fields );

        return Article::update( $this->getId(), $fields );
    }
}