<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Symfony\Component\Validator\Constraints as Assert;
use App\Utils\StringHelper;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PageRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Page
{
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
     * @ORM\Column(type="string", length=32, unique=true)
     */
    private $titleDe;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=32, unique=true)
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
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $slugDe;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $slugEn;

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
     * @param string $locale
     * @return null|string
     */
    public function getTitle(string $locale): ?string
    {
        $key = __FUNCTION__.ucfirst($locale);
        return $this->$key();
    }

    /**
     * @param string $locale
     * @return null|string
     */
    public function getDescription(string $locale): ?string
    {
        $key = __FUNCTION__.ucfirst($locale);
        return $this->$key();
    }

    /**
     * @return string
     */
    public function getSlugDe(): string
    {
        return $this->slugDe;
    }
    /**
     * @ORM\PrePersist
     * @return Page
     */
    public function setSlugDe(): self
    {
        $this->slugDe = StringHelper::createSlug($this->getTitleDe());
        return $this;
    }

    /**
     * @return string
     */
    public function getSlugEn(): string
    {
        return $this->slugEn;
    }


    /**
     * @ORM\PrePersist
     * @return Page
     */
    public function setSlugEn(): self
    {
        $this->slugEn = StringHelper::createSlug($this->getTitleEn());
        return $this;
    }

    /**
     * @param string $locale
     * @return string
     */
    public function getSlug(string $locale): string
    {
        $key = __FUNCTION__.ucfirst($locale);
        return $this->$key();
    }




}
