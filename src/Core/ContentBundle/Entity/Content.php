<?php

namespace Core\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Core\ContentBundle\Entity\Content
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Core\ContentBundle\Entity\ContentRepository")
 */
class Content
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
     * @ORM\Column(name="title", type="string", length=255, nullable = true)
     */
    private $title;
    
    /**
     * @var string $slug
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="slug", type="string", length=255, nullable = true)
     */
    private $slug;

    /**
     * @var string $shortDescription
     * 
     * @ORM\Column(name="shortDescription", type="text", nullable = true)
     */
    private $shortDescription;

    /**
     * @var string $longDescription
     * 
     * @ORM\Column(name="longDescription", type="text", nullable = true)
     */
    private $longDescription;
    
    /**
     * @ORM\ManyToOne(targetEntity="Core\CategoryBundle\Entity\Category", inversedBy="contents")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $category;
    
    /**
     * @ORM\ManyToMany(targetEntity="Core\MediaBundle\Entity\Media")
     * @ORM\JoinTable(name="contents_medias")
     */
    private $media;

    public function __construct()
    {
        $this->media = new ArrayCollection();
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
     * @param string $shortDescription
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;
    }

    /**
     * Get shortDescription
     *
     * @return string 
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Set longDescription
     *
     * @param string $longDescription
     */
    public function setLongDescription($longDescription)
    {
        $this->longDescription = $longDescription;
    }

    /**
     * Get longDescription
     *
     * @return string 
     */
    public function getLongDescription()
    {
        return $this->longDescription;
    }
    
    /**
     * Set Category
     *
     * @param Category  $category
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
    
    /**
     * Get media
     *
     * @return media
     */
    public function getMedia()
    {
        return $this->media;
    }
}