<?php

namespace Core\ProductBundle\Entity;

/**
 * Description of ProductFilter
 *
 * @author Birko
 */
class Filter implements \Serializable
{
    protected $words = null;
    protected $vendor = null;
    protected $order = null;
    protected $category = null;
    protected $tags  = array();
    protected $page = 1;
    
    public function __construct()
    {
        $this->order = "p.createdAt desc";
    }
    
    public function getCategory()
    {
        return $this->category;
    }
    
    public function setCategory($category)
    {
        $this->category = $category;
    }
    
    public function getWords()
    {
        return $this->words;
    }
    
    public function setWords($words)
    {
        
        $this->words = trim($words);
    }
    
    public function getWordsArray()
    {
        return preg_split('/([\s\-_,:;?!\/\(\)\[\]{}<>\r\n"]|(?<!\d)\.(?!\d))/', $this->getWords(), null, PREG_SPLIT_NO_EMPTY);
    }
    
    public function getVendor()
    {
        return $this->vendor;
    }
    
    public function setVendor($vendor)
    {
        $this->vendor = $vendor;
    }
    
    public function getTags()
    {
        return $this->tags;
    }
    
    public function setTags($tags)
    {
        $this->tags = $tags;
    }
    
    public function getOrder()
    {
        return $this->order;
    }
    
    public function setPage($page)
    {
        $this->page = $page;
    }
    
    public function getPage()
    {
        return $this->page;
    }
    
    public function setOrder($order)
    {
        $this->order = $order;
    }
    
    public function serialize() {
        return serialize(array(
            $this->vendor,
            $this->order,
            $this->words,
            $this->category,
            $this->tags,
            $this->page
        ));
    }
    
    public function unserialize($serialized) {
        list(
            $this->vendor,
            $this->order,
            $this->words,
            $this->category,
            $this->tags,
            $this->page
        ) = unserialize($serialized);
    }
}

?>
