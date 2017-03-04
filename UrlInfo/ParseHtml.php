<?php

/**
 * Class ParseHtml
 */
class ParseHtml
{
    /**
     * @var string the html.
     */
    private $content;


    /**
     * ParseHtml constructor.
     * @param string $content
     */
    public function __construct(string $content)
    {
        $this->content = $content;
    }

    /**
     * Parse html and returns resource array.
     * @return array
     */
    public function parse()
    {
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML($this->content);

        return array_merge($this->parseImages($dom), $this->parseScripts($dom), $this->parseStyles($dom));
    }

    /**
     * Parse images from dom.
     * @param DOMDocument $dom
     * @return array
     */
    private function parseImages(DOMDocument $dom)
    {
        return $this->parseTags($dom, 'img', 'src');
    }

    /**
     * Parse scripts from dom.
     * @param DOMDocument $dom
     * @return array
     */
    private function parseScripts(DOMDocument $dom)
    {
        return $this->parseTags($dom, 'script', 'src');
    }

    /**
     * Parse styles from dom.
     * @param DOMDocument $dom
     * @return array
     */
    private function parseStyles(DOMDocument $dom)
    {
        return $this->parseTags($dom, 'link', 'href');
    }

    /**
     * Parse any tag by it name. And returns the tag attribute value.
     * @param DOMDocument $dom
     * @param string $tagName
     * @param string $attributeName
     * @return array
     */
    private function parseTags(DOMDocument $dom, string $tagName, string $attributeName): array
    {
        $result = [];
        $tags = $dom->getElementsByTagName($tagName);
        foreach ($tags as $tag) {
            /* @var $tag DOMElement */
            $value = null;
            foreach ($tag->attributes as $attribute) {
                /* @var $attribute DOMAttr */
                if ($attribute->name === $attributeName) {
                    $value = $attribute->value;
                }
            }

            if ($value) {
                $result[] = $value;
            }
        }

        return $result;
    }
}
