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
        
        // Create the correct JSON payload from the SQL results
        //$out = array('data' => 
          echo  $this->getLesson($tmp->lesson);
        //);

        // Set the correct JSON response header
        //header('Content-Type: application/json');
        //echo json_encode($out);
        
        //  DEBUG ONLY
        //echo "<pre>" . print_r($out, true) . "</pre>";               
    }
    
    
    
    
    
    
    
    
    
    //=======================================================================
    // PRIVATE METHODS
    //
    
    private function getLesson($lesson) {
        
        //return $lesson;
        
        return GSUtil::GetView("help/content/{$lesson}", null);
        
        
    }
    
    
    
    
    
    
    private function getMenu($menu) {
        
        $data = array();
        
        switch($menu) {
            case "reviewTool":
                
                $data[] = array('label' => 'Topic 01', 'file' => 'lesson_01');
                $data[] = array('label' => 'Topic 02', 'file' => 'lesson_02');
                $data[] = array('label' => 'Topic 03', 'file' => 'lesson_03');
                $data[] = array('label' => 'Topic 04', 'file' => 'lesson_04');
                
                break;
        }
        
        return $data;
    }

}
?>
