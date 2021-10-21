<?php

declare(strict_types=1);

namespace CDS\Modules\Users;

class ValidationException extends \Exception
{
    /**
     * Encode JSON message to a string the message and calls the parent constructor.
     *
     * @param array          $message
     * @param int            $code
     * @param Exception|null $previous
     */
    public function __construct(array $message = [], $code = 0, Exception $previous = null)
    {
        parent::__construct(json_encode($message), $code, $previous);
    }

    /**
     * Returns the json decoded message.
     *
     * @param bool $assoc
     *
     * @return mixed
     */
    public function decodeMessage($assoc = false)
    {
        return json_decode($this->getMessage(), $assoc);
    }
}
