<?php

/**
 * возвращает HTML-шаблон с плейсхолдерами для статьи
 *
 * @return string
 */
function articleInListTpl() : string
{
    return '
        <h2><a href="/articles/%ARTICLE_ID% "> %ARTICLE_NAME% </a></h2>
        <p>%ARTICLE_TEXT%</p>
        <p>Автор: <i>%NICK_NAME%</i></p>
        <hr>
        ';
}

/**
 * Возвращает блок с номерами страниц для списка статей
 *
 * @param int $pagesCount
 * @param int $currentPageNum
 * @return string
 */
function articlesPages( int $pagesCount, int $currentPageNum) : string
{
    if ( $pagesCount === 1) {
        return '';
    }
    $res = '<div style="text-align: center">';
//    $res .= '<p>Всего страниц ' . $pagesCount . ' </p>';
    for ($pageNum = 1; $pageNum <= $pagesCount; $pageNum++) {
        if ($currentPageNum === $pageNum) {
            $res .= '<b>' . $pageNum . '</b>';
        } else {
            $res .= '<a href="/' .$pageNum . '">' . $pageNum . '</a>';
        }
        $res .= '&nbsp&nbsp';
    }


    $res .= '</div>';

    return $res;
}