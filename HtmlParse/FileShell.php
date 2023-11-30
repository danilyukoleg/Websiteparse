<?php

namespace HtmlParse;

class FileShell
{
    private $nameDir;
    private $nameFile;
    public function __construct($nameDir, $nameFile)
    {
        $this->nameDir = $nameDir;
        $this->nameFile =  $this->nameDir . '/' .$nameFile;
    }

    public function setFile($text)
    {
        if(!is_dir($this->nameDir)) {
            mkdir($this->nameDir);

            file_put_contents($this->nameFile, $text);
        }

        return $text;
    }

}