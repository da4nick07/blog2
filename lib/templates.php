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
 * Возвращает блок с пролистыванием, с номерами страниц для списка статей
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

/**
 * Возвращает блок с пролистыванием (<Пред> N <След>) для списка статей
 *
 * @param int $pagesCount
 * @param int $currentPageNum
 * @return string
 */
function lotArticlesPages( int $pagesCount, int $currentPageNum) : string
{
    if ( $pagesCount === 1) {
        return '';
    }
    $res = '<div style="text-align: center">';
    if ( $currentPageNum >1) {
        $res .= '<a href="/' . ($currentPageNum -1) . '">&lt; Пред &gt</a>';
    } else {
        $res .= '<span style="color: grey">&lt; Пред &gt;</span>';
    }
    $res .= '&nbsp&nbsp<b>' . $currentPageNum . ' </b>&nbsp';
    if ( $currentPageNum === $pagesCount) {
        $res .= '<span style="color: grey">&lt; След &gt;</span>';
    } else {
        $res .= '<a href="/' . ($currentPageNum +1) . '">&lt; След &gt</a>';
    }

    $res .= '</div>';

    return $res;
}