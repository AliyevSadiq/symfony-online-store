<?php


namespace App\Form\Handler;


use App\Entity\Product;
use App\Form\DTO\EditProductModel;
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

    /**
     * @param EditProductModel $editProductModel
     * @param FormInterface $form
     * @return Product|null
     */
    public function editProcessForm(EditProductModel $editProductModel,FormInterface $form): ?Product
    {
        $product=new Product();

        if ($editProductModel->id){
            $product=$this->productManager->find($editProductModel->id);
        }

        $product->setTitle($editProductModel->title);
        $product->setDescription($editProductModel->description);
        $product->setCategory($editProductModel->category);
        $product->setQuantity($editProductModel->quantity);
        $product->setPrice($editProductModel->price);
        $product->setIsPublished($editProductModel->isPublished);
        $product->setIsDeleted($editProductModel->isDeleted);

        $this->productManager->save($product);

        $newImageFile=$form->get('newImage')->getData();

        $tempImageFilename=$newImageFile ? $this->fileSaver->saveUploadInTempFile($newImageFile) : null;

        $this->productManager->updateProductImages($product,$tempImageFilename);
        $this->productManager->save($product);
        return $product;
    }


}