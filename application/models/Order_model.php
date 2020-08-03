<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends CI_Model {

    function __construct(){
        parent::__construct();
        $this->load->database();
    }
	
    /* Function   : getOrderDetails 
    * 
    * Description : Fetches Order details from order, order_item_details and item tables
    *               corresponding to an order id and logged in user id
    * 
    * Parameters : $itemIds - array of item ids
    * 
    * Return     :  Object containing reqd order details **/ 
    public function getOrderDetails($userId, $orderId) {
        
        $q = $this->db->select('o.id as order_id, o.user_id, o.status, o.total_amount, o.created_at, oi.item_id, oi.qty, oi.price, i.title as title, i.description as description')
                    ->from('tbl_orders o')
                    ->where(array('o.id' => $orderId, 'o.user_id' => $userId))
                    ->join('tbl_order_items oi','o.id = oi.order_id')
                    ->join('tbl_items i', 'oi.item_id=i.id')
                    ->get();
        $data['data'] = $q->result();
        return $data;
    }
    
    /* Function   : saveOrder 
    * 
    * Description : saves order info in tbl_orders table
    * 
    * Parameters : $userId, $orderTotal - total order amount which is sum of prices of all items
    * 
    * Return     :  Id of the last inserted row of orders table, i.e. Order ID **/ 
    public function saveOrder($userId, $orderTotal) {
        $data = array(
            'user_id' => $userId,
            'total_amount' => $orderTotal
        );

        $this->db->insert('tbl_orders', $data);
        return $this->db->insert_id(); 
    }
		
    /* Function   : saveOrderItems
    * 
    * Description : saves item details in the order in tbl_order_items table
    * 
    * Parameters : $orderId - primary key of tbl_orders table, $orderItems - array containing details of the items in the order
    * 
    * Return     :  Status of batch insert of the items **/ 
    public function saveOrderItems($orderId, $orderItems) {
        
        $data = array();
        $i = 0;
        foreach($orderItems as $itemId => $val) {
            $data[$i]['order_id'] = $orderId;
            $data[$i]['item_id'] = $itemId;      
            $data[$i]['qty'] = $val['qty'];
            $data[$i]['price'] = $val['price'];
            
            $i++;
        }        

        return $this->db->insert_batch('tbl_order_items', $data);                 
    }	
}

?>