<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper('security');
        $this->load->database('default');
        $this->load->model('login_model');
        $this->load->library('session');
    }
	
    public function index() {
        $this->load->view('login');
    }
	
    /* Function   : doLogin 
    * 
    * Description : Checks the username and pwd in the DB and logs in the user if correct. 
    *               Password hash is create using the below code and saved in password col of tbl_users
    *               $hash = password_hash($password, PASSWORD_DEFAULT);  
    * 
    * Parameters : None
    * 
    * Return     : None  **/ 
    public function doLogin() {
        $this->load->library('form_validation');  
        $this->form_validation->set_rules('username', 'Username', 'required');  
        $this->form_validation->set_rules('password', 'Password', 'required');  
        if($this->form_validation->run())  
        {   
            $username = $this->input->post('username');  
            $password = $this->input->post('password');  
            $res = $this->login_model->getUser($username);

            // Case: Username not present
            if(empty($res)) {
                echo json_encode(array('message' => "Incorrect username or password"));
            }
            // Case: Password matches
            else if(password_verify($password, $res->password)){
                
                $session_data = array(  
                                    'user_id' => $res->id,
                                    'username' => $username  
                                 );  
                $this->session->set_userdata($session_data);
                echo $this->get_parsed($res);
            }
            // Case: Password does not match
            else {
                echo json_encode(array('message' => "Incorrect username or password"));
            }
        }
        else {
            $errors = validation_errors();
            echo json_encode(array('message' => $errors));
        }
    }
	
    function test() {
        $password = "admin";
        $hash = password_hash($password, PASSWORD_DEFAULT);
        echo $hash;
        if (password_verify($password, $hash)) {
            echo "Match!";
        }        
    }
    
    /* Function   : logout() 
    * 
    * Description : Destroys the current session to log out the user  
    * 
    * Parameters : None
    * 
    * Return     : None  **/ 
    public function logout() {

        $this->session->sess_destroy();
        redirect("Login");
    }
        
    /* Function   : get_parsed 
    * 
    * Description : Formats JSON response
    * 
    * Parameters : $result - result of log in attempt
    * 
    * Return     : None  **/ 
    public function get_parsed($result) {
        
        $response = array('success'=>false,'message'=>'Unable to login.');
        if(!empty($result))
        {
                $response = array('success'=>true,'message'=>'logged in.');
        }
        return json_encode($response);
    }                
}
