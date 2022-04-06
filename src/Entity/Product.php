<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Query\Expr\Func;
use Exception;
use Faker\Guesser\Name;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Twig\Node\CheckToStringNode;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message=" name ne doit pas etre vide")
     * @Assert\Length(min=3,max=255, minMessage="min 3 carater")
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="price cant be blank")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Url(message="need URL valid")
     * @Assert\NotBlank(message="mainPicture cant be blank")
     */
    private $mainPicture;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message=" le description ne doit pas etre vide")
     * @Assert\Length(min=20, minMessage="min 20 caraters pour la description")
     */
    private $shortDescription;

    // public static function loadValidatorMetaData(ClassMetadata $metaData)
    // {

    //     $metaData->addPropertyConstraints('name', [
    //         new NotBlank(['message' => "Le Nom doit pas etre vide"]),
    //         new Length(['min' => 3, 'max' => 255, 'minMessage' => 'Le nom doit etre minimum 3 caractere'])
    //     ]);

    //     $metaData->addPropertyConstraint('price', new NotBlank(['message' => "Le prix est obligatore"]));
    // }

    public $productName = '';
    public function __construct($name)
    {
        echo 'inside constructor of product';
        $this->productName = $name;
    }
    public function __toString()
    {
        echo "inside this";

        return $this->productName;
    }


    public function getUpperName()
    {
        return strtoupper($this->name);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    // with this the form will bypass the navigater default validation and with this we also need required = false in our form build method
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getMainPicture(): ?string
    {
        return $this->mainPicture;
    }

    public function setMainPicture(?string $mainPicture): self
    {
        $this->mainPicture = $mainPicture;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(?string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }
}
