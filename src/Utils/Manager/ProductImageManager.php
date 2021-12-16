<?php


namespace App\Utils\Manager;


use App\Entity\ProductImage;
use App\Utils\File\ImageResizer;
use App\Utils\FileSystem\FileSystemWorker;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class ProductImageManager extends AbstractBaseManager
{

    private FileSystemWorker $fileSystemWorker;
    private string $uploadsTempDir;
    private ImageResizer $imageResizer;

    public function __construct(EntityManagerInterface $entityManager, FileSystemWorker $fileSystemWorker, ImageResizer $imageResizer, string $uploadsTempDir  )
    {
        parent::__construct($entityManager);

        $this->fileSystemWorker = $fileSystemWorker;
        $this->uploadsTempDir = $uploadsTempDir;
        $this->imageResizer = $imageResizer;
    }

    /**
     * @return ObjectRepository
     */
    public function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(ProductImage::class);
    }

    /**
     * @param string $productDir
     * @param string|null $tempImageFileName
     * @return ProductImage|null
     */
    public function saveImageForProduct(string $productDir, ?string $tempImageFileName=null): ?ProductImage
    {
        if (!$tempImageFileName){
            return null;
        }
        $this->fileSystemWorker->createFolder($productDir);


        $imageSmallParams=$this->imageParams($productDir,'small',60);
        $imageMiddleParams=$this->imageParams($productDir,'middle',430);
        $imageBigParams=$this->imageParams($productDir,'big',800);


        $imageSmall=$this->imageResizer->resizeImageAndSave($this->uploadsTempDir,$tempImageFileName,$imageSmallParams);
        $imageMiddle=$this->imageResizer->resizeImageAndSave($this->uploadsTempDir,$tempImageFileName,$imageMiddleParams);
        $imageBig=$this->imageResizer->resizeImageAndSave($this->uploadsTempDir,$tempImageFileName,$imageBigParams);



        $productImage=new ProductImage();
        $productImage->setFilenameSmall($imageSmall);
        $productImage->setFilenameMiddle($imageMiddle);
        $productImage->setFilenameBig($imageBig);

        return $productImage;
    }



    private function imageParams(string $productDir,string $type,int $width,?int $height=null): array
    {
        $fileNameId=uniqid();
        return [
            'width'=>$width,
            'height'=>$height,
            'newFolder'=>$productDir,
            'newFilename'=>sprintf('%s_%s.jpg',$fileNameId,$type)
        ];
    }

    public function removeImageFromProduct(ProductImage $productImage,string $imageDir)
    {
        $smallFilePath=$imageDir.'/'.$productImage->getFilenameSmall();

        $this->fileSystemWorker->remove($smallFilePath);

        $middleFilePath=$imageDir.'/'.$productImage->getFilenameMiddle();

        $this->fileSystemWorker->remove($middleFilePath);

        $bigFilePath=$imageDir.'/'.$productImage->getFilenameBig();

        $this->fileSystemWorker->remove($bigFilePath);

        $product=$productImage->getProduct();

        $product->removeProductImage($productImage);

        $this->entityManager->flush();
    }
}