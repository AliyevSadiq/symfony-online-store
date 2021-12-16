<?php


namespace App\Utils\Manager;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

abstract class AbstractBaseManager
{
    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
   {

       $this->entityManager = $entityManager;
   }

    /**
     * @return ObjectRepository
     */
    abstract public function getRepository(): ObjectRepository;


    public function find(string $id)
    {
      return  $this->getRepository()->find($id);
    }

    /**
     * @param object $entity
     */
    public function save(object $entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    /**
     * @param object $entity
     */
    public function remove(object $entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}