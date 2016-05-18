<?php
namespace App\Import\Scrape\ProgressTrackers;

trait TracksProgress
{
    /**
     * @var array
     */
    protected $progressTrackers = [];
    
    /**
     * Attach a progress tracker
     * 
     * @param ProgressTrackerInterface $progressTracker
     * @return static
     */
    public function attachProgressTracker(ProgressTrackerInterface $progressTracker)
    {   
        $this->progressTrackers[$this->getProgressTrackerKey($progressTracker)] = $progressTracker;
        
        return $this;
    }
    
    /**
     * Detach a progress tracker
     *
     * @param ProgressTrackerInterface $progressTracker
     * @return static
     */
    public function detachProgressTracker(ProgressTrackerInterface $progressTracker)
    {   
        unset($this->progressTrackers[$this->getProgressTrackerKey($progressTracker)]);
        
        return $this;
    }
    
    /**
     * Get progress tracker
     * 
     * @param ProgressTrackerInterface $progressTracker
     * @return array
     */
    public function getProgressTrackers()
    {   
        return $this->progressTrackers;
    }
    
    /**
     * Track a normal progress
     * 
     * @param string $message
     * @param array $context
     * @param string $type
     */
    public function trackProgress($message = '', array $context = [], $type = 'notice')
    {
        foreach ($this->progressTrackers as $progressTracker) {
            $progressTracker->{$type}($message, $context);
        }
    }
    
    /**
     * Track an info progress
     * 
     * @param string $message
     * @param array $context
     * @param string $type
     */
    public function trackInfoProgress($message = '', array $context = [])
    {
        $this->trackProgress($message, $context, 'info');
    }
    
    /**
     * Track an error progress
     * 
     * @param string $message
     * @param array $context
     * @param string $type
     */
    public function trackErrorProgress($message = '', array $context = [])
    {
        $this->trackProgress($message, $context, 'error');
    }
    
    /**
     * Get progress tracker key
     * 
     * @param ProgressTrackerInterface $progressTracker
     * @return string
     */
    private function getProgressTrackerKey(ProgressTrackerInterface $progressTracker)
    {
        return spl_object_hash($progressTracker);
    }
}