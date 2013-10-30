<?php

namespace Core\BannerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Core\BannerBundle\Entity\Banner
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Core\BannerBundle\Entity\BannerRepository")
 */
class Banner
{

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $title
     * 
     * @ORM\Column(name="title", type="string", length=255, nullable = true)
     */
    private $title;
    

    /**
     * @var string $description
     * 
     * @ORM\Column(name="description", type="text", nullable = true)
     */
    private $description;
    
    /**
     * @var string $link
     *
     * @ORM\Column(name="link", type="string", length=1024, nullable = true)
     */
    private $link;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Core\MediaBundle\Entity\Media")
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", onDelete="CASCADE")
     */
     protected $media;
     
    /**
     * @ORM\ManyToOne(targetEntity="Core\CategoryBundle\Entity\Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="CASCADE")
     */
     protected $category;
     
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
      * Set description
      *
      * @param string $description
      */
     public function setDescription($description)
     {
         $this->description = $description;
     }

     /**
      * Get description
      *
      * @return string 
      */
     public function getDescription()
     {
         return $this->description;
     }

    /**
     * Set link
     *
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink()
    {
        return $this->link;
    }
    
    /**
     * Set media
     *
     * @param Media $media
     */
    public function setMedia($media)
    {
        $this->media = $media;
    }

    /**
     * Get Media
     *
     * @return Media
     */
    public function getMedia()
    {
        return $this->media;
    }
    
     /**
     * Set category
     *
     * @param Category $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * Get category
     *
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    } 
}