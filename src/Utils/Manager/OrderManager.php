<?php


namespace App\Utils\Manager;


use App\Entity\Cart;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\StaticStorage\OrderStaticStorage;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class OrderManager extends AbstractBaseManager
{
    private CartManager $cartManager;

    public function __construct(EntityManagerInterface $entityManager, CartManager $cartManager)
    {
        parent::__construct($entityManager);
        $this->cartManager = $cartManager;
    }

    /**
     * @return ObjectRepository
     */
    public function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(Order::class);
    }

    /**
     * @param string $session_id
     */
    public function createOrderFromCartBySession(string $session_id, User $user)
    {
      $cart=$this->cartManager->getRepository()->findOneBy(['sessionId'=>$session_id]);

      if ($cart){
          $this->createOrderFromCart($cart,$user);
      }
    }

    /**
     * @param Cart $cart
     */
    public function createOrderFromCart(Cart $cart,User $user)
    {
     $order=new Order();
     $order->setOwner($user);
     $order->setStatus(OrderStaticStorage::ORDER_STATUS_CREATED);

     $totalPrice=0;

     foreach ($cart->getCartProducts()->getValues() as $cartProduct){

         $orderProduct=new OrderProduct();

         $product=$cartProduct->getProduct();

         $orderProduct->setAppOrder($order);

         $orderProduct->setProduct($product);

         $orderProduct->setPricePerOne($product->getPrice());

         $orderProduct->setQuantity($cartProduct->getQuantity());

         $order->addOrderProduct($orderProduct);
         $this->entityManager->persist($orderProduct);

         $totalPrice +=$orderProduct->getQuantity()*$orderProduct->getPricePerOne();
     }

        $order->setTotalPrice($totalPrice);

        $this->entityManager->persist($order);

        $this->entityManager->flush();

        $this->cartManager->remove($cart);

    }

    /**
     * @param object $order
     */
    public function remove(object $order)
    {
        $order->setIsDeleted(true);

        $this->save($order);
    }
}