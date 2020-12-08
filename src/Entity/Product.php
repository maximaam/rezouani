<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(indexes={@Index(name="idx_product_name", columns={"product_name"})})
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\HasLifecycleCallbacks
 *
 */
class Product
{
    /**
     * Images stored as string, separated with -
     */
    const IMG_SEPARATOR = '-';

    /**
     * @var array
     */
    protected static $availColors = [
        'white', 'silver', 'gray', 'black',
        'beige', 'yellow', 'gold', 'orange', 'red',
        'pink', 'violet', 'fuchsia', 'purple',
        'lightblue', 'blue', 'darkblue',
        'green', 'lightgreen',
        'burlywood', 'brown', 'maroon', 'darkred',
    ];

    /**
     * @var array
     */
    protected static $availSizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var \DateTimeInterface
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTimeInterface
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(length=128)
     */
    private $productNumber;

    /**
     * @var string
     *
     * @ORM\Column(length=128, nullable=true)
     */
    private $productName;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     */
    private $titleDe;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     */
    private $titleEn;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="text")
     */
    private $descriptionDe;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="text")
     */
    private $descriptionEn;

    /**
     * @var array
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="simple_array")
     */
    private $colors;

    /**
     * @var array
     *
     * @ORM\Column(type="simple_array", nullable=true)
     */
    private $sizes;


    /**
     * @var float
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="decimal", scale=2)
     */
    private $price;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $topItem;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="text")
     */
    private $images;

    /**
     * Product constructor
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * Pre update callback
     *
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * @return integer
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface $createdAt
     * @return $this
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTimeInterface $updatedAt
     * @return $this
     */
    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getProductNumber(): ?string
    {
        return $this->productNumber;
    }

    /**
     * @param string $product
     * @return $this
     */
    public function setProductNumber(string $product): self
    {
        $this->productNumber = $product;
        return $this;
    }

    /**
     * @return string
     */
    public function getProductName(): ?string
    {
        return $this->productName;
    }

    /**
     * @param string $product
     * @return $this
     */
    public function setProductName(string $product): self
    {
        $this->productName = $product;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitleDe(): ?string
    {
        return $this->titleDe;
    }

    /**
     * @param string $titleDe
     * @return $this
     */
    public function setTitleDe(string $titleDe): self
    {
        $this->titleDe = $titleDe;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitleEn(): ?string
    {
        return $this->titleEn;
    }

    /**
     * @param string $titleEn
     * @return $this
     *
     */
    public function setTitleEn(string $titleEn): self
    {
        $this->titleEn = $titleEn;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescriptionDe(): ?string
    {
        return $this->descriptionDe;
    }

    /**
     * @param string $descriptionDe
     * @return $this
     */
    public function setDescriptionDe(string $descriptionDe): self
    {
        $this->descriptionDe = $descriptionDe;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescriptionEn(): ?string
    {
        return $this->descriptionEn;
    }

    /**
     * @param string $descriptionEn
     * @return $this
     */
    public function setDescriptionEn(string $descriptionEn): self
    {
        $this->descriptionEn = $descriptionEn;
        return $this;
    }

    /**
     * @param $locale
     * @return null|string
     */
    public function getTitle($locale): ?string
    {
        $key = __FUNCTION__.ucfirst($locale);
        return $this->$key();
    }

    /**
     * @param $locale
     * @return null|string
     */
    public function getDescription($locale): ?string
    {
        $key = __FUNCTION__.ucfirst($locale);
        return $this->$key();
    }

    /**
     * @return array
     */
    public function getColors(): ?array
    {
        return $this->colors;
    }

    /**
     * @param array $colors
     * @return $this
     */
    public function setColors(array $colors): self
    {
        $this->colors = $colors;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getSizes(): ?array
    {
        return $this->sizes;
    }

    /**
     * @param array $sizes
     * @return $this
     */
    public function setSizes(array $sizes): self
    {
        $this->sizes = $sizes;
        return $this;
    }



    /**
     * @return float
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return $this
     */
    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     * @return $this
     */
    public function setCategory(Category $category): self
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return string
     */
    public function getImages(): ?string
    {
        return $this->images;
    }

    /**
     * @param string $images
     * @return $this
     */
    public function setImages(string $images): self
    {
        $this->images = $images;
        return $this;
    }

    /**
     * @return bool
     */
    public function isTopItem(): ?bool
    {
        return $this->topItem;
    }

    /**
     * @param bool $topItem
     * @return $this
     *
     */
    public function setTopItem(bool $topItem): self
    {
        $this->topItem = $topItem;
        return $this;
    }


    /**
     * @return array
     */
    public static function getAvailableColors()
    {
        return self::$availColors;
    }

    /**
     * @return array
     */
    public static function getAvailableSizes()
    {
        return self::$availSizes;
    }


}
