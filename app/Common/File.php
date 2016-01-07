<?php namespace App\Common;

class File extends \SplFileObject
{
    public function csvlineIterator($total)
    {
        for ($i = 0; $i < $total; $i++)
        {
            if (empty($line = $this->fgetcsv())) {
                continue;
            }
            yield $line;
        }
    }

    public function getTotalLines()
    {
        $originalPosition = $this->key();

        // Need to do this crazy hack because fseek(0, SEEK_END) doesn't work!
        // We pass in the size so that even if all lines are blank we reach the end of the file
        $this->seek($this->getSize());
        $lastLineNumber = $this->key();
        $this->seek($originalPosition);

        return $lastLineNumber;
    }
} 