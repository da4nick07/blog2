<div style="text-align: center;">
    <h1>Вход</h1>
    <?php if (!empty($_ERROR_)): ?>
        <div style="background-color: red;padding: 5px;margin: 15px"><?= $_ERROR_ ?></div>
    <?php endif; ?>

    <form action="/users/login" method="post">
        <label>Email <input type="text" name="email" value="<?= htmlentities($_POST['email'] ?? '') ?>"></label>
        <br><br>
        <label>Пароль <input type="password" name="password" value="<?= htmlentities($_POST['password'] ?? '') ?>"></label>
        <br><br>
        <input type="submit" value="Войти">
    </form>
</div>


