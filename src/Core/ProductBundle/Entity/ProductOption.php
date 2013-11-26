<?php

namespace Core\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use Doctrine\Common\Collections\ArrayCollection;
use Core\CommonBundle\Entity\TranslateEntity;


/**
 * Core\ProductBundle\Entity\ProductOption
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Core\ProductBundle\Entity\ProductOptionRepository")
 * @Gedmo\TranslationEntity(class="Core\CommonBundle\Entity\Translation")
 */
class ProductOption extends TranslateEntity implements \Serializable
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     * @Gedmo\Translatable
     * @Gedmo\SortableGroup
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string $value
     * @Gedmo\Translatable
     * @ORM\Column(name="value", type="string", length=255, nullable=true)
     */
    private $value;
    
     /**
     * @Gedmo\SortableGroup
     * @ORM\ManyToOne(targetEntity="Core\ProductBundle\Entity\Product", inversedBy="options")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $product;
    
    /**
     * @Gedmo\SortablePosition
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    private $position;
    
    /**
     * @var decimal $amount
     *
     * @ORM\Column(name="amount", type="decimal", nullable = true)
     */
    private $amount;



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
     * Set value
     *
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }
    
    public function setPosition($position)
    {
        $this->position = $position;
    }

    public function getPosition()
    {
        return $this->position;
    }
    
    /**
     * Set amount
     *
     * @param decimal $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * Get amount
     *
     * @return decimal 
     */
    public function getAmount()
    {
        return $this->amount;
    }
    
    /**
     * Set product
     *
     * @param Product $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }


    /**
     * Get product
     *
     * @return Product 
     */
    public function getProduct()
    {
        return $this->product;
    }
    

    public function serialize() {
        return serialize(array(
            $this->id,
            $this->name,
            $this->value,
            $this->position,
            $this->amount,
            $this->locale 
        ));
        
    }

    public function unserialize($serialized) {
        list(
            $this->id,
            $this->name,
            $this->value,
            $this->position,
            $this->amount,
            $this->locale 
        ) = unserialize($serialized);
    }
}