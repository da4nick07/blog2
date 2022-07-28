<?php

namespace Controllers;

require_once ROOT_DIR . 'lib/templates.php';
use Services\Paging;

class MainController extends BaseController
{

    public function main( array $params )
    {
        $params[ MATCHES ] = array( '/1', '1');
        $this->page($params);
    }

    public function page(array $params) : void
    {
        /*
        * Вопрос: зачем для создания списка / перечня статей для КАЖДОЙ статьи создавать экземпляр класса? Мы так ресурсы бережём?!
        */
//        $articles = Article::selectClassAll();

        $vars['_USER_'] = $params[ USER ];
        $itemsPerPage =ARTICLES_PER_PAGE;
        $articles = Paging::getArticlesOnPage( 0, $itemsPerPage, (int)$params[ MATCHES ][ 1 ]);
        $vars['_MAIN_ARTICLES_'] = getArticlrsPage( $articles, $params);
        echo renderTpl( ROOT_DIR . 'templates/main/main.php', $vars);
    }

    public function sayHello(array $params = []) : void
    {
        $vars['_MAIN_TITLE_']  = 'Приветствие';
        $vars['_USER_'] = $params[ USER ];
        $vars['_MAIN_ARTICLES_'] = 'Привет, ' . htmlentities($params[ MATCHES ][1]??'');

        echo renderTpl( ROOT_DIR . 'templates/main/main.php', $vars);
    }

    public function sayBye(array $params = []) : void
    {
        $vars['_MAIN_TITLE_']  = 'Пока, пока...';
        $vars['_USER_'] = $params[ USER ];
        $vars['_MAIN_ARTICLES_'] = 'Пока... Пока..., ' . htmlentities($params[ MATCHES ][1]??'');

        echo renderTpl( ROOT_DIR . 'templates/main/main.php', $vars);
    }

    public function test(array $params = []) : void
    {

        $vars['subject'] = 'Subject';
        $vars['name'] = 'Name';
        $vars['adress'] = 'Adress';
        //       echo renderTpl( ROOT_DIR . 'templates/test/test.html', $vars);
        if ( count($vars) ) {
            extract($vars, EXTR_OVERWRITE);
        }

        include ROOT_DIR . 'templates/test/test.html';
    }

}