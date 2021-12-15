<?php

namespace App\Controller\Admin;

use App\Entity\ProductImage;
use App\Utils\Manager\ProductImageManager;
use App\Utils\Manager\ProductManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/product-image', name: 'admin_product_image_')]
class ProductImageController extends AbstractController
{
    #[Route('/{image}/delete', name: 'delete')]
    public function delete(ProductImage $image, ProductManager $productManager, ProductImageManager $productImageManager)
    {
        if (!$image) {
            return $this->redirectToRoute('admin_product_list');
        }

        $product = $image->getProduct();

        $productImagesDir = $productManager->getProductImagesDir($product);

        $productImageManager->removeImageFromProduct($image, $productImagesDir);

        return $this->redirectToRoute('admin_product_edit',['product'=>$product->getId()]);
    }
}
