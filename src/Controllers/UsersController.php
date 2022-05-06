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
        /*
                // это вариант подстановкой / заменой строк, через str_replace ( плейсхолдеры)
                // ГЛАВНОЕ - можно обойтись БЕЗ открытия файла с шаблоном
                if (!empty($_POST)) {
                    try {
                        $user = User::signUp($_POST);
                    } catch (InvalidArgumentException $e) {
                        // блок с ошибкой можно вынести в функцию, не дело тут держать HTML
                        $error = '
                            <div style="background-color: red;padding: 5px;margin: 15px">' . $e->getMessage() .  '</div>
                        ';
                    }
                }

                if ($user instanceof User) {
                    $vars['_MAIN_ARTICLES_'] = signUpSuccessful();
                } else {
                    $nickname = $_POST['nickname'] ?? null;
                    $email = $_POST['email'] ?? '';
                    $password = $_POST['password'] ?? '';

                    $vars['_MAIN_ARTICLES_'] = signUp();
                    $vars['_MAIN_ARTICLES_'] = str_replace( array('%ERROR%', '%NICKNAME%', '%EMAIL%', '%PASSWORD%'), array($error, $nickname, $email, $password), $vars['_MAIN_ARTICLES_'] );
                }
        */
        // вариант через буфер вывода, подстановка через переменные
        // тут без include  с открытием файла не обойтись
        // или что?

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
//            $vars['_MAIN_ARTICLES_'] = getHtml2('signUp', $vars2);
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
//                UsersAuthService::createToken($user);
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
