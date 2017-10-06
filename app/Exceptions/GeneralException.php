<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Class GeneralException
 * @package App\Exceptions
 */
class GeneralException extends RuntimeException{

    /**
     * @var string
     */
    public $errorMessage;

    /**
     * GeneralException constructor.
     * @param string $errorMessage
     */
    public function __construct($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }

}