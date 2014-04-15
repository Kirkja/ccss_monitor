<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {


	public function index()
	{
            
 
        $data = array();
        $data['page_title'] = "Welcome";

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
        $data['header_zone']    = GSUtil::GetView('splash/headerView',  $headData); 
        $data['body_zone']      = GSUtil::GetView('splash/bodyView',    $bodyData);
        $data['footer_zone']    = GSUtil::GetView('splash/footerView',  $footData);
        
        // render the constructed page
        $this->load->view('pagestub', $data);  
        
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */