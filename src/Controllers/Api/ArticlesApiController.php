<?php

namespace Controllers\Api;

use Controllers\BaseController;
use Exceptions\NotFoundException;
use Models\Article;
use Models\User;

class ArticlesApiController extends BaseController
{
    public function view(int $articleId)
    {
        $article = Article::selectOneByColumn( 'id', $articleId);

        if ($article === null) {
            throw new NotFoundException('Статья не найдена ( id = ' . $articleId . ')');
        }
        displayJson( $article->jsonSerialize());
    }

    public function add()
    {
        $input = $this->getInputData();
        $articleFromRequest = $input['articles'][0];

        $authorId = $articleFromRequest['author_id'];
        /** @var  $author User */
        $author = User::selectOneByColumn( 'id', $authorId);

        $article = Article::insertFromArray($articleFromRequest, $author);

        header('Location: /api/articles/' . $article->getId(), true, 302);
    }

}
