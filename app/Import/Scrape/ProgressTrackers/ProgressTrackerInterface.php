<?php
namespace App\Import\Scrape\ProgressTrackers;

interface ProgressTrackerInterface
{
    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function notice($message, array $context = []);
    
    /**
     * Interesting events.
     *
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function info($message, array $context = []);
    
    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function error($message, array $context = []);
}