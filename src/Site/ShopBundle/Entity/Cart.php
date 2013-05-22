<?php
namespace Site\ShopBundle\Entity;
/**
 * Description of Cart
 *
 * @author Birko
 */
class Cart implements \Serializable
{
    protected $shippingAddress = null;
    protected $paymentAddress = null;
    protected $sameAddress = true;
    protected $items = array();
    protected $payment = null;
    protected $shipping = null;
    protected $comment = null;
    
    public function __construct()
    {
        $this->shippingAddress = null;
        $this->paymentAddress = null;
        $this->sameAddress = true;
        $this->items = array();
    }
    
    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }
    
    public function setShippingAddress($address = null)
    {
        $this->shippingAddress = $address;
    }
    
    public function getPaymentAddress()
    {
        return $this->paymentAddress;
    }
    
    public function setPaymentAddress($address = null)
    {
        $this->paymentAddress = $address;
    }
    
    
    public function isSameAddress()
    {
        return $this->sameAddress;
    }
    
    public function setSameAddress($sameAddress)
    {
        $this->sameAddress = $sameAddress;
    }
    
    public function getItems()
    {
        return $this->items;
    }
    
    public function setItems($items = array())
    {
        $this->items = $items;
    }
    
    public function addItem(CartItem $item, $index = null)
    {
        if($index !== null && $index !== false)
        {
            $this->items[$index]= $item;
        }
        else
        {
            $this->items[]= $item;
        }
    }
    
    public function getItem($index)
    {
        $items = $this->getItems();
        if(!$this->isEmpty() && count($items) > $index)
        {     
            return $items[$index];
        }
        return null;
    }
    
    public function getItemsCount()
    {
        $count = 0;
        if(!$this->isEmpty())
        {
            $items = $this->getItems();
            foreach($items as $item)
            {
                $count += $item->getAmount();
            }
        }
        return $count;
        
    }
    
    public function addItems($items = array(), $index = null)
    {
        if(!empty($items))
        {
            foreach($items as $item)
            {
                $this->addItem($item, $index);
                if($index !== null && $index !== false)
                {
                    $index++;
                }
            }
        }
    }
    
    public function findItem($itemData)
    {
        if(!$this->isEmpty())
        {
            $items = $this->getItems();
            foreach($items as $key => $item)
            {
                if($item->compareData($itemData))
                {
                    return $key;
                }
            }
        }
        return false;
    }
    
    public function isEmpty()
    {
        return empty($this->items);
    }
    
    public function removeItem($index)
    {
        if(!$this->isEmpty())
        {
            $items = $this->getItems();
            if ($index >= 0 && $index <= count($items))
            {
                unset($items[$index]);
                $items = array_values($items);
                $this->setItems($items);
            }
        }
    }
    
    public function getPayment()
    {
        return $this->payment;
    }
    
    public function setPayment($payment = null)
    {
        $this->payment = $payment;
    }
    
    public function getShipping()
    {
        return $this->shipping;
    }
    
    public function setShipping($shipping = null)
    {
        $this->shipping = $shipping;
    }
    
    public function getPrice()
    {
        $price = 0;
        if(!$this->isEmpty())
        {
            foreach($this->getItems() as $item)
            {
                $price += $item->getPriceTotal();
            }
        }
        if(!empty($this->payment))
        {
            $price += $this->payment->getPrice();
        }
        if(!empty($this->shipping))
        {
            $price += $this->shipping->getPrice();
        }
        return $price;
    }
    
    public function getPriceVAT()
    {
        $price = 0;
        if(!$this->isEmpty())
        {
            foreach($this->getItems() as $item)
            {
                $price += $item->getPriceVATTotal();
            }
        }
        if(!empty($this->payment))
        {
            $price += $this->payment->getPrice();
        }
        if(!empty($this->shipping))
        {
            $price += $this->shipping->getPriceVAT();
        }
        return $price;
    }
    
    public function getComment()
    {
        return $this->comment;
    }
    
    public function setComment($comment = null)
    {
        $this->comment = $comment;
    }
    
    public function clearItems()
    {
        $this->setPaymentAddress();
        $this->setShippingAddress();
        $this->setPayment();
        $this->setShipping();
        $this->setComment();
        $this->setSameAddress(true);
        $this->setItems();
    }
            
    
    public function serialize() {
        return serialize(array(
            $this->shippingAddress,
            $this->paymentAddress,
            $this->sameAddress,
            $this->items,
            $this->payment,
            $this->shipping,
            $this->comment,
        ));
    }
    public function unserialize($serialized) 
    {
        list(
            $this->shippingAddress,
            $this->paymentAddress,
            $this->sameAddress,
            $this->items,
            $this->payment,
            $this->shipping,
            $this->comment,
          ) = unserialize($serialized);
    }
}

?>
