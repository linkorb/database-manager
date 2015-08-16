<?php

namespace LinkORB\Component\DatabaseManager\Exception;

/**
 * @author Igor Mukhin <igor.mukhin@gmail.com>
 */
class ConfigNotFoundException extends RuntimeException
{
    /**
     * @param string $filename
     */
    public function __construct($filename)
    {
        parent::__construct(sprintf(
            "Configuration file '%s' not found.",
            $filename
        ));
    }
}
