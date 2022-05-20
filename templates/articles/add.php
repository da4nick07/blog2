<h1>Создание новой статьи</h1>
<?php if(!empty($error)): ?>
    <div style="color: red;"><?= $error ?></div>
<?php endif; ?>
<form action="/articles/add" method="post">
    <label for="name">Название статьи</label><br>
    <input type="text" name="name" id="name" value="<?= htmlentities($_POST['name'] ?? '') ?>" size="50"><br>
    <br>
    <label for="text">Текст статьи</label><br>
    <textarea name="text" id="text" rows="10" cols="80"><?= htmlentities($_POST['text'] ?? '') ?></textarea><br>
    <br>
    <input type="submit" value="Создать">
</form>

