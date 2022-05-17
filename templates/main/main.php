<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?=$_MAIN_TITLE_ ?? 'Другой блог'?></title>
    <link rel="stylesheet" type="text/css" href="/static/front/styles.css"/>
</head>
<body>

<table class="layout">
    <tr>
        <td colspan="2" class="header">
            <?=$_MAIN_HEADER_ ?? 'Другой блог'?>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: right">
            <?php if(!empty($_USER_)): ?>
                Привет, <?= $_USER_->getNickname() ?>  | <a href="http://blog2/users/logOut">Выйти</a>
            <?php else: ?>
                <a href="http://blog2/users/login">Войти</a> | <a href="http://blog2/users/register">Зарегестрироваться</a>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td>
            <?=$_MAIN_ARTICLES_ ?? BLOCK_ERROR?>
        </td>

        <td width="300px" class="sidebar">
            <div class="sidebarHeader">Меню</div>
            <ul>
                <li><a href="/">Главная страница</a></li>
                <li><a href="/about-me">Обо мне</a></li>
                <li><a href="/test">TEST</a></li>
            </ul>
        </td>
    </tr>
    <tr>
        <td class="footer" colspan="2">
            <?=$_MAIN_FOOTER_ ?? 'Все права защищены (c) Другой блог'?>
        </td>
    </tr>
</table>

</body>
</html>
