<?php
namespace App\Exceptions;

/**
 * A decorator around PDOException whose getMessage function does not include the 
 * actual SQL call with parameters. This class should be used to wrap PDOExceptions
 * whose SQL calls may contain binary data that are not suitable to log or 
 * be serialized to JSON.
 */
class LoggablePDOException extends \PDOException
{
    public function __construct(\PDOException $previous)
    {
        parent::__construct('', 0, $previous);
        $this->message = $this->formatMessage($previous);
        $this->code = $previous->getCode();
        $this->errorInfo = $previous->errorInfo;
    }
    
    protected function formatMessage(\PDOException $e)
    {
        return sprintf('SQLSTATE[%s]: %s: %s', $e->errorInfo[0], $e->errorInfo[1], $e->errorInfo[2]);
    }
    
}
