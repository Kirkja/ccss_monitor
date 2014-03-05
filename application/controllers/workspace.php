<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Workspace extends CI_Controller {

    public function index() {
        
        if (!GSAuth::IsActive()) { 
            GSAuth:leave();
        }
        
        $data = array();
        $data['page_title'] = "Workspace";

        
        // Include the required CSS
        $data['css_includes'] = array(
            base_url() .'lib/js/jquery/jquery-ui-1.10.4/themes/base/jquery.ui.all.css',            
            base_url() .'css/layout.css',
            base_url() .'css/ws2.css'
        );
        
        
        // Include the required JS
        $data['js_includes'] = array(            
            'http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js',
            'http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js',
            base_url() .'lib/js/jquery/jquery.layout-latest.js',
            base_url() .'lib/js/jquery/jquery.layout.resizeTabLayout-latest.min.js',
            base_url() .'lib/js/jquery/jquery.layout.resizePaneAccordions-latest.js',
            base_url() .'lib/js/actions/init_workspace.js',
            base_url() .'lib/js/actions/ws2_actions.js',
            'http://ajax.googleapis.com/ajax/libs/angularjs/1.2.12/angular.min.js',
            'http://ajax.googleapis.com/ajax/libs/angularjs/1.2.12/angular-resource.min.js',
            base_url() .'lib/js/angular/ws/app.js',
            base_url() .'lib/js/angular/ws/SettingsController.js'            
        );         

        // Data structures for each subview call if needed
        $headData = array(); 
        $bodyData = array();
        $footData = array();
        
        // Load the subviews into the page         
        $data['header_zone']    = $this->GetView('workspace/headerView', $bodyData); 
        $data['body_zone']      = $this->GetView('workspace/bodyView', $bodyData);
        $data['footer_zone']    = $this->GetView('workspace/footerView', $footData);
        
        // render the constructed page
        $this->load->view('pagestub', $data);         
        
        
    }
    
    
    private function GetView($filename, $data)
    {
        if (file_exists(APPPATH."views/{$filename}.php")) {
            return $this->load->view($filename, $data, true);
        }
        else {
            return null;
        }
    }    
}
?>
