<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\EditProductFormType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'main_homepage')]
    public function index(): Response
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();

        return $this->render('main/default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

//    #[Route('/product-add',name: 'product-add', methods: ['GET','POST'])]
//    public function productAdd(Request $request): Response
//    {
//        $product=new Product();
//        $product->setTitle('Product'.rand(1,100));
//        $product->setDescription('Description'.rand(1,100));
//        $product->setPrice(rand(10,1000));
//        $product->setQuantity(rand(1,10));
//
//
//        $this->entityManager->persist($product);
//        $this->entityManager->flush();
//        return $this->redirectToRoute('main_homepage');
//
//    }

    #[Route('/edit-product/{product}', name: 'edit-product', requirements: ['product' => '\d+'], methods: ['GET', 'POST'])]
    #[Route('/add-product', name: 'product-add', methods: ['GET', 'POST'])]
    public function editProduct(Request $request, ?Product $product): Response
    {
        if (!isset($product)) {
            $product = new Product();
        }

        $form = $this->createForm(EditProductFormType::class,$product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($product);
            $this->entityManager->flush();
            return $this->redirectToRoute('edit-product',['product'=>$product->getId()]);
        }

        return $this->render('main/default/edit_product.html.twig', ['form' => $form->createView()]);
    }


}
