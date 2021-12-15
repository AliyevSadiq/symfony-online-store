<?php


namespace App\Utils\FileSystem;


use Symfony\Component\Filesystem\Filesystem;

class FileSystemWorker
{

    private Filesystem $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function createFolder(string $folder)
    {
        if (!$this->filesystem->exists($folder)){
            $this->filesystem->mkdir($folder);
        }

    }

    public function remove(string $item)
    {
        if ($this->filesystem->exists($item)){
            $this->filesystem->remove($item);
        }
    }
}