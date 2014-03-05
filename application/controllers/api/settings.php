<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Settings extends CI_Controller {

    /**
     * 
     */
    public function index() {
        // Automatically exit. Non one needs to access the root
        //
        exit();
    }
    
    
    
    public function login() {
        
        $tmp = array();
        $data = array();
        
        if (GSAuth::IsActive()) {            
           $tmp['ScreenName']  = "Dummy Screen Name";
           $tmp['UserName']    = 'DummyUserName';
           $tmp['UserCode']    = 'DummyPassword';                      
        }
        $data[] = $tmp;
        
        echo json_encode($data);        
    }
}