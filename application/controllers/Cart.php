<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends CI_Controller {

    function __construct() 
    {
        parent::__construct();

        $this->load->helper('common');          
        
        $this->userId = check_login();
        if($this->userId == false) {
            redirect("Login");
        }        				
    }
    
    /** Function   : index 
    * 
    * Description : Fetches the cart details of the user from Redis 
    *               and processes it such that cart page can render the details
    * 
    * Parameters : None
    * 
    * Return     : None  **/ 
    public function index() 
    {        
        $this->load->library('cartredis');
        $this->cartredis->setCartContents($this->userId);
        
        // Get IDs of all the items in the cart
        $cartItemIds = get_cart_item_ids($this->cartredis->cartContents);
        
        $data['data'] = array();
        if(!empty($cartItemIds)) {
            
            // Fetch cart item details from DB
            $this->load->model('item_model');
            $result = $this->item_model->getItems($cartItemIds);
            $data['data'] = $result['data'];
            $data['total'] = $result['num_rows'];        
            $data['total_cart_value'] = $this->cartredis->cartContents["cart_total"];                            
        }
        echo json_encode($data);
    }    
}