<?php

namespace Site\ShopBundle\Entity;

/**
 * Description of CartItem
 *
 * @author Birko
 */
class CartItem implements \Serializable
{
    
    protected $amount = 0;
    protected $productID = null;
    protected $options = array();
    protected $variations = array();
    protected $price = 0;
    protected $priceVAT = 0;
    protected $name;
    protected $description;
    protected $changeAmount = true;
    
    public function __construct() 
    {
    }

    public function serialize() {
        return serialize(array(
            $this->amount,
            $this->productID,
            $this->price,
            $this->priceVAT,
            $this->name,
            $this->description,
            $this->options,
            $this->variations,
            $this->changeAmount,
        ));
    }

    public function unserialize($serialized) {
        list(
            $this->amount,
            $this->productID,
            $this->price, 
            $this->priceVAT,     
            $this->name,
            $this->description,
            $this->options,
            $this->variations,
            $this->changeAmount
        ) = unserialize($serialized);
    }
    
    public function isChangeAmount()
    {
        return $this->changeAmount;
    }
    
    protected function setChangeAmount($changeAmount)
    {
        $this->changeAmount = $changeAmount;
    }
    
    public function getAmount()
    {
        return $this->amount;
    }
    
    public function setAmount($amout)
    {
        $this->amount = $amout;
    }
    
    public function addAmount($amount)
    {
        $this->setAmount($this->getAmount() + $amount);
    }
    
    public function setProductId($productid)
    {
        $this->productID = $productid;
    }
    public function getProductId()
    {
        return $this->productID;
    }
    
    public function setPrice($price)
    {
        $this->price = $price;
    }
    
    public function getPrice()
    {
        return $this->price;
    }
    
    public function setPriceVAT($pricevat)
    {
        $this->priceVAT = $pricevat;
    }
    
    public function getPriceVAT()
    {
        return $this->priceVAT;
    }
    
    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
    
    public function setDescription($desciption)
    {
        $this->description = $desciption;
    }

    public function getDescription()
    {
        return $this->description;
    }
    
    public function getOptions()
    {
        return $this->options;
    }
    
    public function setOptions($options = array())
    {
        $this->options = $options;
    }
    
    public function addOption($option)
    {
        $this->options[$option->getId()] = $option;
    }
    
    public function addOptions($options = array())
    {
        if(!empty($options))
        {
            foreach($options as $option)
            {
                $this->addOption($option);
            }
        }
    }
    
    public function getVariatoins()
    {
        return $this->variations;
    }
    
    public function setVariations($variations = array())
    {
        $this->variations = $variations;
    }
    
    public function addVariation($variation)
    {
        $this->variations[$variation->getId()] = $variation;
    }
    
    public function addVariations($variations = array())
    {
        if(!empty($variations))
        {
            foreach($variations as $variation)
            {
                $this->addVariation($variation);
            }
        }
    }
    
    public function compareData($data = array())
    {
        if(!$this->isChangeAmount())
        {
            return false;
        }
        if($data['productId'] !== $this->getProductId())
        {
            return false;
        }
        
        if($data['price'] != $this->getPrice())
        {
            return false;
        }
       
        if(!empty($data['option']))
        {
            $opts = $this->getOptions();
            if(!empty($opts ))
            {
                foreach($data['option'] as $option)
                {
                    if(!in_array($option, array_keys($opts)))
                    {
                        $opts = null;
                        return false;
                    }
                }
            }
            else
            {
                $opts = null;
                return false;
            }
            $opts = null;
        }
        
        if(!empty($data['variation']))
        {
            $vars = $this->getVariatoins();
            if(!empty($vars))
            {
                foreach($data['variation'] as $variation)
                {
                    if(!in_array($variation, array_keys($vars)))
                    {
                        $vars = null;
                        return false;
                    }
                }
            }
            else
            {
                $vars = null;
                return false;
            }
            $vars = null;
        }
        return true;       
    }
    
    public function getPriceTotal()
    {
        return $this->getAmount()* $this->getPrice();
    }
    
    public function getPriceVATTotal()
    {
        return $this->getAmount()* $this->getPriceVAT();
    }
}

?>
