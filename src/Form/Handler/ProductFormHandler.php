<?php


namespace App\Form\Handler;


use App\Entity\Product;
use App\Utils\File\FileSaver;
use App\Utils\Manager\ProductManager;
use Symfony\Component\Form\FormInterface;

class ProductFormHandler
{


    private FileSaver $fileSaver;
    private ProductManager $productManager;

    public function __construct(ProductManager $productManager, FileSaver $fileSaver)
    {
        $this->fileSaver = $fileSaver;
        $this->productManager = $productManager;
    }

    public function editProcessForm(Product $product,FormInterface $form): Product
    {
        $this->productManager->save($product);

        $newImageFile=$form->get('newImage')->getData();

        $tempImageFilename=$newImageFile ? $this->fileSaver->saveUploadInTempFile($newImageFile) : null;

        $this->productManager->updateProductImages($product,$tempImageFilename);
        $this->productManager->save($product);
        return $product;
    }


}