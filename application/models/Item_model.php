<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item_model extends CI_Model {

    function __construct(){
        parent::__construct();
        $this->load->database();
    }
		
    /* Function   : getAllItems 
    * 
    * Description : Fetches all items from DB. If search keyword is sent,
    *              fetch those items whose title/description matches the search keyword.
    * 
    * Parameters : $searchKey, $page - page number
    * 
    * Return     : Array containing Item Data and Total Item Count  **/ 
    function getAllItems($searchKey, $page) {
        if(!empty($searchKey)) {
                $this->db->like('title', $searchKey);
                $this->db->or_like('description', $searchKey); 
        }

        $this->db->limit(5, ($page - 1) * 5); // todo- get this value from config
        $query = $this->db->get("tbl_items");

        $data['data'] = $query->result();
        $data['num_rows'] = $this->db->count_all("tbl_items");
        
        return $data;
    }    
    
    /* Function   : getItemById 
    * 
    * Description : Fetches an item corresponding to an item id
    * 
    * Parameters : $itemId
    * 
    * Return     : Item object **/
    function getItemById($itemId) {
        $q = $this->db->get_where('tbl_items', array('id' => $itemId));
        $item = $q->row();
        
        return $item;
    }
    
    /* Function   : getItems 
    * 
    * Description : Fetches all items corresponding to a list of item ids
    * 
    * Parameters : $itemIds - array of item ids
    * 
    * Return     : Array containing Item Data and Total Rows Returned by query **/ 
    function getItems($itemIds) {
        $this->db->select('id, title, description, selling_price');
        $this->db->where_in('id', $itemIds);
        $query = $this->db->get('tbl_items');

        $data['data'] = $query->result();
        $data['num_rows'] = $query->num_rows();
        return $data;
    }    
    
}

?>