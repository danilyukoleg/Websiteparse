<?php
namespace HtmlParse;
require_once "./vendor/autoload.php";
use DiDom\Document;

class Page
{
    private $url;
    private $resDocument;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public  function getUrl()
    {
        return $this->url;
    }
    public function getPage()
    {
        return $this->resDocument;
    }

    public function getNormilizePage()
    {
        $curlResult = $this->getCurl();


        $document = new Document($curlResult);

        $this->normalizeUrl($document->find("a"), $this->url, "href");
        $this->normalizeUrl($document->find("img"), $this->url, "src");
        $this->normalizeUrl($document->find("script"), $this->url, "src");
        $this->normalizeUrl($document->find("audio"), $this->url, "src");
        $this->normalizeUrl($document->find("audio source"), $this->url, "src");
        $this->normalizeUrl($document->find("video source"), $this->url, "src");
        $this->resDocument = $document->html();


        return $this;
    }

    public function getHrefs($href, $selector = null )
    {
        $document = new Document($this->resDocument);
        $selector = !empty($selector) ? $selector : 'a';
        $links = $document->find($selector);
        $resHref = [];
        foreach ($links as $link) {
            array_push($resHref ,$link->attr($href));
        }

        return $resHref;
    }

    private function normalizeUrl($tags, $path, $link)
    {

        $pathUrl = parse_url($path);

        foreach ($tags as $tag) {
            $attrValue = $tag->attr($link);
            $newLink = null;

            if ($attrValue !== null && $path !== null) {
                if (preg_match("#^https?://" . $pathUrl['host'] . "#", $attrValue)) {
                    $newLink = $path . substr($attrValue, strlen($pathUrl['host']));
                } elseif (preg_match("#\/\d\/+#", $path)) {
                    $newLink = $path;
                } elseif (preg_match("#^\.?\.?/#", $attrValue)) {
                    $newLink = $pathUrl['scheme'] . '://' . $pathUrl['host'] .
                        rtrim(dirname($pathUrl['path']), '/') .
                        '/' . ltrim($attrValue, './');
                } else {
                    $newLink = $path . $attrValue;
                }
            } else {
                $newLink = "";
            }
            $tag->setAttribute($link, $newLink);
        }
    }

    private function getCurl()
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $cookieFilePath = $_SERVER['DOCUMENT_ROOT'] . 'cookie.txt';

        curl_setopt($curl, CURLOPT_COOKIEFILE, $cookieFilePath);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookieFilePath);

        $result = curl_exec($curl);

        return $result;
    }
}