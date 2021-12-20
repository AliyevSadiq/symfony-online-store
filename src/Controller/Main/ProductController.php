<?php

namespace App\Controller\Main;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product/{uuid}', name: 'main_product_show')]
    public function index(Product $product): Response
    {
        return $this->render('main/product/show.html.twig', compact('product'));
    }
}
