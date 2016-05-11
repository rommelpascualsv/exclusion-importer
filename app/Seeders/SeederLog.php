<?php namespace App\Seeders;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class SeederLog
{
    protected $logger;

    public function __construct()
    {
        $name = 'seeder-log';
        $this->logger = new Logger($name);
        $logFile = storage_path('logs/' . $name . '-' . date("Y-m-d_H-i-s") . '.log');
        $this->logger->pushHandler(new StreamHandler($logFile, Logger::ERROR));
    }

    public function error($row, $database, $type)
    {
        $this->logger->addError('Error parsing a ' . $type . ' row for ' . $database, ['row' => implode(', ', $row)]);
    }
}
