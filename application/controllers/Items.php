<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Items extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->helper('url');       
        $this->load->helper('common');  
        $this->load->library('cartredis');
        
        $res = check_login();
        if($res == false) {
            redirect("Login");
        }        				
    }
	
    /** Function  : index 
    * 
    * Description : Fetches all the items from MySQL DB. Processes and set  
    *               all the details as needed for the item listing view.
    * 
    * Parameters : None
    * 
    * Return     : None  **/ 
    public function index() {        
        $this->load->database();

        $searchKey = $this->input->get("search");
        $page = $this->input->get("page",1);
        if(!$page) $page = 1;
        
        // Fetch items on the DB. If search key is sent, fetch items whose title/description match
        $this->load->model('item_model');
        $result = $this->item_model->getAllItems($searchKey, $page);                        
        $data['data'] = $result['data'];
        $data['total'] = $result['num_rows']; 
        
        // Get user's current cart details (needed for showing/hiding Add-To-Cart/Remove-From-Cart buttons)
        $userId = get_loggedin_userid();
        $cartContents = $this->cartredis->findCartByKey($userId);  
        $data['cartItemIds'] = get_cart_item_ids($cartContents);
        foreach($data['data'] as $key => $val) {
            $data['data'][$key]->present_in_cart = 0;
            if(in_array($val->id, $data['cartItemIds'])) {
                $data['data'][$key]->present_in_cart = 1;
            }                        
        } 
        $data['total_cart_value'] = $cartContents["cart_total"];

        echo json_encode($data);
    }
         
    /** Function   : itemAddToCart 
    * 
    * Description : Adds an item to the user's cart in Redis
    * 
    * Parameters : $id - Item ID that needs to be added in the cart.
    * 
    * Return     : None  **/ 
    public function itemAddToCart($id) 
    {        
        $this->load->model('item_model');
        $item = $this->item_model->getItemById($id);   

        if($item) {
            $cartItem['item_id'] = $id;
            $cartItem['qty'] = 1;
            $cartItem['price'] = $item->selling_price;

            $userId = get_loggedin_userid();
            $this->cartredis->addToCartContents($cartItem, $userId);
            
            $totalCartValue = isset($this->cartredis->cartContents["cart_total"]) ? $this->cartredis->cartContents["cart_total"] : 0;
            echo json_encode(['success'=>true, 'total_cart_value' => $totalCartValue]);
        }
    }
    
    /** Function   : index 
    * 
    * Description : Removes an item from the user's cart in Redis
    * 
    * Parameters : $id - Item ID that needs to be added in the cart.
    * 
    * Return     : None  **/ 
    public function itemRemoveFromCart($id) 
    {
        $userId = get_loggedin_userid();
        $this->cartredis->removeCartItem($id, $userId);        
        $totalCartValue = isset($this->cartredis->cartContents["cart_total"]) ? $this->cartredis->cartContents["cart_total"] : 0;
        echo json_encode(['success'=>true, 'total_cart_value' => $totalCartValue]);
    }            
}
