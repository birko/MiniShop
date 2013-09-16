<?php

namespace Core\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Core\PriceBundle\Entity\AbstractPrice;

/**
 * Core\ShopBundle\Entity\Payment
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Core\ShopBundle\Entity\PaymentRepository")
 */
class Payment  extends AbstractPrice implements \Serializable
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
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;
    


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

    public function serialize() {
        return serialize(array(
            $this->id,
            $this->price,
            $this->priceVAT,
            $this->name,
            $this->description,
            $this->vat
            
        ));
    }

    public function unserialize($serialized) {
        list(
            $this->id,
            $this->price,
            $this->priceVAT,    
            $this->name,
            $this->description,
            $this->vat
        ) = unserialize($serialized);
        
    }
    
    public function __toString()
    {
        return $this->getName() . " " . number_format($this->getPriceVAT(), 2);
    }
}