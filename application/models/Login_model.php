<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {

    public $username;
    public $password;
    public $date;
		
    function __construct(){
        parent::__construct();
    }
				
    /* Function   : getUser 
    * 
    * Description : Gets user details corresponding to username and password
    *              entered by the user in the Login form
    * 
    * Parameters : $username, $password
    * 
    * Return     : Object containing user details  **/ 
    public function getUser($username) {            
        $this->db->select('id, email, username, password');
        $this->db->from('oms.tbl_users');
        $this->db->where('username', $username);
        $query = $this->db->get();
        $res   = $query->result();      
        return $res[0];
    }

}

?>