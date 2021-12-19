<?php

namespace App\Controller\Main;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class EmbedController extends AbstractController
{
    public function categories(CategoryRepository $categoryRepository): Response
    {
        $categories=$categoryRepository->findBy(['isDeleted'=>false],['id'=>'ASC'],5);
        return $this->render('main/_embed/_menu/_categories.html.twig',compact('categories'));
    }

    public function showSimilarProducts(ProductRepository $productRepository, int $limit=4,int $categoryId=null): Response
    {
        $params=['isDeleted'=>false];

        if ($categoryId){
            $params['category']=$categoryId;
        }

        $products=$productRepository->findBy($params,['id'=>'DESC'],$limit);

        return $this->render('main/_embed/_similar_product.html.twig',compact('products'));
    }
}
