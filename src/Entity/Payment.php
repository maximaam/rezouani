<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaymentRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Payment
{
    const STATUS_INIT = 1;
    const STATUS_CONFIRM = 2;

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
     * @ORM\Column(type="string")
     */
    private $status;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $delivered = false;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $paymentId;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $buyerEmail;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $buyerName;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $amount;


    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $buyerAddress;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    private $paypalPaymentDetails;

    /**
     * @var array
     *
     * @ORM\Column(type="simple_array")
     */
    private $productsIds;


    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $productsContent;


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
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentId(): string
    {
        return $this->paymentId;
    }

    /**
     * @param string $paymentId
     * @return $this
     */
    public function setPaymentId(string $paymentId): self
    {
        $this->paymentId = $paymentId;
        return $this;
    }

    /**
     * @return array
     */
    public function getPaypalPaymentDetails(): array
    {
        return $this->paypalPaymentDetails;
    }
    /**
     * @param array $paypalPaymentDetails
     * @return $this
     */
    public function setPaypalPaymentDetails(array $paypalPaymentDetails): self
    {
        $this->paypalPaymentDetails = $paypalPaymentDetails;
        return $this;
    }

    /**
     * @return array
     */
    public function getProductsIds(): array
    {
        return $this->productsIds;
    }
    /**
     * @param array $ids
     * @return $this
     */
    public function setProductsIds(array $ids): self
    {
        $this->productsIds = $ids;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDelivered(): bool
    {
        return $this->delivered;
    }

    /**
     * @param bool $delivered
     * @return $this
     */
    public function setDelivered(bool $delivered): self
    {
        $this->delivered = $delivered;
        return $this;
    }

    /**
     * @return string
     */
    public function getBuyerEmail(): string
    {
        return $this->buyerEmail;
    }

    /**
     * @param string $buyerEmail
     * @return $this
     */
    public function setBuyerEmail(string $buyerEmail): self
    {
        $this->buyerEmail = $buyerEmail;
        return $this;
    }

    /**
     * @return string
     */
    public function getBuyerName(): string
    {
        return $this->buyerName;
    }

    /**
     * @param string $buyerName
     * @return $this
     */
    public function setBuyerName(string $buyerName): self
    {
        $this->buyerName = $buyerName;
        return $this;
    }

    /**
     * @return string
     */
    public function getBuyerAddress(): string
    {
        return $this->buyerAddress;
    }

    /**
     * @param string $buyerAddress
     * @return $this
     *
     */
    public function setBuyerAddress(string $buyerAddress): self
    {
        $this->buyerAddress = $buyerAddress;
        return $this;
    }


    /**
     * @return string
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * @param string $amount
     * @return $this
     *
     */
    public function setAmount(string $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setProductsContent(string $name): self
    {
        $this->productsContent = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getProductsContent(): ?string
    {
        return $this->productsContent;
    }






}
