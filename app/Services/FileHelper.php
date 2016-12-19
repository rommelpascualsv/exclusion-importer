<?php

namespace App\Services;


use App\Repositories\FileRepository;

class FileHelper
{
    const FILE_HASH_ALGO = 'sha256';

    public function __construct(FileRepository $fileRepo)
    {
        $this->fileRepo = $fileRepo;
    }

    /**
     * If there are multiple files specified by $files, generates a single zip archive of the files contained by $files and
     * returns the path to the generated zip archive.Otherwise, if there is only element contained by $files, returns
     * the value of that element.
     * @param array $files list of file paths
     * @throws \Exception
     * @return string the path to the zip archive if there are multiple files specified in $files, or the value of the
     * first element if there is only one element
     */
    public function zipMultiple($files)
    {
        if (! $files) {
            return null;
        }

        $result = null;

        if (count($files) > 1) {

            $result = tempnam(sys_get_temp_dir(), str_random(4));

            $zipped = create_zip($files, $result, true);

            if (! $zipped) {
                throw new \Exception('An error occurred while creating zip archive from files');
            }

        } else {
            $result = $files[0];
        }

        return $result;

    }

    /**
     * Creates a hash of the file and saves it in the files repository, if a record with the given file type and prefix
     * does not yet exist in the repository.
     *
     * @param string $file the path to the file whose hash will be generated and saved in the file repository
     * @param string $fileType the file type to associate with the generated file hash
     * @param string $prefix the state prefix to associate with the generated file hash
     * @return null|string the generated hash of the file, or null
     */
    public function createAndSaveFileHash($file, $fileType, $prefix)
    {
        if (! $file) {
            return null;
        }

        $hash = null;

        if ($fileType === 'zip') {
            // For zip files, we cannot rely on the hash of the zip file
            // to determine if we need to insert a new hash in the database (since
            // zip files having the same content can have different hashes
            // due to creation timestamp differences). We first have to retrieve the latest
            // file in the repo and compare its content with the downloaded zip file to
            // see if their contents are the same. If the contents are the same,
            // we return the hash of what's already in the database, otherwise we
            // save the hash of the downloaded file in the database
            $latestRepoFile = $this->getLatestRepoFileFor($prefix);

            if ($latestRepoFile && $this->contentEquals($latestRepoFile->img_data, $file)) {
                $hash = $latestRepoFile->hash;
            }
        }

        if (! $hash) {
            $hash = hash_file(self::FILE_HASH_ALGO, $file);
        }

        $record = [
            'state_prefix' => $prefix,
            'hash' => $hash,
            'img_type' => $fileType
        ];

        // Insert a new record in the files repository if it does not yet contain
        // a hash for the given file prefix and file index, otherwise we don't need
        // to insert it in the files repository if a hash already exists
        if (! $this->fileRepo->contains($record)) {

            info('Inserting new file hash to the files repository for \''. $prefix .'\' : ' . $hash);

            $record['date_last_downloaded'] = $this->now();

            $this->fileRepo->create($record);

        } else {

            $this->fileRepo->update($record, ['date_last_downloaded' => $this->now()]);

            info('Existing file hash found in files repository for \''. $prefix .'\' : ' . $hash);
        }

        return $hash;
    }

    /**
     * Updates an existing record with the given hash and prefix in the files repository with the contents of the
     * given file
     *
     * @param string $file the file whose contents will be persisted to the files repository
     * @param string $hash a hash in the files repository with which to associate the file contents
     * @param string $prefix a prefix in the files repository with which to associate the file contents
     * @return bool true if the record with the given prefix and hash was updated with the contents of the file. Otherwise
     * returns false indicating that the record already has content that is the same as the contents of the file.
     * @throws \Exception
     */
    public function saveFileContents($file, $hash, $prefix)
    {
        if (! $file) {
            return false;
        }

        $record = [
            'state_prefix' => $prefix,
            'hash' => $hash
        ];

        $existing = $this->fileRepo->getFilesForPrefixAndHash($prefix, $hash);

        if (! $existing) {
            throw new \Exception('Illegal state encountered : No existing record in files repository was found to update with file contents');
        }

        if (! $this->contentEquals($existing[0]->img_data, $file)) {

            info('Updating file content for hash : ' . $hash);

            $this->fileRepo->update($record, ['img_data' => file_get_contents($file)]);

            return true;

        } else {

            info('Last saved file is already up-to-date for hash : ' . $hash);
            return false;
        }

    }

    private function getLatestRepoFileFor($prefix)
    {
        $files = $this->fileRepo->getFilesForPrefix($prefix);

        return $files ? $files[0] : null;
    }

    /**
     * Compares $content with the contents of $file
     *
     * @param string $content the string content to compare with the contents of the file
     * @param string $file the file path
     * @return boolean
     */
    private function contentEquals($content, $file)
    {
        $contentFile = null;

        try {
            // Load $contents in a temp file and compare that with $file
            $contentFile = tempnam(sys_get_temp_dir(), str_random(4));

            file_put_contents($contentFile, $content, LOCK_EX);

            return file_content_equals($contentFile, $file);

        } finally {
            if ($contentFile) unlink($contentFile);
        }
    }

    private function now()
    {
        return date('Y-m-d H:i:s');
    }
}
