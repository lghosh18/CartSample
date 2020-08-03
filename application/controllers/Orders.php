<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends CI_Controller {

    function __construct() {
        parent::__construct();
        
        $this->load->helper('common');  
    }

    /* Function   : get 
    * 
    * Description : Gets order details corresponding to the OrderId sent  
    *              and the logged in user id.
    * 
    * Parameters : $orderId - Order Id
    * 
    * Return     : None   **/
    public function get($orderId) {
        
        $this->load->model('order_model');
        $userId = get_loggedin_userid();
        $orderData = $this->order_model->getOrderDetails($userId, $orderId);
        $data['data'] = $orderData['data'];
        $data['order_id'] = $orderId;
        $data['total_amount'] = isset($orderData['data'][0]->total_amount) ? $orderData['data'][0]->total_amount : 0;
        echo json_encode($data);
    }
	
    /* Function   : placeOrder 
    * 
    * Description : Saves order details for the logged in user ID. Order is  
    *              created using the items in the cart. Cart is deleted once order is saved.
    * 
    * Parameters : None
    * 
    * Return     : None  **/
    public function placeOrder() {  
        
        $userId = get_loggedin_userid();
        if(empty($userId)) {
            echo json_encode(['success'=>false, 'message' => "User not logged in"]);
            return;
        } 
        
        // Get cart contents from Redis
        $this->load->library('cartredis');
        $this->cartredis->setCartContents($userId);        
        $orderItems = array();
        foreach($this->cartredis->cartContents as $key => $cartItem) {
            if(isset($cartItem['item_id'])) {
                $orderItems[$cartItem['item_id']]['qty'] = $cartItem['qty'];                
            }
        }
        
        // Get IDs of all the items in the cart
        $cartItemIds = get_cart_item_ids($this->cartredis->cartContents);
        
        // Fetch cart item details from DB as price might have changed in the meantime
        $this->load->model('item_model');
        $result = $this->item_model->getItems($cartItemIds);
        $itemsDetails = $result['data'];
        $orderTotal = 0;
        foreach($itemsDetails as $key => $item) {
            $orderItems[$item->id]['price'] = $item->selling_price;
            $orderItems[$item->id]['item_id'] = $item->id;
            $orderItems[$item->id]['title'] = $item->title;
            $orderItems[$item->id]['description'] = $item->description;
            $orderTotal += $orderItems[$item->id]['qty'] * $orderItems[$item->id]['price'];
        }

        // Save Order details in DB
        $this->load->model('order_model');
        $orderId = $this->order_model->saveOrder($userId, $orderTotal);
        if(!$orderId) {
            echo json_encode(['success'=>false, 'message' => 'Error in placing order.']);
        }
        $this->order_model->saveOrderItems($orderId, $orderItems);
                
        // Delete the cart
        $this->cartredis->deleteCart($userId);
        $data['order_id'] = $orderId;
        $data['total_amount'] = $orderTotal;
        echo json_encode($data);
    }

}
