<?php

use Services\Paging;

/**
 *  Тип шаблона:
 *  - FILE - шаблон - файл, его загрузка через include
 *  - STRING - шаблон - строка, его загрузка через eval
 */
enum TplType
{
    case FILE;
    case STRING;
}

/**
 * Выполняет указанный HTML-шаблон с PHP переменными
 *
 * @param string $tpl - полный путь до HTML-шаблона или строка собс-но шаблона
 * @param array $vars - массив переменных
 * @param TplType $tpltype - FILE - в $tpl - полный путь до HTML-шаблона, STRING - в $tpl - текст шаблона
 * @return string
 */
function renderTpl(string $tpl, array $vars = [], TplType $tplType = TplType::FILE) : string
{
    if ( count($vars) ) {
        extract($vars, EXTR_OVERWRITE);
    }

    ob_start();
    if ($tplType === TplType::FILE) {
        include $tpl;
    } else {
        eval( '?>' . $tpl . '<?php echo PHP_EOL;' );
    }

    return ob_get_clean();
}
/**
 * Формирует и выводит страницу с сообщением об ошибке
 *
 * @param array $params - массив общих параметров (см стр. 40-41 index.php)
 * @param string $template - полный путь до HTML-шаблона для исключения
 * @param string $errMsg - сообщение об ошибке
 * @param int $code - код ошибки для браузера
 * @return void
 */
function outException( array $params, string $template, string $errMsg, int $code = 0): void
{
    if ( $code ) {
        http_response_code($code);
    }
    $vars['_MAIN_ARTICLES_'] = renderTpl( $template, ['_ERROR_' => $errMsg]);
    $vars['_USER_'] = $params[ USER ];
    echo renderTpl( ROOT_DIR . 'templates/main/main.php', $vars);
}

/**
 * Формирует страницу в списке статей
 *
 * @param array $articles
 * @param array $params
 * @return string
 */
function getArticlrsPage( array $articles, array $params) : string
{
    $itemsPerPage =3;
    $res = '';
    $articleTpl = articleInListTpl();
    foreach ( $articles as $article) {
        $vars['_ARTICLE_ID_'] = $article['a_id'];
        $vars['_ARTICLE_NAME_'] = htmlentities( $article['name'] );
        $vars['_ARTICLE_TEXT_'] = htmlentities( $article['text']);
        $vars['_NICK_NAME_'] = htmlentities( $article['nickname'] );
        $vars['_CREATED_AT_'] = $article['created_at'];
        $res .= renderTpl( $articleTpl, $vars, TplType::STRING);
    }
//        $vars['_MAIN_ARTICLES_'] .= articlesPages( Article::getPagesCount($itemsPerPage), $params[ MATCHES ][ 1 ]);
    $res .= lotArticlesPages( Paging::getPagesCount($itemsPerPage), $params[ MATCHES ][ 1 ]);

    return $res;
}