<?php

namespace LinkORB\Component\DatabaseManager\Exception;

/**
 * @author Igor Mukhin <igor.mukhin@gmail.com>
 */
class InvalidDatabaseException extends RuntimeException
{
    /**
     * @param string $dbname
     */
    public function __construct($dbname)
    {
        parent::__construct(sprintf(
            "Database '%s' doesn't exist",
            $dbname
        ));
    }
}
