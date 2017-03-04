<?php
/**
 * Interface EntityFilter
 */
interface EntityFilter
{
    /**
     * @param $data
     * @return bool
     */
    public function filter($data): bool;
}
