<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommandOrderRepository")
 */
class CommandOrder
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="time")
     */
    private $delivery;

    /**
     * @ORM\Column(type="boolean")
     */
    private $payment_cash;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Buyers", inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $buyers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderDetails", mappedBy="id_order", orphanRemoval=true)
     */
    private $orderDetails;

    public function __construct()
    {
        $this->orderDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDelivery(): ?\DateTimeInterface
    {
        return $this->delivery;
    }

    public function setDelivery(\DateTimeInterface $delivery): self
    {
        $this->delivery = $delivery;

        return $this;
    }

    public function getPaymentCash(): ?bool
    {
        return $this->payment_cash;
    }

    public function setPaymentCash(bool $payment_cash): self
    {
        $this->payment_cash = $payment_cash;

        return $this;
    }

    public function getBuyers(): ?Buyers
    {
        return $this->buyers;
    }

    public function setBuyers(?Buyers $buyers): self
    {
        $this->buyers = $buyers;

        return $this;
    }

    /**
     * @return Collection|OrderDetails[]
     */
    public function getOrderDetails(): Collection
    {
        return $this->orderDetails;
    }

    public function addOrderDetail(OrderDetails $orderDetail): self
    {
        if (!$this->orderDetails->contains($orderDetail)) {
            $this->orderDetails[] = $orderDetail;
            $orderDetail->setIdOrder($this);
        }

        return $this;
    }

    public function removeOrderDetail(OrderDetails $orderDetail): self
    {
        if ($this->orderDetails->contains($orderDetail)) {
            $this->orderDetails->removeElement($orderDetail);
            // set the owning side to null (unless already changed)
            if ($orderDetail->getIdOrder() === $this) {
                $orderDetail->setIdOrder(null);
            }
        }

        return $this;
    }
}
