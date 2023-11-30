<?php

namespace HtmlParse;

class Helper
{
    /**
     * Передаем то что надо вывести в красивом виде
     * @param $data
     * @return void
     */
    public static function dump (mixed $data)
    {
        echo "<pre>" . print_r($data, 1) . "</pre>";
    }
}