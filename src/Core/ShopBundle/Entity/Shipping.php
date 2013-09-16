<?php

namespace Core\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Core\PriceBundle\Entity\AbstractPrice;
/**
 * Core\ShopBundle\Entity\Shipping
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Core\ShopBundle\Entity\ShippingRepository")
 */
class Shipping  extends AbstractPrice implements \Serializable
{
    
    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var text $description
     *
     * @ORM\Column(name="description", type="text", nullable = true)
     */
    private $description;
    
    /**
     * @ORM\ManyToOne(targetEntity="Core\ShopBundle\Entity\State")
     * @ORM\JoinColumn(name="state_id", referencedColumnName="id")
     */
    private $state;


    public function __construct()
    {
    }
    
    
    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text 
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * Set State
     *
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * Get State
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    public function serialize() {
        return serialize(array(
            $this->id,
            $this->name,
            $this->description,
            $this->price,
            $this->priceVAT,
            $this->state,
            $this->vat
        ));
        
    }

    public function unserialize($serialized) {
        list($this->id,
            $this->name,
            $this->description,
            $this->price,
            $this->priceVAT,
            $this->state,
            $this->vat
        ) = unserialize($serialized);
    }
    
    public function __toString()
    {
        return $this->getName() . " " . number_format($this->getPriceVAT(), 2);
    }
}