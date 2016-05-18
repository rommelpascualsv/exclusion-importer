<?php
namespace App\Import\Scrape\ProgressTrackers;

use Illuminate\Console\Command;

class CliProgressTracker implements ProgressTrackerInterface
{
    /**
     * Instantiate
     * 
     * @param Command $command
     */
    public function __construct(Command $command)
    {
        $this->command = $command;
    }
    
    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function notice($message, array $context = [])
    {
        $this->command->line($message);
    }
    
    /**
     * Interesting events.
     *
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function info($message, array $context = [])
    {
        $this->command->info($message);
    }
    
    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function error($message, array $context = [])
    {
        $this->command->error($message);
    }
}