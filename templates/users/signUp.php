<div style="text-align: center;">
    <h1>Регистрация</h1>
    <?php if (!empty($_ERROR_)): ?>
        <div style="background-color: red;padding: 5px;margin: 15px"><?= $_ERROR_ ?></div>
    <?php endif; ?>
    <form action="/users/register" method="post">
        <label>Nickname <input type="text" name="nickname" value="<?= $_POST['nickname'] ?? '' ?>"></label>
        <br><br>
        <label>Email <input type="text" name="email" value="<?= $_POST['email'] ?? '' ?>"></label>
        <br><br>
        <label>Пароль <input type="password" name="password" value="<?= $_POST['password'] ?? '' ?>"></label>
        <br><br>
        <input type="submit" value="Зарегистрироваться">
    </form>
</div>
