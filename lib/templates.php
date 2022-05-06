<?php

/**
 * возвращает HTML-шаблон с PHP переменными для формы регистрации
 *
 * @return string
 */
function sighnUp2() : string
{
    return '
<div style="text-align: center;">
    <h1>Регистрация</h1>
    <?php if (!empty($error)): ?>
        <div style="background-color: red;padding: 5px;margin: 15px"><?= $error ?></div>
    <?php endif; ?>
    <form action="/users/register" method="post">
        <label>Nickname <input type="text" name="nickname" ?>"></label>
        <br><br>
        <label>Email <input type="text" name="email" ?>"></label>
        <br><br>
        <label>Пароль <input type="password" name="password" ?>"></label>
        <br><br>
        <input type="submit" value="Зарегистрироваться">
    </form>
</div>
';
}

/**
 * возвращает HTML-шаблон при успешной регистрации
 *
 * @return string
 */
function signUpSuccessful() : string
{
    return '
    <div style="text-align: center;">
        <h1>Регистрация прошла успешно!</h1>
        Ссылка для активации вашей учетной записи отправлена вам на email.
    </div>
';
}

/**
 * возвращает HTML-шаблон с плейсхолдерами для формы регистрации
 *
 * @return string
 */
function signUp() : string
{
    return '
<div style="text-align: center;">
    <h1>Регистрация</h1>
    %ERROR%
    <form action="/users/register" method="post">
            <label>Nickname <input type="text" name="nickname" value= %NICKNAME% ></label>
            <br><br>
            <label>Email <input type="text" name="email" value= %EMAIL% ></label>
            <br><br>
            <label>Пароль <input type="password" name="password" value= %PASSWORD% ></label>
            <br><br>
            <input type="submit" value="Зарегистрироваться">
    </form>
</div>
';
}
