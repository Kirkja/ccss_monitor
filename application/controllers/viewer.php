<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Viewer extends CI_Controller {

    
    public function index() { exit(); }
    
    
    public function detach($imageID) {
        
        
                        
        $data = array();
        $data['page_title'] = "View Image";

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
            base_url() .'lib/js/angular/viewer/viewer.js',
            base_url() .'lib/js/angular/viewer/viewerController.js'            
        );         

        // Data structures for each subview call if needed
        $headData = array(); 
        $bodyData = array('imageID' => $imageID);
        $footData = array();
        
        // Load the subviews into the page         
        $data['header_zone']    = $this->GetView('viewer/headerView', $headData); 
        $data['body_zone']      = $this->GetView('viewer/bodyView', $bodyData);
        $data['footer_zone']    = $this->GetView('viewer/footerView', $footData);
        
        // render the constructed page
        $this->load->view('pagestub', $data);                
    }
    
    /**
     * Fecthes a view without causing an error is the file does not exists.
     * 
     * @param type $filename
     * @param type $data
     * @return null
     */
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