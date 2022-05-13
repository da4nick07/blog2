<?php

namespace Models;

use Exceptions\InvalidArgumentException;
use PDO;

class User extends ActiveRecordEntity
{
    protected static string $tableName = 'users';
    public static array $updateAr = array( 'nickname'=>PDO::PARAM_STR,
        'email'=>PDO::PARAM_STR,
        'password_hash'=>PDO::PARAM_STR,
        'auth_token'=>PDO::PARAM_STR);

    public static array $insertAr = array( 'nickname'=>PDO::PARAM_STR,
        'email'=>PDO::PARAM_STR,
        'is_confirmed'=>PDO::PARAM_BOOL,
        'role'=>PDO::PARAM_STR,
        'password_hash'=>PDO::PARAM_STR,
        'auth_token'=>PDO::PARAM_STR);
//                     'created_at'=>'');

    public static array $refreshAr = array( 'auth_token'=>PDO::PARAM_STR,
        'created_at'=>PDO::PARAM_STR);

    protected string $nickname;
    protected string $email;
    protected int $is_confirmed;
    protected string $role;
    protected string $password_hash;
    protected string $auth_token;
    protected string $created_at;
    /*
        public function __construct()
        {
        }
    */
    /**
     * @return string
     */
    public function getNickname(): string
    {
        return $this->nickname;
    }

    public static function signUp(array $userData) : User
    {
        if (empty($userData['nickname'])) {
            throw new InvalidArgumentException('Не передан nickname');
        }

        if (!preg_match('/^[a-zA-Z0-9]+$/', $userData['nickname'])) {
            throw new InvalidArgumentException('Nickname может состоять только из символов латинского алфавита и цифр');
        }

        if (empty($userData['email'])) {
            throw new InvalidArgumentException('Не передан email');
        }

        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Email некорректен');
        }

        if (empty($userData['password'])) {
            throw new InvalidArgumentException('Не передан password');
        }

        if (mb_strlen($userData['password']) < 3) {
            throw new InvalidArgumentException('Пароль должен быть не менее 3 символов');
        }

        if (static::selectOneByColumn('nickname', $userData['nickname']) !== null) {
            throw new InvalidArgumentException('Пользователь с таким nickname уже существует');
        }

        if (static::selectOneByColumn('email', $userData['email']) !== null) {
            throw new InvalidArgumentException('Пользователь с таким email уже существует');
        }

        $user = new User();
        $user->nickname = $userData['nickname'];
        $user->email = $userData['email'];
        $user->password_hash = password_hash($userData['password'], PASSWORD_DEFAULT);
        $user->is_confirmed = true; // подтверждение по почте пока отложил
        $user->role = 'user';
        $user->auth_token = sha1(random_bytes(100)) . sha1(random_bytes(100));

        $ar = [];
        foreach (static::$insertAr as $property => $v) {
            $ar[$property] = $user->$property;
        }
        $user->id = User::insert( $ar );

        return $user;
    }

    public static function login(array $loginData): User
    {
        if (empty($loginData['email'])) {
            throw new InvalidArgumentException('Не передан email');
        }

        if (empty($loginData['password'])) {
            throw new InvalidArgumentException('Не передан password');
        }

        /** @var  $user User */
        $user = User::selectOneByColumn('email', $loginData['email']);
        if ($user === null) {
            throw new InvalidArgumentException('Нет пользователя с таким email');
        }

        if (!password_verify($loginData['password'], $user->getPasswordHash())) {
            throw new InvalidArgumentException('Неправильный пароль');
        }

        if (!$user->is_confirmed) {
            throw new InvalidArgumentException('Пользователь не подтверждён');
        }

        $user->refreshAuthToken();
        User::update( $user->getId(), array( 'auth_token' => $user->auth_token));

        return $user;
    }

    public function getPasswordHash(): string
    {
        return $this->password_hash;
    }

    private function refreshAuthToken()
    {
        $this->auth_token = sha1(random_bytes(100)) . sha1(random_bytes(100));
    }

    public function getAuthToken() : string
    {
        return $this->auth_token;
    }

    public function getRole() : string
    {
        return $this->role;
    }
}