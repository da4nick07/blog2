<?php

namespace Controllers\Api;

use Controllers\BaseController;
use Exceptions\Forbidden;
use Exceptions\NotFoundException;
use Exceptions\UnauthorizedException;
use Models\Article;
use Models\User;

class ArticlesApiController extends BaseController
{
    public function view(array $params)
    {
        $article = Article::selectOneByColumn( 'id', (int) $params[ MATCHES ][1]);

        if ($article === null) {
            throw new NotFoundException('Статья не найдена ( id = ' . (int) $params[ MATCHES ][1] . ')');
        }
        displayJson( $article->jsonSerialize());
    }

    public function add(array $params)
    {
        if ( $params[ USER ] === null) {
            throw new UnauthorizedException('Вы не авторизованы.');
        }
        if ( $params[ USER ]->getRole() !== 'admin' ) {
            throw new Forbidden( ' Добавлять статьи может только Администратор');
        }
        $input = $this->getInputData();
        $articleFromRequest = $input['articles'][0];

        $authorId = $articleFromRequest['author_id'];
        /** @var  $author User */
        $author = User::selectOneByColumn( 'id', $authorId);

        $article = Article::insertFromArray($articleFromRequest, $author);

        header('Location: /api/articles/' . $article->getId(), true, 302);
    }

}
