<?php

namespace common;

use yii\helpers\Html;
use common\models\File;

Class Util {

    public static function replaceBBCode($text_post) {
        $str_search = array(
            "#\\\n#is",
            "#\[b\](.+?)\[\/b\]#is",
            "#\[i\](.+?)\[\/i\]#is",
            "#\[u\](.+?)\[\/u\]#is",
            "#\[s\](.+?)\[\/s\]#is",
            "#\[code\](.+?)\[\/code\]#is",
            // "#\[quote\](.+?)\[\/quote\]#is",
            "#\[url=(.+?)\](.+?)\[\/url\]#is",
            "#\[url\](.+?)\[\/url\]#is",
            "#\[img\](.+?)\[\/img\]#is",
            //"#\[size=(.+?)\](.+?)\[\/size\]#is",
            "#\[color=(.+?)\](.+?)\[\/color\]#is",
            "#\[list\](.+?)\[\/list\]#is",
            "#\[listn](.+?)\[\/listn\]#is",
            "#\[\*\](.+?)\[\/\*\]#",
            "#\[h1\](.+?)\[\/h1\]#is"
        );
        $str_replace = array(
            "<br />",
            "<b>\\1</b>",
            "<i>\\1</i>",
            "<span style='text-decoration:underline'>\\1</span>",
            "<s>\\1</s>",
            "<code class='code'>\\1</code>",
            //"<table width = '95%'><tr><td>Цитата</td></tr><tr><td class='quote'>\\1</td></tr></table>",
            "<a href='$1'>\\2</a>",
            "<a href='$1'>\\1</a>",
            "<a href='\\1' class='thumbnail'><img height='10%' src='\\1' alt = 'Image' /></a>",
            // "<span style='font-size:\\1%'>\\2</span>",
            "<span style='color:\\1'>\\2</span>",
            "<ul>\\1</ul>",
            "<ol>\\1</ol>",
            "<li>\\1</li>",
            "<h1>\\</h1>"
        );
        return preg_replace($str_search, $str_replace, $text_post);
    }

    public static function iconLink($iconName, $text, $to) {
        return '<li>' . Html::a('<i class="glyphicon glyphicon-' . $iconName . '"></i> ' . $text, $to) . '</li>';
    }

}
