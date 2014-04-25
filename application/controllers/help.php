<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Help extends CI_Controller {

    
    
    
    public function index() {
                                
        $data = array();
        $data['page_title'] = "Help System";

        // Include the required CSS
        $data['css_includes'] = array(
            base_url() .'lib/js/jquery/jquery-ui-1.10.4/themes/base/jquery.ui.all.css',            
            base_url() .'css/layout.css',
            base_url() .'css/ws2.css'
        );
        
        // Include the required JS
        $data['js_includes'] = array(            
            'http://ajax.googleapis.com/ajax/libs/angularjs/1.2.12/angular.min.js',
            'http://ajax.googleapis.com/ajax/libs/angularjs/1.2.12/angular-resource.min.js',
            'http://ajax.googleapis.com/ajax/libs/angularjs/1.2.12/angular-sanitize.min.js',
            base_url() .'lib/js/angular/help/help.js',
            base_url() .'lib/js/angular/help/helpController.js'            
        );         

        // Data structures for each subview call if needed
        $headData = array(); 
        $bodyData = array();
        $footData = array();
        
        // Load the subviews into the page         
        $data['header_zone']    = GSUtil::GetView('help/headerView', $headData); 
        $data['body_zone']      = GSUtil::GetView('help/bodyView', $bodyData);
        $data['footer_zone']    = GSUtil::GetView('help/footerView', $footData);
        
        // render the constructed page
        $this->load->view('pagestub', $data);                
    }
      

    
}
?>