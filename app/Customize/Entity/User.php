<?php

namespace Customize\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Customize\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    private $stripeCustomerId;

    /**
     * @ORM\Column(type="integer", unique=true, nullable=true, options={"unsigned":true})
     */
    private $customerId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStripeCustomerId()
    {
        return $this->stripeCustomerId;
    }
    
    public function setStripeCustomerId($stripeCustomerId)
    {
        $this->stripeCustomerId = $stripeCustomerId;
    }

    public function getCustomerId()
    {
        return $this->stripeCustomerId;
    }
    
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;
    }
}
