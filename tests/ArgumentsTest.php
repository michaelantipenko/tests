<?php
use PHPUnit\Framework\TestCase;

/**
 * @covers Arguments
 */
class ArgumentsTest extends TestCase
{
    public function testArguments()
    {
        $arguments = new Arguments($this->getMockArguments());

        $this->assertEquals($arguments->get('file'), 'users.xml');
        $this->assertEquals($arguments->get('url'), 'https://github.com');
        $this->assertEquals($arguments->get('age'), '25-30');
    }

    /**
     * @return array
     */
    private function getMockArguments()
    {
        return [
            null,
            'file=users.xml',
            'url=https://github.com',
            'age=25-30',
        ];
    }
}
