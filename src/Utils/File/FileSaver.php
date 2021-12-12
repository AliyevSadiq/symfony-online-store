<?php


namespace App\Utils\File;


use App\Utils\FileSystem\FileSystemWorker;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileSaver
{

    private SluggerInterface $slugger;
    private string $uploadsTempDir;
    private FileSystemWorker $fileSystemWorker;

    public function __construct(SluggerInterface $slugger,string $uploadsTempDir,FileSystemWorker $fileSystemWorker)
    {
        $this->slugger = $slugger;
        $this->uploadsTempDir = $uploadsTempDir;
        $this->fileSystemWorker = $fileSystemWorker;
    }

    public function saveUploadInTempFile(UploadedFile $uploadedFile): string
    {
        $originalFileName=pathinfo($uploadedFile->getClientOriginalName(),PATHINFO_FILENAME);
        $saveFileName=$this->slugger->slug($originalFileName);

        $fileName=sprintf("%s-%s.%s",$saveFileName,uniqid(),$uploadedFile->getClientOriginalExtension());

        $this->fileSystemWorker->createFolder($this->uploadsTempDir);

        try {
            $uploadedFile->move($this->uploadsTempDir,$fileName);
        }catch (FileException $exception){
            dd($exception->getMessage());
        }
        return $fileName;
    }


}