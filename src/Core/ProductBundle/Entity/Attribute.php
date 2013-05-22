<?php

namespace Core\ProductBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * Core\ProductBundle\Entity\Attribute
 *
 * @ORM\Table(name="product_attribute")
 * @ORM\Entity(repositoryClass="Core\ProductBundle\Entity\AttributeRepository")
 */
class Attribute
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
     * @var string name
     * @ORM\Column(type="string") 
     */
    protected $name;
    /** 
     * @var string value
     * @ORM\Column(type="string") 
     */
    protected $value;
    /** 
     * @var string group
     * @Gedmo\SortableGroup
     * @ORM\Column(name="agroup", type="string", nullable=true) 
     */
    protected $group;
    
    /**
     * @Gedmo\SortablePosition
     * @ORM\Column(name="position", type="integer")
     */
    protected $position;
    
    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="attributes")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="CASCADE")
     */
     protected $product;
    
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

    /**
     * Set group
     *
     * @param string $group
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }
    
    /**
     * Get value
     *
     * @return string 
     */
    public function  getGroup()
    {
        return $this->group;
    }

    
    /**
     * Set position
     *
     * @param integer $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    
    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }
     
    /**
     * Set product
     *
     * @param Product product
     */
    public function setProduct (Product $product)
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
}