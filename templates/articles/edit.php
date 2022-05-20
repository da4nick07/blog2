<h1>Редактирование статьи</h1>
<?php if(!empty($_ERROR_)): ?>
    <div style="color: red;"><?= $_ERROR_ ?></div>
<?php endif; ?>
<form action="/articles/<?= $_ARTICLE_->getId() ?>/edit" method="post">
    <label for="name">Название статьи</label><br>
    <input type="text" name="name" id="name" value="<?= htmlentities($_POST['name'] ?? $_ARTICLE_->getName()) ?>" size="50"><br>
    <br>
    <label for="text">Текст статьи</label><br>
    <textarea name="text" id="text" rows="10" cols="80"><?= htmlentities($_POST['text'] ?? $_ARTICLE_->getText()) ?></textarea><br>
    <br>
    <input type="submit" value="Записать">
</form>

