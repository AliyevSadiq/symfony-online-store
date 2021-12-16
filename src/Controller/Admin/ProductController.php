<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\DTO\EditProductModel;
use App\Form\EditProductFormType;
use App\Form\Handler\ProductFormHandler;
use App\Repository\ProductRepository;
use App\Utils\Manager\ProductManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/product/', name: 'admin_product_')]
class ProductController extends AbstractController
{


    #[Route('list', name: 'list')]
    public function list(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findBy(['isDeleted' => false], ['id' => 'DESC']);

        return $this->render('admin/product/list.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('edit/{product}', name: 'edit', requirements: ['product' => '\d+'], methods: ['GET', 'POST'])]
    #[Route('add', name: 'add', methods: ['GET', 'POST'])]
    public function save(Request $request, ProductFormHandler $handler, ?Product $product): Response
    {
        $productModel = EditProductModel::makeFromProduct($product);

        $form = $this->createForm(EditProductFormType::class, $productModel);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $product = $handler->editProcessForm($productModel, $form);

            return $this->redirectToRoute('admin_product_edit', ['product' => $product->getId()]);
        }
        return $this->render('admin/product/edit.html.twig', [
            'images' => $product  ? $product->getProductImages()->getValues() : [],
            'form' => $form->createView(),
            'product' => $product
        ]);
    }

    #[Route('delete/{product}', name: 'delete', requirements: ['product' => '\d+'])]
    public function delete(Product $product, ProductManager $productManager): Response
    {
        $productManager->remove($product);
        return $this->redirectToRoute('admin_product_list');
    }

}
