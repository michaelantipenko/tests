<?php

/**
 * Class EntityReader
 */
class EntityReader
{
    /**
     * @var string the name of the root element.
     */
    public $rootTag = 'items';
    /**
     * @var string the name of the each element.
     */
    public $itemTag = 'item';
    /**
     * @var array the tags need parse and add to the output.
     */
    public $tags = [];
    /**
     * @var string the full path to the file.
     */
    private $file;


    /**
     * XmlEntityReader constructor.
     * @param string $file
     */
    public function __construct(string $file)
    {
        $this->file = $file;
    }

    /**
     * Reads file and returns items one by one.
     * @return Generator
     */
    public function read()
    {
        $handle = fopen($this->file, 'r');
        $buffer = ltrim(fgets($handle, 4096));
        $buffer = str_ireplace('<' . $this->rootTag . '>', '', $buffer);

        while (!feof($handle)) {
            $buffer = $buffer . fgets($handle, 4096);
            while (($startPosition = mb_stripos($buffer, "</$this->itemTag>")) !== false) {
                $lengthTag = mb_strlen("<$this->itemTag>");
                $endPosition = $startPosition + $lengthTag + 1;
                $item = mb_substr($buffer, $lengthTag, $startPosition - $lengthTag);
                $buffer = mb_substr($buffer, $endPosition);

                yield $this->parseItem($item);
            }
        }
        fclose($handle);
    }

    /**
     * Parse item string to array.
     * @param string $item
     * @return array
     */
    private function parseItem(string $item): array
    {
        $result = [];
        foreach ($this->tags as $tag) {
            $result[$tag] = $this->parseTag($tag, $item);
        }

        return $result;
    }

    /**
     * Find tag value in the string.
     * @param string $name
     * @param string $string
     * @return string
     */
    private function parseTag(string $name, string $string): string
    {
        preg_match("/<$name>(.*)?<\/$name>/", $string, $matches);
        return $matches[1];
    }
}
