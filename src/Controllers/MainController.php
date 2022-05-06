<?php

namespace Controllers;

use Models\Article;

class MainController extends BaseController
{

    public function main(array $params) : void
    {
        /*
        * Вопрос: зачем для создания списка / перечня статей для КАЖДОЙ статьи создавать экземпляр класса? Мы так ресурсы бережём?!
        */
        $articles = Article::selectClassAll();
//        $articles = $this->db->query(
//            'SELECT  a.id as a_id, a.name, a.text, nickname  FROM `articles` a JOIN `users` u ON a.author_id = u.id', Article::class);
//        <h2><a href="/articles/' . $article->getId() . '">' . $article->getName() . '(' . $article->getCount() . ')' .  '</a></h2>

        $vars['_USER_'] = $params[ USER ];
        $vars['_MAIN_ARTICLES_'] = '';
        foreach ( $articles as $article) {
            $vars['_MAIN_ARTICLES_'] .= '
        <h2><a href="/articles/' . $article->getId() . '">' . $article->getName() .  '</a>' . '(count = ' . $article->getCount() . ')' . '</h2>
                <p>' . $article->getText() .  '</p>
                <hr>
            ';
        }
//                <p>Автор: <i>' . $article['nickname'] .  '</i></p>
        echo renderVars( ROOT_DIR . 'templates/main/main.php', $vars);
    }

    public function sayHello(array $params = []) : void
    {
        $vars['_MAIN_TITLE_']  = 'Приветствие';
        $vars['_USER_'] = $params[ USER ];
        $vars['_MAIN_ARTICLES_'] = 'Привет, ' . $params[ URI ][1]??'';

        echo renderVars( ROOT_DIR . 'templates/main/main.php', $vars);
    }

    public function sayBye(array $params = []) : void
    {
        $vars['_MAIN_TITLE_']  = 'Пока, пока...';
        $vars['_USER_'] = $params[ USER ];
        $vars['_MAIN_ARTICLES_'] = 'Пока... Пока..., ' . $params[ URI ][1]??'';

        echo renderVars( ROOT_DIR . 'templates/main/main.php', $vars);
    }
}

