<?php 

namespace App\Utils;

class ExceptionUtils
{
    /**
     * Returns a JSON-serializable error message derived from the given PDOException
     * @param \PDOException $e
     */
    public static function getJsonSerializableErrorMessage(\PDOException $e)
    {
        return sprintf('SQLSTATE[%s]: %s: %s', $e->errorInfo[0], $e->errorInfo[1], $e->errorInfo[2]);
    }
    
}
