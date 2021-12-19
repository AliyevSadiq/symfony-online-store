<?php


namespace App\Form\DTO;


use App\Entity\Category;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class EditProductModel
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     * @Assert\NotBlank(message="Please enter title")
     */
    public $title;

    /**
     * @var string
     * @Assert\NotBlank(message="Please enter description")
     */
    public $description;

    /**
     * @var Category
     * @Assert\NotBlank(message="Please select category")
     */
    public $category;

    /**
     * @var float
     * @Assert\NotBlank(message="Please enter price")
     * @Assert\GreaterThanOrEqual(value="0")
     */
    public $price;

    /**
     * @var int
     * @Assert\NotBlank(message="Please enter quantity")
     * @Assert\GreaterThanOrEqual(value="0")
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
     * @Assert\File(
     *     maxSize="5024k"
     * )
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
        $model->category=$product->getCategory();
        $model->quantity=$product->getQuantity();
        $model->price=$product->getPrice();
        $model->isDeleted=$product->getIsDeleted();
        $model->isPublished=$product->getIsPublished();


        return $model;
    }
}