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
    
    public function getCatalogs() {
        
        $activeID = GSAuth::Fence();        
        if (!$activeID) { exit(); }
        //-------------------------
        
        $data   = array();
        $raw    = file_get_contents("php://input");
        $tmp    = json_decode($raw);
        
        $blockID = $tmp->blockID;
        
        // Create the correct JSON payloads
        $out = array('data' => 
            $this->findCatalogs($blockID)
        );

        // Set the correct JSON response header
        header('Content-Type: application/json');
        echo json_encode($out);

        //  DEBUG ONLY
        //echo "<pre>" . print_r($out, true) . "</pre>";          
    }
    
    
    
    
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
    
    
    public function searchEntries() {
        
        $activeID = GSAuth::Fence();        
        if (!$activeID) { exit(); }
        //-------------------------
        
        $data   = array();
        $raw    = file_get_contents("php://input");
        $tmp    = json_decode($raw);
        
        $catalogList    = $tmp->cidList;
        $terms          = $tmp->terms;
        
        // Create the correct JSON payloads
        $out = array('data' => 
            $this->catalogSearch($catalogList, $terms)
        );

        // Set the correct JSON response header
        header('Content-Type: application/json');
        echo json_encode($out);

        //  DEBUG ONLY
        //echo "<pre>" . print_r($out, true) . "</pre>";            
    }    
    
    
    
    
    
    public function searchFor() {
        
        $activeID = GSAuth::Fence();        
        if (!$activeID) { exit(); } 
        //-------------------------
        
        $data   = array();
        $raw    = file_get_contents("php://input");
        $tmp    = json_decode($raw);
        
        $catalogList = "";
        $terms = "";
        
        // Create the correct JSON payloads
        $out = array('data' => 
            $this->catalogSearch($catalogList, $terms)
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
    
    
    private function findCatalogs($blockID) {
        
        $sql = "SELECT 
                BC.id AS catalogID
              , CONCAT(BC.state, ' ' , BC.label) AS label
              FROM bank_catalog AS BC
              LEFT JOIN map_catalog_block MCB ON MCB.catalogID = BC.id
              WHERE BC.active = 'y'
              AND MCB.blockID = {$blockID}
              GROUP BY BC.id";
        
        $query = $this->db->query($sql);
        
        $tmp = array();
        
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row)
            {
                $tmp[] = array(
                    'catalogID'     => $row->catalogID,
                    'label'         => $row->label
                );                
            }
        }
        
        return $tmp;                
    }
    
    
    private function catalogSearch($catalogList, $terms) {
        
        $tmp = array();
        
        $catalogList = trim($catalogList, ",");

        $sql = "SELECT 
                  id
                , CONCAT(state,'_',key0) AS standardKey
                , Tier_2 AS standardText
                , MATCH(Tier_1,Tier_2,Tier_3,Tier_4,Tier_5,Tier_6, Tier_7,Tier_8) AGAINST ('{$terms}' IN BOOLEAN MODE) AS score
                FROM ccss_standards
                WHERE MATCH(Tier_1,Tier_2,Tier_3,Tier_4,Tier_5,Tier_6, Tier_7,Tier_8) AGAINST ('{$terms}' IN BOOLEAN MODE)
                AND catalogID IN ({$catalogList})
                ORDER BY score DESC, gradelevel DESC";
        
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