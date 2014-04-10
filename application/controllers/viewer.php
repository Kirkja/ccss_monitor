<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Viewer extends CI_Controller {

    
    public function index() { exit(); }
    
    
    public function detach($imageID) {
        
                
        $data = array();
        $data['page_title'] = "View Image";

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