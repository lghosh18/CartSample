<?php

/* Function   : check_login()
    * 
    * Description : Checks whether user has logged in or not
    * 
    * Parameters : None
    * 
    * Return     : False if user not logged in, else User ID of the logged in user  **/             
function check_login() {

    $CI = & get_instance();
    $CI->load->library('session');

    $userId = $CI->session->userdata('user_id');
    //echo "fsdfsd";print_r($CI->session->userdata());
    if (!isset($userId))
        return false;
    else
        return $userId;
} 

/* Function   : get_loggedin_userid()
    * 
    * Description : Returns the logged in user's id from session 
    * 
    * Parameters : None
    * 
    * Return     : User ID of the logged in user  **/ 
function get_loggedin_userid() {

    $CI = & get_instance();
    $CI->load->library('session');

    $userId = $CI->session->userdata('user_id');

    return $userId;
}

/* Function   : get_cart_item_ids() 
    * 
    * Description : Processes the contents of the cart to return ids 
    *              of all the items in the cart
    * 
    * Parameters : array - containing all the contents of the cart
    * 
    * Return     : int array containing item ids  **/ 
function get_cart_item_ids($cartContents) {   
    
    $cartItemIds = array();
    foreach($cartContents as $key => $val) {

        if(isset($val["item_id"])) {
            $cartItemIds[] = $val["item_id"];
        }
    }
    return $cartItemIds;
}
    
function get_config_param($param) {
    $CI = & get_instance();
    return $CI->config->item($param);
}