<?php

namespace Site\ShopBundle\Entity;

/**
 * Description of CartItem
 *
 * @author Birko
 */
class CouponItem extends CartItem implements \Serializable
{
    protected $code = null;
    
    public function __construct() 
    {
        parent::__construct();
        $this->setChangeAmount(false);
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
            $this->code,
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
            $this->changeAmount,
            $this->code,
        ) = unserialize($serialized);
    }
    
    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }
    
    public function compareData($data = array())
    {
        if($data['code'] == $this->getCode())
        {
            return true;
        }
        return parent::compareData($data);     
    }
}

?>
