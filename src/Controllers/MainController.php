<?php

namespace Controllers;

require_once ROOT_DIR . 'lib/templates.php';

use Models\Article;

class MainController extends BaseController
{

    public function main(array $params) : void
    {
        /*
        * Вопрос: зачем для создания списка / перечня статей для КАЖДОЙ статьи создавать экземпляр класса? Мы так ресурсы бережём?!
        */
//        $articles = Article::selectClassAll();
        $articles = $this->db->fetchQuery(
            'SELECT  a.id as a_id, a.name, a.text, nickname  FROM `articles` a JOIN `users` u ON a.author_id = u.id');

        $vars['_USER_'] = $params[ USER ];
        $vars['_MAIN_ARTICLES_'] = '';
        $articleTpl = oneArticle();
        foreach ( $articles as $article) {
            /*
                        $vars['_MAIN_ARTICLES_'] .= str_replace( array( '%ARTICLE_ID%', '%ARTICLE_NAME%', '%ARTICLE_TEXT%', '%NICK_NAME%'),
                            array( $article->getId(), $article->getName(), $article->getText(), 'xxx' ), $articleTpl);
            */
            $vars['_MAIN_ARTICLES_'] .= str_replace( array( '%ARTICLE_ID%', '%ARTICLE_NAME%', '%ARTICLE_TEXT%', '%NICK_NAME%'),
                array( $article['a_id'], $article['name'], $article['text'], $article['nickname'] ), $articleTpl);

        }
        echo renderVars( ROOT_DIR . 'templates/main/main.php', $vars);
    }

    public function sayHello(array $params = []) : void
    {
        $vars['_MAIN_TITLE_']  = 'Приветствие';
        $vars['_USER_'] = $params[ USER ];
        $vars['_MAIN_ARTICLES_'] = 'Привет, ' . $params[ MATCHES ][1]??'';

        echo renderVars( ROOT_DIR . 'templates/main/main.php', $vars);
    }

    public function sayBye(array $params = []) : void
    {
        $vars['_MAIN_TITLE_']  = 'Пока, пока...';
        $vars['_USER_'] = $params[ USER ];
        $vars['_MAIN_ARTICLES_'] = 'Пока... Пока..., ' . $params[ MATCHES ][1]??'';

        echo renderVars( ROOT_DIR . 'templates/main/main.php', $vars);
    }

    public function test(array $params = []) : void
    {

        $vars['subject'] = 'Subject';
        $vars['name'] = 'Name';
        $vars['adress'] = 'Adress';
        //       echo renderVars( ROOT_DIR . 'templates/test/test.html', $vars);
        if ( count($vars) ) {
            extract($vars, EXTR_OVERWRITE);
        }

        include ROOT_DIR . 'templates/test/test.html';
    }

}