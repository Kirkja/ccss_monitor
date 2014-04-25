<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

  
class Help extends CI_Controller {



    public function index() {
        // Automatically exit. Noone needs to access the root
        //
        exit();
    }

    
    public function menus() {
                
        // Retrieve the POST data off the buffer
        $raw = file_get_contents("php://input");
        $tmp = json_decode($raw);
        
        // Reject the process unless all the required values are preent
        if (!isset($tmp->menu))     { exit(); }        
        
        // Create the correct JSON payload from the SQL results
        $out = array('data' => 
            $this->getMenu($tmp->menu)
        );

        // Set the correct JSON response header
        header('Content-Type: application/json');
        echo json_encode($out);
        
        //  DEBUG ONLY
        //echo "<pre>" . print_r($out, true) . "</pre>";               
    }


    public function lesson() {
                
        // Retrieve the POST data off the buffer
        $raw = file_get_contents("php://input");
        $tmp = json_decode($raw);
        
        // Reject the process unless all the required values are preent
        if (!isset($tmp->lesson)) {                         
            exit();             
        }        
                
        echo  $this->getLesson($tmp->lesson);        
    }
    
    
    
    
    
    public function getPage() {
        // Retrieve the POST data off the buffer
        $raw = file_get_contents("php://input");
        $tmp = json_decode($raw);
        
        // Reject the process unless all the required values are preent
        if (!isset($tmp->page)) {                         
            exit();             
        }        
 
        echo  GSUtil::GetView("help/content/{$tmp->page}", null);
     
    }
    
    
    
    //=======================================================================
    // PRIVATE METHODS
    //
    
    private function getLesson($lesson) {        
        return GSUtil::GetView("help/content/{$lesson}", null);                
    }
    
    
    
    
    
    
    private function getMenu($menu) {
        
        $data = array();
        
        switch($menu) {
            case "reviewTool":
                
                $data[] = array('label' => 'Workspace Intro', 'file' => 'lesson_01');
                $data[] = array('label' => 'Sample Navigation', 'file' => 'lesson_02');
                $data[] = array('label' => 'Viewing Sample Images', 'file' => 'lesson_03');
                $data[] = array('label' => 'Browsing Standards', 'file' => 'lesson_04');
                $data[] = array('label' => 'Searching Standards', 'file' => 'lesson_05');
                $data[] = array('label' => 'Review Items', 'file' => 'lesson_06');
                                
                break;
            
            /*
            case "dok":
                $data[] = array('label' => 'Topic 01', 'file' => 'dok_guide');
                break;
            
            case "blm":
                $data[] = array('label' => 'Topic 01', 'file' => 'blooms_guide');
                break;
            */
        }
        
        return $data;
    }
    
    
    
    

}
?>
