<?php

namespace Core\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Core\ProductBundle\Entity\Product
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Core\ProductBundle\Entity\ProductRepository")
 */
class Product
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
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string $slug
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var text $shortDescription
     *
     * @ORM\Column(name="shortDescription", type="text", nullable=true)
     */
    private $shortDescription;

    /**
     * @var text $longDescription
     *
     * @ORM\Column(name="longDescription", type="text", nullable=true)
     */
    private $longDescription;
    
     /**
     * @var datetime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;
    
    /**
     * @ORM\ManyToMany(targetEntity="Core\CategoryBundle\Entity\Category", inversedBy="products")
     * @ORM\JoinTable(name="products_categorises")
     */
     private $categories;
     
    /**
     * @ORM\OneToMany(targetEntity="Core\ProductBundle\Entity\Price", mappedBy="product")
     * @ORM\OrderBy({ "priceVAT" = "ASC"})
     */
    private $prices;
    
     /**
     * @ORM\ManyToOne(targetEntity="Core\VendorBundle\Entity\Vendor", inversedBy="products")
     * @ORM\JoinColumn(name="vendor_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $vendor;
    
     /**
     * @ORM\OneToMany(targetEntity="Core\ProductBundle\Entity\ProductOption", mappedBy="product")
     * @ORM\OrderBy({"name" = "ASC", "position" = "ASC", "value" = "ASC"})
     */
    private $options; 
    
    /**
     * @ORM\ManyToMany(targetEntity="Core\MediaBundle\Entity\Media")
     * @ORM\JoinTable(name="products_medias")
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private $media;
    
    /**
     * @ORM\OneToMany(targetEntity="Attribute", mappedBy="product")
     */
    private $attributes;
    
    /**
     * @ORM\OneToOne(targetEntity="Stock", mappedBy="product")
     **/
    private $stock;
    
    /**
     * @var boolean $anabled
     *
     * @ORM\Column(name="enabled", type="boolean", nullable = true)
     */
    private $enabled;
    
    /**
     * @var string $tags
     * @ORM\Column(name="tags", type="string", length=255, nullable = true)
     */
    private $tags;
    



    public function __construct() 
    {
        $this->setCreatedAt(new \DateTime());
        $this->categories = new ArrayCollection();
        $this->prices = new ArrayCollection();
        $this->options = new ArrayCollection();
        $this->media = new ArrayCollection();
        $this->attributes = new ArrayCollection();
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
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set shortDescription
     *
     * @param text $shortDescription
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;
    }

    /**
     * Get shortDescription
     *
     * @return text 
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Set longDescription
     *
     * @param text $longDescription
     */
    public function setLongDescription($longDescription)
    {
        $this->longDescription = $longDescription;
    }

    /**
     * Get longDescription
     *
     * @return text 
     */
    public function getLongDescription()
    {
        return $this->longDescription;
    }
    
    /**
     * Get categories
     *
     * @return ArrayCollection
     */    
    public function getCategories()
    {
        return $this->categories;   
    }
    
    /**
     * Get prices
     *
     * @return ArrayCollection
     */    
    public function getPrices($type = null)
    {
        if($type)
        {
            return $this->getPrices()->filter(
                function($entry) use ($type) {
                    return ($entry->getType() == $type);
                }
            );
        }
        else
        {
            return $this->prices;  
        }
    }
    
    /**
     * Get options
     *
     * @return ArrayCollection
     */    
    public function getOptions()
    {
        return $this->options;   
    }
    
    /**
     * Set Vendor
     *
     * @param Vendor $vendor
     */
    public function setVendor($vendor)
    {
        $this->vendor = $vendor;
    }

    /**
     * Get Vendor
     *
     * @return Vendor
     */
    public function getVendor()
    {
        return $this->vendor;
    }
    
     /**
     * Get media
     *
     * @return media
     */
    public function getMedia()
    {
        return $this->media;
    }
    
    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return datetime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    public function getPricesByPriceGroup($priceGroup = null, $type =null)
    {
        if($priceGroup !== null)
        {
            return $this->getPrices($type)->filter(
                function($entry) use ($priceGroup) {
                    return ($entry->getPriceGroup()->getId() == $priceGroup->getId());
                }
            );
        }
        else 
        {
            return $this->getPrices($type);
        }
    }
    
    public function getMinimalPrice($priceGroup = null, $type = null)
    {   
        $price = null;
        if($this->getPrices()!== null && $this->getPrices()->count() > 0)
        {
            $price = $this->getPricesByPriceGroup($priceGroup, $type)->first();
            if($price === null)
            {
                $price = $this->getPrices($type)->first();
            }
        }
        if($price === null)
        {
            $price = new Price();
        }
        return $price;
    }
    
     /**
     * Get attributes
     *
     * @return ArrayCollection
     */
    public function getAttributes()
    {
        return $this->attributes;   
    }
    
    public function getAttribute($name)
    {
        return $this->getAttributes()->filter(function($entry) use ($name)
        {
            return ($entry->getName() == $name);
        })->current();
    }
    
    public function getGroupedAttributes()
    {
        $result = array();
        $attributes = $this->getAttributes();
        if(!empty($attributes))
        {
            foreach($this->getAttributes() as $attribute)
            {
                $result[$attribute->getName()][] = $attribute; 
            }
        }
        return $result;
    }
    
    /**
     * Set stock
     *
     * @param Stock $stock
     */
    public function setStock(Stock $stock = null)
    {
        $this->stock = $stock;
    }

    /**
     * Get Stock
     *
     * @return Stock
     */
    public function getStock()
    {
        return $this->stock;
    }
    
    /**
     * Set enabled
     *
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * Get enabled
     *
     * @return boolean 
     */
    public function isEnabled()
    {
        return $this->enabled;
    }
    
    /**
     * Set tags
     *
     * @param mixed $tags
     */
    public function setTags($tags)
    {
        if(!empty($tags))
        {
            $this->tags = implode(', ', $tags) .  ', ';
        }
        else
        {
            $this->tags = null;
        }
    }

    /**
     * Get tags
     *
     * @return mixed
     */
    public function getTags()
    {
        $tags = array();
        if(!empty($this->tags))
        {
            $tags = explode(', ', $this->tags);
            $end = trim(end($tags));
            if(empty($end))
            {
                unset($tags[count($tags) - 1]);
            }
        }

        return $tags;
    }
}