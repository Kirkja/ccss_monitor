<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Standards extends CI_Controller {

    /**
     * 
     */
    public function index() {
        // Automatically exit. Non one needs to access the root
        //
        exit();
    }
    
    
    //==========================================================================
    // Public Methods
    //
    
    public function getEntries() {
        
        $activeID = GSAuth::Fence();
        
        if (!$activeID) { exit(); }
        //-------------------------
        
        $data   = array();
        $raw    = file_get_contents("php://input");
        $tmp    = json_decode($raw);
        
        $catalogID = $tmp->cid;
        
        //$catalogID = '95478981184192512';
        
 
        // Create the correct JSON payloads
        $out = array('data' => 
            $this->extractCatalog($catalogID)
        );

        // Set the correct JSON response header
        header('Content-Type: application/json');
        echo json_encode($out);

        //  DEBUG ONLY
        //echo "<pre>" . print_r($out, true) . "</pre>";            
    }
    
    
    
    
    
    //==========================================================================
    // Private Methods
    //
    
    /**
     * 
     * @param type $activeID
     * @param type $catalogID
     */
    private function extractCatalog($catalogID) {
        
        
        
        $sql = "SELECT 
                CCSS.id
              , CONCAT(CCSS.state,'_',CCSS.key0) AS standardKey
              , CCSS.Tier_2 AS standardText
              FROM ccss_standards AS CCSS
              WHERE CCSS.Tier_2 <> ''
              AND catalogID = {$catalogID}
              ORDER BY CCSS.gradelevel, CONCAT(CCSS.state,'_',CCSS.key0)";
        
        $query = $this->db->query($sql);
        
        $tmp = array();
        
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row)
            {
                $tmp[] = array(
                    'key'   => $row->standardKey,
                    'desc'  => $row->standardText
                );                
            }
        }
        
        return $tmp;
    }
    
    
    
}

?>