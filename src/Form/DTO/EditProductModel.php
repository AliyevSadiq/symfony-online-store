<?php


namespace App\Form\DTO;


use App\Entity\Product;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class EditProductModel
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $description;

    /**
     * @var float
     */
    public $price;

    /**
     * @var int
     */
    public $quantity;

    /**
     * @var bool
     */
    public $isDeleted;

    /**
     * @var bool
     */
    public $isPublished;

    /**
     * @var UploadedFile|null
     */
    public $newImage;


    public static function makeFromProduct(?Product $product): self
    {
        $model=new self();


        if (!$product){
            return $model;
        }

        $model->id=$product->getId();
        $model->title=$product->getTitle();
        $model->description=$product->getDescription();
        $model->quantity=$product->getQuantity();
        $model->price=$product->getPrice();
        $model->isDeleted=$product->getIsDeleted();
        $model->isPublished=$product->getIsPublished();


        return $model;
    }
}