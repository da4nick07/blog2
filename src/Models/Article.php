<?php

namespace Models;

//use Models\User;
//use Models\ActiveRecordEntity;

// каскадная загрузка классов
//require_once  'User.php';

use Exceptions\InvalidArgumentException;

class Article extends ActiveRecordEntity
{
    protected static int $count = 0;
    protected static string $tableName = 'articles';

    protected int $author_id;
    protected User $author;
    protected string $name;
    protected string $text;
    protected string  $created_at;

    public function __construct()
    {
        self::$count++;

        $this->updateAr =array( 'name'=>'',
            'text'=>'');

        $this->insertAr =array( 'author_id'=>'',
            'name'=>'',
            'text'=>'');
        //                               'created_at'=>'');

        $this->refreshAr =array( '$created_at'=>'');
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
        foreach ($article->insertAr as $property => $v) {
            $article->insertAr[$property] = $article->$property;
        }
        $article->id =  Article::insert( $article->insertAr );

        return $article;
    }

    public function changeFromArray(array $fields): Article
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
        foreach ($this->updateAr as $property => $v) {
            $this->updateAr[$property] = $this->$property;
        }
        return Article::update( $this->getId(), $this->updateAr );
    }
}
