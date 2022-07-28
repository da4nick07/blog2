<h2><?= htmlentities($_ARTICLE_->getName() ?? "НЕТ НАЗВАНИЯ?") ?></h2>
<p><?= htmlentities($_ARTICLE_->getText() ?? "НЕТ ТЕКСТА?")  ?></p>
<p> Автор: <?= $_ARTICLE_->getAuthor()->getNickname() ?? "АВТОР НЕ УКАЗАН?"  ?></p>
<p> Дата публикации: <?= $_ARTICLE_->getCreatedAt() ?? "Неизвестна" ?></p>

<?php if( $_ARTICLE_->isEditable( $_USER_ ) ): ?>
    <a href="/articles/<?= $_ARTICLE_->getId() ?>/edit">Редактировать статью</a>
    &nbsp;&nbsp;<a href="/articles/<?= $_ARTICLE_->getId() ?>/delete">Удалить</a>
<?php endif; ?>
