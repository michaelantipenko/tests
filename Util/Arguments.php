<?php

/**
 * Class Arguments
 */
class Arguments
{
    /**
     * @var array
     */
    private $arguments;


    /**
     * Arguments constructor.
     * @param array $arguments
     */
    public function __construct(array $arguments)
    {
        $this->arguments = $this->parseArguments($arguments);
    }

    /**
     * Returns specific argument value.
     * @param string $name
     * @return mixed|null
     */
    public function get(string $name)
    {
        return isset($this->arguments[$name]) ? $this->arguments[$name] : null;
    }

    /**
     * Method parses input $arguments.
     *
     * Input array:
     * ```
     * [
     *      1 => 'id=1-2',
     *      2 => 'age=25-30',
     * ]
     * ```
     *
     * Output array:
     *
     * ```
     * [
     *      'id' => '1-2',
     *      'age' => '25-30',
     * ]
     *
     * ```
     *
     * @param array $arguments
     * @return array
     */
    private function parseArguments(array $arguments): array
    {
        $result = [];
        foreach ($arguments as $index => $argument) {
            if ($index === 0) {
                continue;
            }

            list($name, $value) = explode('=', $argument);
            $result[$name] = $value;
        }

        return $result;
    }
}
