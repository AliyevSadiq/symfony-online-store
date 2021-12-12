<?php


namespace App\Form\Handler;


use App\Entity\Product;
use App\Utils\File\FileSaver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

class ProductFormHandler
{

    private EntityManagerInterface $entityManager;
    private FileSaver $fileSaver;

    public function __construct(EntityManagerInterface $entityManager, FileSaver $fileSaver)
    {
        $this->entityManager = $entityManager;
        $this->fileSaver = $fileSaver;
    }

    public function editProcessForm(Product $product,FormInterface $form): Product
    {
        $this->entityManager->persist($product);

        $newImageFile=$form->get('newImage')->getData();

        $temp=$newImageFile ? $this->fileSaver->saveUploadInTempFile($newImageFile) : null;
dd($temp);
        $this->entityManager->flush();
        return $product;
    }


}