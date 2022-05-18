<?php
namespace Controllers;

use Models\User;
use Exceptions\InvalidArgumentException;

class UsersController
{
    public function signUp()
    {
        $vars['_MAIN_TITLE_']  = 'Регистрация';
        $user = null;

        $vars2['_ERROR_'] = null;
        if (!empty($_POST)) {
            try {
                $user = User::signUp($_POST);
            } catch (InvalidArgumentException $e) {
                $vars2['_ERROR_'] = $e->getMessage();
            }
        }

        if ($user instanceof User) {
            $vars['_USER_'] = $user;
            $vars['_MAIN_ARTICLES_'] = include ROOT_DIR . 'templates/users/signUpSuccessful.php';
        } else {
            $vars['_MAIN_ARTICLES_'] = renderVars(ROOT_DIR . 'templates/users/signUp.php', $vars2);
        }

        echo renderVars( ROOT_DIR . 'templates/main/main.php', $vars);
    }

    public function login()
    {
        $vars['_MAIN_TITLE_']  = 'Авторизация';

        $vars2['_ERROR_'] = null;
        if (!empty($_POST)) {
            try {
                $user = User::login($_POST);
                createToken($user);
                header('Location: /hello/' . $user->getNickname());
                exit();
            } catch (InvalidArgumentException $e) {
                $vars2['_ERROR_'] = $e->getMessage();
            }
        }
        $vars['_MAIN_ARTICLES_'] = renderVars(ROOT_DIR . 'templates/users/login.php', $vars2);

        echo renderVars( ROOT_DIR . 'templates/main/main.php', $vars);
    }

    public function logOut( array $params = [] )
    {
        setcookie('token', '', -1, '/', '', false, true);
        header('Location: /bye/' . $params[ USER ]->getNickname());
    }
}