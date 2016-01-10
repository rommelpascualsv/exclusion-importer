<?php namespace App\Common\Logger;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use \Exception;


class ExceptionLogger implements ExceptionLoggerInterface {


    /**
     * @var \League\Flysystem\Filesystem  $filesystem
     */
    private $filesystem;


    /**
     * Constructor
     *
     * @param Filesystem|FilesystemInterface $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }


	/**
	 * Log a formatted exception message
	 *
	 * @param	Exception	$e
	 * @param	string		$path
	 * @return	mixed
	 */
	public function logMessage(Exception $e, $path)
	{
		$timestamp	= strftime('%Y-%m-%d %H:%M:%S');
		$message	= $e->getMessage();
		$filename	= $e->getFile();
		$line		= $e->getLine();

		$message = sprintf("%s -- %s in %s : [ %s ]", $timestamp, $message, $filename, $line);

        $config = $this->filesystem
            ->getConfig()
            ->get('options', []);

		return $this->write($path, $message . PHP_EOL, true, $config);
	}


	/**
	 * Log the exception with a stack trace
	 *
	 * @param	Exception	$e
	 * @param	string		$path
	 * @return	bool
	 */
	public function logStackTrace(Exception $e, $path)
	{
        $content = strftime('%Y-%m-%d %H:%M:%S');
		$content .= $e->__toString();

        $config = $this->filesystem
            ->getConfig()
            ->get('options', []);

        return $this->write($path, $content, true, $config);
	}


    /**
     * Write the exception data to the log file
     *
     * @param   string $path
     * @param   string $content
     * @param   bool $append
     * @param array $config
     * @return  mixed
     */
    private function write($path, $content, $append = false, $config = [])
    {
		if ( ! $append)
			return $this->filesystem->put($path, $content, $config);

		if ($this->filesystem->has($path))
		{
			$content = $this->filesystem->read($path) . $content;
		}

		return $this->filesystem->put($path, $content, $config);
    }

}
