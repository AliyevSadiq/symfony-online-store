<?php

namespace App\Controller\Main;

use App\Entity\Cart;
use App\Entity\CartProduct;
use App\Repository\CartProductRepository;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'main_api_')]
class CartApiController extends AbstractController
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/cart', name: 'save_cart')]
    public function saveCart(Request $request, ProductRepository $productRepository, CartRepository $cartRepository, CartProductRepository $cartProductRepository): Response
    {
        $productId = $request->request->get('product_id');
        $sessionId = $request->cookies->get('PHPSESSID');

        if (!$sessionId) {
            return new JsonResponse([
                'success' => false,
                'message' => 'session not exists'
            ]);
        }
        $product = $productRepository->findOneBy(['uuid' => $productId]);

        $cart = $cartRepository->findOneBy(['sessionId' => $sessionId]);

        if (!$cart) {
            $cart = new Cart();
            $cart->setSessionId($sessionId);
        }

        $cartProduct = $cartProductRepository->findOneBy(['cart' => $cart, 'product' => $product]);

        if (!$cartProduct) {
            $cartProduct = new CartProduct();
            $cartProduct->setCart($cart);
            $cartProduct->setProduct($product);
            $quantity = 1;
        } else {
            $quantity = $cartProduct->getQuantity() + 1;
        }
        $cartProduct->setQuantity($quantity);

        $cart->addCartProduct($cartProduct);

        $this->entityManager->persist($cart);
        $this->entityManager->persist($cartProduct);
        $this->entityManager->flush();

        return new JsonResponse([
            'success' => true,
            'message' => 'Product add to cart'
        ]);
    }
}
