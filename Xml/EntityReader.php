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
        $buffer = preg_replace("/<$this->rootTag?[^\>]+>/", '', $buffer, 1);

        while (!feof($handle)) {
            $buffer = $buffer . fgets($handle, 4096);
            while (($startPosition = mb_stripos($buffer, "</$this->itemTag>")) !== false) {
                $tag = $this->parseTag($this->itemTag, $buffer, true);
                $buffer = str_replace($tag, '', $buffer);

                yield $this->parseItem($tag);
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
     * @param bool $fullMatch
     * @return string
     */
    private function parseTag(string $name, string $string, $fullMatch = false): string
    {
        // preg_match("/<$name?[^\>]+>(.*?)<\/$name>/", $string, $matches);
        preg_match("/<$name\s*>(.*?)<\/$name\s*>/", $string, $matches);
        return (string)($fullMatch ? $matches[0] : $matches[1]);
    }
}
