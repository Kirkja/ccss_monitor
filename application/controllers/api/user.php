<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends CI_Controller {
    
     public function index() {
         exit();
     }
    
     
     public function check()
     {
        $errors = array();  	// array to hold validation errors
        $data   = array(); 	// array to pass back data
                 
        // read in the POST, and parse into into an object 
        $raw = file_get_contents("php://input"); 
        $tmp = json_decode($raw);       

        $data['stuff'] = print_r($tmp, true);
                
        if (empty($tmp->userName)) {
            $errors['user'] = 'Name is required.';
        }
	if (empty($tmp->userPassword)) {
            $errors['password'] = 'Password is required';        
        }
        
        if ( !empty($errors)) {
            $data['success'] = false;
            $data['errors']  = $errors;  
            echo json_encode($data);
            exit();
        }
        
        
        //-- Run through the user login testing

        if (!GSAuth::IsActive()) { 
            if (GSAuth::Validate($tmp->userName, $tmp->userPassword)) {
                $data['success'] = true;
                $data['message'] = GSAuth::$user->activeID; 
                echo json_encode($data);            
            }
            exit();
        }         
        
        $data['success'] = true;
        $data['message'] = "Already logged in.";         
         echo json_encode($data); 
        
        // response if there are errors
        /*
	if ( ! empty($errors)) {
            // if there are items in our errors array, return those errors
            $data['success'] = false;
            $data['errors']  = $errors;
	} else {
            // if there are no errors, return a message
            $data['success'] = true;
            $data['message'] = 'Success!';
	}
        */
	// return all our data to an AJAX call
	//echo json_encode($data);        
        
     }
    
}

