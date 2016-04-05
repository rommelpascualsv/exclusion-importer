<?php namespace App\Common\Logger;

interface ExceptionLoggerInterface 
{
    /**
     * Write the exception data to the log file
     *
     * @param \Exception $e
     * @param   string $path
     * @return mixed
     * @internal param string $content
     * @internal param bool $append
     */
    function logMessage(\Exception $e, $path);

    /**
     * Log the exception with a stack trace
     *
     * @param	\Exception	$e
     * @param	string		$path
     * @return	bool
     */
    function logStackTrace(\Exception $e, $path);
}
