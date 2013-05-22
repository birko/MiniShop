<?php

namespace Core\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Core\ShopBundle\Entity\Coupon
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Core\ShopBundle\Entity\CouponRepository")
 */
class Coupon
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
     * @var string $code
     *
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;

    /**
     * @var decimal $discount
     *
     * @ORM\Column(name="discount", type="decimal", precision=10, scale=6, nullable=true)
     */
    private $discount;

    /**
     * @var decimal $price
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=6, nullable=true)
     */
    private $price;

    /**
     * @var decimal $priceVAT
     *
     * @ORM\Column(name="priceVAT", type="decimal", precision=10, scale=6, nullable=true)
     */
    private $priceVAT;

    /**
     * @var boolean $active
     *
     * @ORM\Column(name="active", type="boolean", nullable = true)
     */
    private $active;
    
    /**
     * @var boolean $used
     *
     * @ORM\Column(name="used", type="boolean", nullable = true)
     */
    private $used;
    
    /**
     * @ORM\ManyToMany(targetEntity="Core\ProductBundle\Entity\Product")
     * @ORM\JoinTable(name="products_coupons")
     */
    private $products;

    public function __construct() {
        $this->products = new ArrayCollection();
        $this->setUsed(false);
        $this->setActive(true);
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
     * Set code
     *
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set discount
     *
     * @param decimal $discount
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    /**
     * Get discount
     *
     * @return decimal 
     */
    public function getDiscount()
    {
        return $this->discount;
    }
    
    /**
     * Set discount
     *
     * @param decimal $discount
     */
    public function setDiscountPerc($discount)
    {
        $this->setDiscount($discount / 100);
    }

    /**
     * Get discount
     *
     * @return decimal 
     */
    public function getDiscountPerc()
    {
        return $this->getDiscount() * 100;
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
     * Set priceVAT
     *
     * @param decimal $priceVAT
     */
    public function setPriceVAT($priceVAT)
    {
        $this->priceVAT = $priceVAT;
    }

    /**
     * Get priceVAT
     *
     * @return decimal 
     */
    public function getPriceVAT()
    {
        return $this->priceVAT;
    }

    /**
     * Set active
     *
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function isActive()
    {
        return $this->active;
    }
    
    /**
     * Set used
     *
     * @param boolean $used
     */
    public function setUsed($used)
    {
        $this->used = $used;
    }

    /**
     * Get used
     *
     * @return boolean 
     */
    public function isUsed()
    {
        return $this->used;
    }
    
     /**
     * Get products
     *
     * @return ArrayCollection
     */    
    public function getProducts()
    {
        return $this->products;   
    }
}