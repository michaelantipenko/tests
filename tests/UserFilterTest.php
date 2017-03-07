<?php
use PHPUnit\Framework\TestCase;

/**
 * @covers UserFilter
 */
class UserFilterTest extends TestCase
{
    public function testFilterId()
    {
        $user = $this->getMockUser();
        $filter = new UserFilter();

        $filter->id = 10;
        $this->assertTrue($filter->filter($user));

        $filter->id = 15;
        $this->assertFalse($filter->filter($user));

        $filter->id = '5-15';
        $this->assertTrue($filter->filter($user));

        $filter->id = '15-20';
        $this->assertFalse($filter->filter($user));
    }

    public function testFilterAge()
    {
        $user = $this->getMockUser();
        $filter = new UserFilter();

        $filter->age = 20;
        $this->assertTrue($filter->filter($user));

        $filter->age = 27;
        $this->assertFalse($filter->filter($user));

        $filter->age = '20-27';
        $this->assertTrue($filter->filter($user));

        $filter->age = '20-24';
        $this->assertFalse($filter->filter($user));
    }

    public function testFilterEmail()
    {
        $user = $this->getMockUser();
        $filter = new UserFilter();

        $filter->email = 'test@example.com';
        $this->assertTrue($filter->filter($user));

        $filter->email = 'user@example.com';
        $this->assertFalse($filter->filter($user));
    }

    public function testFilterName()
    {
        $user = $this->getMockUser();
        $filter = new UserFilter();

        $filter->name = 'User';
        $this->assertTrue($filter->filter($user));

        $filter->name = 'User name';
        $this->assertFalse($filter->filter($user));
    }

    /**
     * @return array
     */
    private function getMockUser()
    {
        return [
            'id' => 10,
            'age' => 25,
            'email' => 'test@example.com',
            'name' => 'User',
        ];
    }
}
