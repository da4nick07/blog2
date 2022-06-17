<?php
namespace Controllers;

use Models\Article;
use Exceptions\UnauthorizedException;
use Exceptions\InvalidArgumentException;
use Exceptions\Forbidden;
use Exceptions\NotFoundException;
use Services\MRedis;
use Services\Paging;

class ArticlesController extends BaseController
{

    public function view(array $params) : void
    {
        $article = Article::selectOneByColumn( 'id', (int)$params[ MATCHES ][1] );

        if ( $article === null) {
            throw new NotFoundException();
        }

        $vars['_USER_'] = $params[ USER ];
        $vars['_MAIN_ARTICLES_'] = renderVars(ROOT_DIR . 'templates/articles/article.php', ['_USER_' => $vars['_USER_'], '_ARTICLE_' => $article]);
        echo renderVars( ROOT_DIR . 'templates/main/main.php', $vars);
    }

    public function edit(array $params): void
    {
        if ( $params[ USER ] === null) {
            throw new UnauthorizedException();
        }

        /** @var  $article Article */
        $article = Article::selectOneByColumn( 'id', (int)$params[ MATCHES ][1]);

        if ($article === null) {
            throw new NotFoundException();
        }
        if ( !( ($article->getAuthorId() === $params[ USER ]->getId()) or ( $params[ USER ]->getRole() === 'admin')) ) {
            throw new Forbidden( ' Редактировать может либо автор, либо Администратор');
        }

        if ( empty($_POST) ) {
            $vars['_USER_'] = $params[ USER ];
            $vars['_MAIN_ARTICLES_'] = renderVars(ROOT_DIR . 'templates/articles/edit.php', ['_ARTICLE_' => $article]);
            echo renderVars( ROOT_DIR . 'templates/main/main.php', $vars);
        } else {
            try {
                $article->updateFromArray($_POST);
            } catch (InvalidArgumentException $e) {
                $vars['_USER_'] = $params[ USER ];
                $vars['_MAIN_ARTICLES_'] = renderVars(ROOT_DIR . 'templates/articles/edit.php', ['_ERROR_' => $e->getMessage(), '_ARTICLE_' => $article]);
                echo renderVars( ROOT_DIR . 'templates/main/main.php', $vars);
                return;
            }

            header('Location: /articles/' . $article->getId(), true, 302);
            exit();
        }

    }

    public function add(array $params): void
    {
        if ( $params[ USER ] === null) {
            throw new UnauthorizedException();
        }

        if ($params[ USER ]->getRole() !== 'admin') {
            throw new Forbidden( 'Для добавления статьи нужно обладать правами администратора' );
        }

        $article = null;
        $vars2['_ERROR_'] = null;
        if (!empty($_POST)) {
            try {
                // объект создаём для контроля корректности значений
                $article = Article::insertFromArray( $_POST, $params[ USER ] );
            } catch (InvalidArgumentException $e) {
                $vars2['_ERROR_'] = $e->getMessage();
            }
        }

        if ( $article !== null) {
            // удалим всё закешированное
            $redis = MRedis::getInstance();
            $redis->getRedis()->del( $redis->getRedis()->keys('art*') );
            // пересчитаем базовое для кэша
            Paging::getArticlesId(0);

            header('Location: /articles/' . $article->getId());
        } else {
            $vars['_USER_'] = $params[ USER ];
            $vars['_MAIN_ARTICLES_'] = renderVars( ROOT_DIR . 'templates/articles/add.php', $vars2);
            echo renderVars( ROOT_DIR . 'templates/main/main.php', $vars);
        }
    }

    public function delete(array $params): void
    {
        $article = Article::selectOneByColumn( 'id', (int)$params[ MATCHES ][0] );

        if ($article === null) {
            http_response_code(404);
            echo renderVars( ROOT_DIR . 'templates/errors/404.php', []);
            return;
        }
        $article->delete();
    }
}