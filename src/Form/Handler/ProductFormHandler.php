<?php


namespace App\Form\Handler;


use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

class ProductFormHandler
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function editProcessForm(Product $product,FormInterface $form): Product
    {
        $this->entityManager->persist($product);
        $this->entityManager->flush();
        return $product;
    }
}