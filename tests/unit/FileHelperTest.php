<?php

namespace Test\Unit;

use App\Repositories\FileRepository;
use App\Services\FileHelper;
use CDM\Test\TestCase;
use Mockery;


class FileHelperTest extends TestCase
{
    private $helper;
    private $fileRepo;

    public function setUp()
    {
        parent::setUp();

        $this->fileRepo = Mockery::mock(FileRepository::class);

        $this->helper = new FileHelper($this->fileRepo);

    }

    public function testCreateAndSaveFileHashShouldAddHashToTheFilesRepositoryIfHashDoesNotExistInFilesRepository()
    {
        $file = base_path('tests/unit/files/tn1-0.pdf');

        $hash = hash_file(FileHelper::FILE_HASH_ALGO, $file);

        $record = [
            'state_prefix' => 'tn1',
            'hash' => $hash,
            'img_type' => 'pdf'
        ];

        $this->fileRepo->shouldReceive('contains')->once()->with($record)->andReturnFalse();

        $this->fileRepo->shouldReceive('create')->once()->withArgs(function($arg1) use ($hash) {
            return $arg1['state_prefix'] === 'tn1'
                && $arg1['hash'] === $hash
                && $arg1['img_type'] === 'pdf'
                && ! empty($arg1['date_last_downloaded']);
        });

        $this->fileRepo->shouldNotReceive('update');

        $actual = $this->helper->createAndSaveFileHash($file, 'pdf', 'tn1');

        $this->assertEquals($hash, $actual);

    }

    public function testCreateAndSaveFileHashShouldNotAddHashToTheFilesRepositoryIfHashAlreadyExistsInTheFilesRepository()
    {
        $file = base_path('tests/unit/files/tn1-0.pdf');

        $hash = hash_file(FileHelper::FILE_HASH_ALGO, $file);

        $record = [
            'state_prefix' => 'tn1',
            'hash' => $hash,
            'img_type' => 'pdf'
        ];

        $this->fileRepo->shouldReceive('contains')->once()->with($record)->andReturnTrue();

        $this->fileRepo->shouldNotReceive('create');

        $this->fileRepo->shouldReceive('update')->once()->withArgs(function($arg1, $arg2) use ($record){
            return $arg1 == $record && ! empty($arg2['date_last_downloaded']);
        });

        $actual = $this->helper->createAndSaveFileHash($file, 'pdf', 'tn1');

        $this->assertEquals($hash, $actual);

    }

    public function testCreateAndSaveFileHashShouldCompareContentsOfIncomingZipFileWithLatestContentsFromFileRepoAndAddTheIncomingFileHashIfTheContentsAreNotTheSame()
    {
        $incomingZipFile = null;
        $repoZipFile = null;

        try {

            $file1 = base_path('tests/unit/files/tn1-0.pdf');
            $file2 = base_path('tests/unit/files/tn1-0-dummy.pdf');
            $incomingZipFile = tempnam('tests/unit/files', 'tn1');
            create_zip([$file1, $file2], $incomingZipFile, true);

            $repoZipFile = tempnam('tests/unit/files', 'tn1');
            create_zip([$file1], $repoZipFile, true);

            $incomingHash = hash_file(FileHelper::FILE_HASH_ALGO, $incomingZipFile);

            $repoHash = hash_file(FileHelper::FILE_HASH_ALGO, $repoZipFile);

            // For zip files, helper should fetch the latest file record and check if the file content is the same as the incoming zip file
            $this->fileRepo->shouldReceive('getFilesForPrefix')->once()->with('tn1')->andReturn([(object)[
                'state_prefix' => 'tn1',
                'img_data' => file_get_contents($repoZipFile),
                'hash' => $repoHash,
                'img_type' => 'zip'
            ]]);

            $this->fileRepo->shouldReceive('contains')->once()->with([
                'state_prefix' => 'tn1',
                'hash' => $incomingHash,
                'img_type' => 'zip'
            ])->andReturn(false);

            // Hash for zip file should be inserted since it does not yet exist
            $this->fileRepo->shouldReceive('create')->once()->withArgs(function($args) use ($incomingHash){
                return $args['state_prefix'] === 'tn1'
                    && $args['hash'] === $incomingHash
                    && ! empty($args['date_last_downloaded']);
            });

            $this->fileRepo->shouldNotReceive('update');

            $actual = $this->helper->createAndSaveFileHash($incomingZipFile, 'zip', 'tn1');

            $this->assertEquals($incomingHash, $actual);

        } finally {

            if ($incomingZipFile) unlink($incomingZipFile);
            if ($repoZipFile) unlink($repoZipFile);
        }

    }

    public function testCreateAndSaveFileHashShouldCompareContentsOfIncomingZipFileWithLatestContentsFromFileRepoAndNotAddTheIncomingFileHashIfTheContentsAreTheSame()
    {
        $incomingZipFile = null;
        $repoZipFile = null;

        try {

            $file1 = base_path('tests/unit/files/tn1-0.pdf');
            $file2 = base_path('tests/unit/files/tn1-0-dummy.pdf');
            $incomingZipFile = tempnam('tests/unit/files', 'tn1');

            create_zip([$file1, $file2], $incomingZipFile, true);

            $repoZipFile = tempnam('tests/unit/files', 'tn1');
            create_zip([$file1, $file2], $repoZipFile, true);

            $repoHash = hash_file(FileHelper::FILE_HASH_ALGO, $repoZipFile);

            $this->fileRepo->shouldReceive('getFilesForPrefix')->once()->with('tn1')->andReturn([(object)[
                'state_prefix' => 'tn1',
                'img_data' => file_get_contents($repoZipFile),
                'hash' => $repoHash,
                'img_type' => 'zip'
            ]]);

            $this->fileRepo->shouldReceive('contains')->once()->with([
                'state_prefix' => 'tn1',
                'hash' => $repoHash,
                'img_type' => 'zip'
            ])->andReturnTrue();

            $this->fileRepo->shouldNotReceive('create');

            $this->fileRepo->shouldReceive('update')->once()->withArgs(function($arg1, $arg2) use ($repoHash){
                return $arg1['state_prefix'] === 'tn1'
                    && $arg1['hash'] === $repoHash
                    && $arg1['img_type'] === 'zip'
                    && ! empty($arg2['date_last_downloaded']);
            });

            $actual = $this->helper->createAndSaveFileHash($incomingZipFile, 'zip', 'tn1');

            $this->assertEquals($repoHash, $actual);

        } finally {

            if ($incomingZipFile) unlink($incomingZipFile);
            if ($repoZipFile) unlink($repoZipFile);
        }

    }

    public function testSaveFileContentsShouldUpdateFileContentsOfRepositoryIfFileContentsInRepositoryIsNotEqualToIncomingFile()
    {
        $incomingFile = base_path('tests/unit/files/tn1-0.pdf');
        $repoFile = file_get_contents('tests/unit/files/tn1-0-dummy.pdf');

        $hash = hash_file(FileHelper::FILE_HASH_ALGO, $incomingFile);

        $this->fileRepo->shouldReceive('getFilesForPrefixAndHash')->once()->with('tn1', $hash)->andReturn([(object)[
            'state_prefix' => 'tn1',
            'img_data' => $repoFile,
            'hash' => $hash,
            'img_type' => 'pdf'
        ]]);

        $this->fileRepo->shouldReceive('update')->once()->with([
            'state_prefix' => 'tn1',
            'hash' => $hash
        ],[
            'img_data' => file_get_contents($incomingFile)
        ]);

        $this->helper->saveFileContents($incomingFile, $hash, 'tn1');
    }

    public function testSaveFileContentsShouldNotUpdateFileContentsOfRepositoryIfFileContentsInRepositoryIsEqualToIncomingFile()
    {
        $incomingFile = base_path('tests/unit/files/tn1-0.pdf');
        $repoFile = file_get_contents('tests/unit/files/tn1-0.pdf');

        $hash = hash_file(FileHelper::FILE_HASH_ALGO, $incomingFile);

        $this->fileRepo->shouldReceive('getFilesForPrefixAndHash')->once()->with('tn1', $hash)->andReturn([(object)[
            'state_prefix' => 'tn1',
            'img_data' => $repoFile,
            'hash' => $hash,
            'img_type' => 'pdf'
        ]]);

        $this->fileRepo->shouldNotReceive('update');

        $this->helper->saveFileContents($incomingFile, $hash, 'tn1');
    }
}
