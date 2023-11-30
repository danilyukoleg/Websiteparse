<?php

namespace HtmlParse;

class Helper
{
    /**
     * Передаем то что надо вывести в красивом виде
     * @param mixed $data
     * @return void
     */
    public static function dump (mixed $data)
    {
        echo "<pre>" . print_r($data, 1) . "</pre>";
    }

    /**
     * @param array $data
     * @param string $template
     * @return string
     */
    public static function setTemplate(array $data, string $template):string
    {

        $tpl = file_get_contents($template);

        $nameFunc = [];
        $valueFunc = [];

        foreach ($data as $key => $value) {
            array_push($nameFunc, "{{ $key }}");
            array_push($valueFunc, $value);
        }

        return str_replace([...$nameFunc], [...$valueFunc], $tpl);
    }

    public static function getReplaceCode($text)
    {
        $nameCode = [
            '<code>' => '⁅c⁆',
            '</code>' => '⁅/c⁆',
            '<code class="literal">' => '⁅c⁆',
        ];

        return str_replace(array_keys($nameCode), array_values($nameCode), $text);
    }

    public static function getTranslit($value)

    {
        $ru = ['а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я', ' '];

        $en = ['a', 'b', 'v', 'g', 'd', 'e', 'yo', 'zh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'ts', 'ch', 'sh', 'ch', '', 'y', '', 'e', 'yu', 'ya', '_'];



        $value = str_replace($ru, $en, strtolower($value));

        return strtolower($value);

    }

}