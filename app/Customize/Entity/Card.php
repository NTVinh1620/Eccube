<?php

namespace Customize\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Customize\Repository\CardRepository")
 */
class Card
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
    private $tokenId;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"unsigned":true})
     */
    private $customerId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTokenId()
    {
        return $this->tokenId;
    }
    
    public function setTokenId($tokenId)
    {
        $this->tokenId = $tokenId;
    }

    public function getCustomerId()
    {
        return $this->customerId;
    }
    
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;
    }
}
