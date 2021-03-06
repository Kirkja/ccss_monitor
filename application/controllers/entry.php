<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Entry extends CI_Controller {

    public function index() {
        
        //-- Test for inactive user to handle login
        if (!GSAuth::IsActive()) {            
            if ($this->input->post('user_name') 
                && $this->input->post('user_password')) {
                    if (GSAuth::Validate(
                        $this->input->post('user_name'), 
                        $this->input->post('user_password')
                    ))
                    {
                        redirect(base_url()."workspace");                    
                    }
            }
        } 
        else {
            redirect(base_url()."workspace");
        }
        
        $data = array();
        $data['page_title'] = "Entry";

        // Include the required CSS
        $data['css_includes'] = array(
           base_url().'css/style_basic.css'
        );
        
        // Include the required JS
        $data['js_includes'] = array(
            //'http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js',
           // 'http://ajax.googleapis.com/ajax/libs/angularjs/1.2.12/angular.min.js',
           // 'http://ajax.googleapis.com/ajax/libs/angularjs/1.2.12/angular-resource.min.js',            
           //  base_url(). 'lib/js/angular/root.js',
           //  base_url(). 'lib/js/angular/entry/entryController.js'
        );         

        // Data structures for each subview call if needed
        $headData = array(); 
        $bodyData = array();
        $footData = array();
        
        // Load the subviews into the page         
        $data['header_zone']    = GSUtil::GetView('entry/headerView',  $headData); 
        $data['body_zone']      = GSUtil::GetView('entry/bodyView',    $bodyData);
        $data['footer_zone']    = GSUtil::GetView('entry/footerView',  $footData);
        
        
        // render the constructed page
        $this->load->view('pagestub', $data);                
    }
    
    
    
    /**
     * 
     */
    public function out() {
        redirect(GSAuth::leave());        
    }    
        
    
    
}
?>