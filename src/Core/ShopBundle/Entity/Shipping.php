<?php

namespace Core\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Core\ShopBundle\Entity\Shipping
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Core\ShopBundle\Entity\ShippingRepository")
 */
class Shipping implements \Serializable
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
    
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
     * @var decimal $price
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=6 )
     */
    protected $price;
    
    /**
     * @var decimal $priceVAT
     *
     * @ORM\Column(name="price_vat", type="decimal", precision=10, scale=6 )
     */
    protected $priceVAT;
    
    /**
     * @ORM\ManyToOne(targetEntity="Core\ShopBundle\Entity\State")
     * @ORM\JoinColumn(name="state_id", referencedColumnName="id")
     */
    private $state;


    public function __construct()
    {
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
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
     * Set price
     *
     * @param decimal $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * Get price
     *
     * @return decimal
     */
    public function getPrice()
    {
        return $this->price;
    }
    
     /**
     * Set price
     *
     * @param decimal $price
     */
    public function setPriceVAT($price)
    {
        $this->priceVAT = $price;
    }

    /**
     * Get price
     *
     * @return decimal
     */
    public function getPriceVAT()
    {
        return $this->priceVAT;
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
            $this->state
        ));
        
    }

    public function unserialize($serialized) {
        list($this->id,
            $this->name,
            $this->description,
            $this->price,
            $this->priceVAT,
            $this->state
        ) = unserialize($serialized);
    }
    
    public function __toString()
    {
        return $this->getName() . " " . number_format($this->getPriceVAT(), 2);
    }
}