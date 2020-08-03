<?php

require_once BASEPATH.'libraries/predis/autoload.php';

class CI_CartRedis {
    
    private $CI;
 
    function __construct() {
        $this->CI = get_instance();
        Predis\Autoloader::register();
        $this->redis = new Predis\Client();
        /*** Since we are connecting to default localhost and port 6379, this configuration is not needed
            $redis = new PredisClient(array(
            "scheme" => "tcp",
            "host" => "127.0.0.1",
            "port" => 6379));
        ***/
        
        $this->initialize();
    }
 
    function initialize() {
     
        $this->userPrefix = get_config_param(cart_key_prefix);
        $this->maxCartValidityTime = get_config_param(max_cart_validity_time);
    }    
    
    function setCartContents($userId) {
        $this->cartContents = $this->findCartByKey($userId); 
        return $this->cartContents;        
    }
    
    /* Function   : addToCartContents 
    * 
    * Description : Adds an item to a user's cart    
    * 
    * Parameters : $cartItem - array containing item details, $userId
    * 
    * Return     : None  **/ 
    function addToCartContents($cartItem, $userId) 
    {        
        $this->setCartContents($userId);
        if(!empty($cartItem)) {
            $this->cartContents = array_merge($this->cartContents, array($cartItem));            
        }
        $this->rebuildCart($userId);
    }
    
    /* Function   : removeCartItem 
    * 
    * Description : Removes an item from a user's cart. Delete the cart from Redis
    *               if no item left in cart     
    * 
    * Parameters : $itemId, $userId
    * 
    * Return     : None  **/ 
    public function removeCartItem($itemId, $userId) 
    {
        $this->setCartContents($userId);
        
        foreach ($this->cartContents as $key => $itemData) {
            if($itemData["item_id"] == $itemId) {
                array_splice($this->cartContents, $key, 1);
            }
        }
        if(isset($this->cartContents[0])) {
            $this->rebuildCart($userId);            
        }
        else {
            $this->deleteCart($userId);
        }        
    }
    
    /* Function   : rebuildCart 
    * 
    * Description : Regenerate cart properties like Total Amount etc. after an item
    *                is added or removed. After regenerating cart, update in Redis
    * 
    * Parameters : $userId
    * 
    * Return     : None  **/ 
    function rebuildCart($userId) {
        unset($this->cartContents['total_items']);
        unset($this->cartContents['cart_total']);
        $total = 0;
        
        foreach ($this->cartContents as $key => $val) {
            
            // Make sure the array contains the proper indexes
            if (!is_array($val) OR ! isset($val['price']) OR ! isset($val['qty'])) {
                continue;
            }
            $this->cartContents[$key]['item_total_price'] = $val['price'] * $val['qty'];
            $total += $this->cartContents[$key]['item_total_price'];
            $total_items += $this->cartContents[$key]['qty'];
        }

        // Set the cart total and total items.
        $this->cartContents['total_items'] = $total_items;
        $this->cartContents['cart_total'] = $total;       
        
        $this->updateUserCart($this->cartContents, $userId);        
    }
    
    /* Function   : updateUserCart 
    * 
    * Description : Sets cart details in Redis for a user
    * 
    * Parameters : $cartData, $userId
    * 
    * Return     : status of Redis update  **/ 
    public function updateUserCart($cartData, $userId) {
        
        $key = $this->userPrefix . $userId;
        
        $ttl = $this->maxCartValidityTime;    
        $data = json_encode($cartData);
        $status = $this->redis->setex($key, $ttl, $data);
        if($status === TRUE) {
            return TRUE;
        }
        else {
            return array('error'=>'Error in redis update');
        }      
    } 
    
    /* Function   : findCartByKey 
    * 
    * Description : Gets cart data from Redis for a particular key (formed from userid)
    * 
    * Parameters : $userId
    * 
    * Return     : $cartArr - array containing fetched cart data  **/ 
    function findCartByKey($userId) {
        
        $key = $this->createKeyUser($userId);
        $data = $this->redis->get($key);
        $cartArr = array();
        if(!empty($data)) {
            $cartArr = json_decode($data, true);
        }

        return $cartArr;        
    }
    
    /* Function   : createKeyUser 
    * 
    * Description : Creates key against which cart data will be saved in Redis. 
    *               Appends a pre-defined prefix to user id to generate key. 
    * 
    * Parameters : $userId
    * 
    * Return     : $key  **/
    function createKeyUser($userId) {
        
        $key = $this->userPrefix . $userId;
        return $key;
    }
    
    /* Function   : deleteCart 
    * 
    * Description : Deletes cart from Redis for a user id
    * 
    * Parameters : $userId
    * 
    * Return     : None  **/
    function deleteCart($userId) {
        
        $key = $this->createKeyUser($userId);
        $this->redis->del($key);
        unset($this->cartContents);
    }
    
    /* Function   : getCartIndex 
    * 
    * Description : Returns index of cart array for a particular item id
    * 
    * Parameters : $userId
    * 
    * Return     : $key - index of cart array where the sent $itemId is set  **/
    function getCartIndex($itemId) {
        foreach($this->cartContents as $key => $data)
        {
            if ($data["item_id"] == $itemId) {
                return $key;
            }
        }
        return false;
    }
}