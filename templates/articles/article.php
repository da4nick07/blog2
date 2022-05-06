<h2><?= $_ARTICLE_->getName() ?></h2>
<p><?= $_ARTICLE_->getText()  ?></p>
<p> Автор: <?= $_ARTICLE_->getAuthor()->getNickname()  ?></p>

<?php if( $_ARTICLE_->isEditable( $_USER_ ) ): ?>
    <a href="/articles/<?= $_ARTICLE_->getId() ?>/edit">Редактировать статью</a>
<?php endif; ?>
