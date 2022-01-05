<?php

namespace App\Controller\Admin;


use App\Entity\Order;
use App\Entity\StaticStorage\OrderStaticStorage;
use App\Form\Admin\EditOrderFormType;
use App\Form\Handler\OrderFormHandler;
use App\Repository\OrderRepository;
use App\Utils\Manager\OrderManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/order', name: 'admin_order_')]
class OrderController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function list(OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findBy(['isDeleted' => false], ['id' => 'DESC']);
        $orderStatus = OrderStaticStorage::getOrderStatusChoices();
        return $this->render('admin/order/list.html.twig', compact('orders', 'orderStatus'));
    }

    #[Route('/edit/{order}', name: 'edit', requirements: ['order' => '\d+'], methods: ['GET', 'POST'])]
    #[Route('/add', name: 'add', methods: ['GET', 'POST'])]
    public function save(Request $request, OrderFormHandler $formHandler, ?Order $order): Response
    {

        if (!$order) {
            $order = new Order();
        }

        $form = $this->createForm(EditOrderFormType::class, $order);

        if ($form->isSubmitted() && $form->isValid()) {

            $formHandler->editProcessForm($order);

            $this->addFlash('success', 'This order was successfully saved!!!');

            return $this->redirectToRoute('admin_order_list');
        }

        return $this->render('admin/order/edit.html.twig', [
            'order' => $order,
            'form' => $form->createView()
        ]);
    }

    #[Route('/delete/{order}', name: 'delete', requirements: ['order' => '\d+'])]
    public function delete(Order $order, OrderManager $orderManager): Response
    {
        $orderManager->remove($order);
        $this->addFlash('warning', 'This order was successfully deleted!!!');
        return $this->redirectToRoute('admin_order_list');
    }
}
