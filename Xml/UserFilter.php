<?php
require_once __DIR__ . '/EntityFilter.php';

/**
 * Class UserFilter
 */
class UserFilter implements EntityFilter
{
    /**
     * @var int|string the user id.
     */
    public $id;
    /**
     * @var int|string the user age.
     */
    public $age;
    /**
     * @var string the user name.
     */
    public $name;
    /**
     * @var string the user email.
     */
    public $email;


    /**
     * This method checks have to user in the output result.
     * @param array $user
     * @return bool
     */
    public function filter($user): bool
    {
        return $this->checkId($user['id'])
            && $this->checkAge($user['age'])
            && $this->checkEmail($user['email'])
            && $this->checkName($user['name']);
    }

    /**
     * Tests user id.
     * @param string $id
     * @return bool
     */
    private function checkId(string $id): bool
    {
        return $this->rangeChecker($id, $this->id);
    }

    /**
     * Tests user age.
     * @param string $age
     * @return bool
     */
    private function checkAge(string $age): bool
    {
        return $this->rangeChecker($age, $this->age);
    }

    /**
     * Tests user name.
     * @param string $name
     * @return bool
     */
    private function checkName(string $name): bool
    {
        return $this->stringChecker($name, $this->name);
    }

    /**
     * Test user email.
     * @param string $email
     * @return bool
     */
    private function checkEmail(string $email): bool
    {
        return $this->stringChecker($email, $this->email);
    }

    /**
     * This method can checks value if it sets `25-30` or `15`.
     * If input value has dash, value will split by dash, first value will be
     * start value and second value will be end value. If end value does not exist
     * method will check test value more than start value start value.
     *
     * @param string $testValue
     * @param null|string $queryValue
     * @return bool
     */
    private function rangeChecker(string $testValue, $queryValue): bool
    {
        if ($queryValue) {
            $queryValues = explode('-', $queryValue);
            if ($queryValues[0]
                && isset($queryValues[1])
                && $testValue >= $queryValues[0]
                && $testValue <= $queryValues[1]
            ) {
                return true;
            } elseif ($testValue >= $queryValues[0] && !isset($queryValues[1])) {
                return true;
            }

            return false;
        }

        return true;
    }

    /**
     * Checks exist input value in the test value.
     * @param string $testValue
     * @param null|string $queryValue
     * @return bool
     */
    private function stringChecker(string $testValue, $queryValue): bool
    {
        if ($queryValue) {
            return mb_stripos($testValue, $queryValue) !== false;
        }

        return true;
    }
}
