<?php
/**
 * Class EntityWriter
 */
class EntityWriter
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
     * @var string the full path to the file.
     */
    private $file;


    /**
     * EntityWriter constructor.
     * @param string $file
     */
    public function __construct(string $file)
    {
        $this->file = $file;
    }

    /**
     * Starts writing to the file. Before writing, it will delete previous file.
     * @return false|int
     */
    public function start()
    {
        if (file_exists($this->file)) {
            unlink($this->file);
        }

        return $this->append("<$this->rootTag>");
    }

    /**
     * Appends data to the file.
     * @param $data
     * @return int
     */
    private function append(string $data)
    {
        return file_put_contents($this->file, $data, FILE_APPEND);
    }

    /**
     * Adds new item to the file. Item must be array, before adding array will be
     * converted to the string and wrapped item tags.
     * @param array $item
     * @return string
     */
    public function addItem(array $item)
    {
        $data = "<$this->itemTag>";
        $data .= $this->normalizeItem($item);
        $data .= "</$this->itemTag>";

        return $this->append($data);
    }

    /**
     * Adds closing tag of the root.
     * @return false|int
     */
    public function end()
    {
        return $this->append("</$this->rootTag>");
    }

    /**
     * Converted item to the string. This method waits array:
     *
     * ```
     * [
     *      'id' => 10,
     *      'name' => 'User name',
     *      'age' => 25,
     * ]
     *
     * ```
     * @param array $data
     * @return string
     */
    private function normalizeItem(array $data): string
    {
        $result = [];
        foreach ($data as $name => $value) {
            $result[] = "<$name>$value</$name>";
        }

        return implode('', $result);
    }
}
