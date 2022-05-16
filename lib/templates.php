<?php

/**
 * возвращает HTML-шаблон с плейсхолдерами для статьи
 *
 * @return string
 */
function oneArticle() : string
{
//    <a href="/about-me">Обо мне</a>
    return '
        <h2><a href="/articles/%ARTICLE_ID% "> %ARTICLE_NAME% </a></h2>
        <p>%ARTICLE_TEXT%</p>
        <p>Автор: <i>%NICK_NAME%</i></p>
        <hr>
        ';
}