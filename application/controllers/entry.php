<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Entry extends CI_Controller {

    public function index() {
        
        
        /* --------------------------------
         * This controller is open to all
         * --------------------------------
         */
        
        // Handle a login form posting
        // if not already logged into the system
        if (!GSAuth::IsActive()) {            
            if ($this->input->post('user_name') 
                && $this->input->post('user_password')) {
                    GSAuth::Validate(
                        $this->input->post('user_name'), 
                        $this->input->post('user_password')
                    );
            }
        } 
        
        
        
        $data = array();
        $data['page_title'] = "Entry";

        // Include the required CSS
        $data['css_includes'] = array(
           base_url().'css/style_basic.css'
        );
        
        // Include the required JS
        $data['js_includes'] = array(
           
        );         

        // Data structures for each subview call if needed
        $headData = array(); 
        $bodyData = array();
        $footData = array();
        
        // Load the subviews into the page         
        $data['header_zone']    = $this->GetView('entry/headerView', $bodyData); 
        $data['body_zone']      = $this->GetView('entry/bodyView', $bodyData);
        $data['footer_zone']    = $this->GetView('entry/footerView', $footData);
        
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

    
    
    public function out() {
        redirect(GSAuth::leave());        
    }    
        
    
    
}
?>