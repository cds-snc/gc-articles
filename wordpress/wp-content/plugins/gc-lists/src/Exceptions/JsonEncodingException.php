<?php

declare(strict_types=1);

namespace GCLists\Exceptions;

use RuntimeException;

class JsonEncodingException extends RuntimeException
{
    /**
     * Create a new JSON encoding exception for the model.
     *
     * @param  mixed  $model
     * @param  string  $message
     * @return static
     */
    public static function forModel($model, $message)
    {
        return new static('Error encoding model [' . get_class($model) . '] with ID [' . $model->id . '] to JSON: ' . $message);
    }
}
