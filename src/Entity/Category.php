<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Utils\StringHelper;

/**
 * @ORM\Table(indexes={
 *     @Index(name="idx_alias_de", columns={"alias_de"}),
 *     @Index(name="idx_alias_en", columns={"alias_en"}),
 * })
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Category
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTimeInterface
     *
     * @Assert\DateTime()
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTimeInterface
     *
     * @Assert\DateTime()
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(length=128, unique=true)
     */
    private $nameDe;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(length=128, unique=true)
     */
    private $nameEn;

    /**
     * @var string
     *
     * @ORM\Column(length=128, unique=true)
     */
    private $aliasDe;

    /**
     * @var string
     *
     * @ORM\Column(length=128, unique=true)
     */
    private $aliasEn;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="description_de", type="text")
     */
    private $descriptionDe;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="description_en", type="text")
     */
    private $descriptionEn;

    /**
     * @var category
     *
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     */
    private $children;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="category")
     */
    private $products;


    /**
     *
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->children = new ArrayCollection();
        $this->products = new ArrayCollection();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->setUpdatedAt(new \DateTime());
        $this->createAlias();
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->createAlias();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \DateTimeInterface $createdAt
     *
     * @return $this
     */
    public function setCreatedAt(\DateTimeInterface $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     *
     * @param \DateTimeInterface $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt(\DateTimeInterface $updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setNameDe(string $name): self
    {
        $this->nameDe = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getNameDe(): ?string
    {
        return $this->nameDe;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setNameEn(string $name): self
    {
        $this->nameEn = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getNameEn(): ?string
    {
        return $this->nameEn;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setAliasDe(string $name): self
    {
        $this->aliasDe = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getAliasDe(): ?string
    {
        return $this->aliasDe;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setAliasEn(string $name): self
    {
        $this->aliasEn = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getAliasEn(): ?string
    {
        return $this->aliasEn;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setDescriptionDe(string $name): self
    {
        $this->descriptionDe = $name;
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
     * @param string $name
     * @return $this
     */
    public function setDescriptionEn(string $name): self
    {
        $this->descriptionEn = $name;
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
     * @return Collection
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    /**
     * @param Collection $children
     * @return Category
     */
    public function setChildren(Collection $children = null): self
    {
        $this->children = $children;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Category $parent
     * @return Category
     */
    public function setParent(Category $parent = null): self
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return null !== $this->getNameDe() ?: '';
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param $locale
     * @return mixed
     */
    public function getName($locale)
    {
        $key = __FUNCTION__.ucfirst($locale);
        return $this->$key();
    }

    /**
     * @param $locale
     * @return mixed
     */
    public function getAlias($locale)
    {
        $key = __FUNCTION__.ucfirst($locale);
        return $this->$key();
    }

    /**
     * @param $locale
     * @return mixed
     */
    public function getDescription($locale)
    {
        $key = __FUNCTION__.ucfirst($locale);
        return $this->$key();
    }


    /**
     * @return string
     */
    public function getNameWithSubCat()
    {
        /** @var Category $parent */
        if (null !== $parent = $this->getParent()) {
            return $parent->getNameDe() . ' -> ' . $this->getNameDe();
        }

        return $this->getNameDe();
    }

    /**
     *
     */
    private function createAlias(): void
    {
        $this->setAliasDe(StringHelper::createSlug($this->getNameDe()));
        $this->setAliasEn(StringHelper::createSlug($this->getNameEn()));
    }
}
