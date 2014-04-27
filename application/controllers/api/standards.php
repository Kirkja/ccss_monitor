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
                
        if (!isset($tmp->blockID))     { exit(); }   
                
        // Create the correct JSON payloads
        $out = array('data' => 
            $this->findCatalogs($tmp->blockID)
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
        
        //$data   = array();
        $raw    = file_get_contents("php://input");
        $tmp    = json_decode($raw);
        
        if (!isset($tmp->cid))     { exit(); }   

        // Create the correct JSON payloads
        $out = array('data' => 
            $this->extractCatalog($tmp->cid, $activeID)
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
        
        if (!isset($tmp->cidList))      { exit(); }   
        if (!isset($tmp->terms))        { exit(); }   

        // Create the correct JSON payloads
        $out = array('data' => 
            $this->catalogSearch($tmp->cidList, $tmp->terms)
        );

        // Set the correct JSON response header
        header('Content-Type: application/json');
        echo json_encode($out);

        //  DEBUG ONLY
        //echo "<pre>" . print_r($out, true) . "</pre>";            
    }    
    
    
    
    
    /*
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
    */
    
    
    
    
    //==========================================================================
    // Private Methods
    //
    
    /**
     * 
     * @param type $activeID
     * @param type $catalogID
     */
    private function extractCatalog($catalogID, $activeID) {
       
        
        /*                
        $sql = "SELECT 
                CCSS.id
              , CONCAT(CCSS.state,'_',CCSS.key0) AS standardKey
              , CASE
                    WHEN CCSS.Tier_7 <> '' THEN CONCAT(CCSS.Tier_5, ' -- ', CCSS.Tier_6, ' --- ', CCSS.Tier_7)  
                    WHEN CCSS.Tier_6 <> '' THEN CONCAT(CCSS.Tier_5, ' -- ', CCSS.Tier_6) 
                    ELSE CCSS.Tier_5
                END AS standardText
                FROM bank_standards AS CCSS
                WHERE CCSS.Tier_5 <> ''
                AND catalogID = {$catalogID}
                ORDER BY CCSS.gradelevel, CONCAT(CCSS.state,'_',CCSS.key0)";
        */
        
        $sql = "SELECT 
                CCSS.id
                , CONCAT(CCSS.state,'_',CCSS.key0) AS standardKey
                , CASE
                    WHEN CCSS.Tier_8 <> '' THEN CONCAT(CCSS.Tier_5, ' -- ', CCSS.Tier_6, ' --- ', CCSS.Tier_7, ' ++ ', CCSS.Tier_8)  
                    WHEN CCSS.Tier_7 <> '' THEN CONCAT(CCSS.Tier_5, ' -- ', CCSS.Tier_6, ' --- ', CCSS.Tier_7)  
                    WHEN CCSS.Tier_6 <> '' THEN CONCAT(CCSS.Tier_5, ' -- ', CCSS.Tier_6) 
                    ELSE CCSS.Tier_5
                END AS standardText,
                SN.id AS noteID
                FROM bank_standards AS CCSS
                LEFT JOIN standard_note AS SN ON (
                      SN.standardID = CCSS.id
                  AND SN.active = 'y'
                )
                LEFT JOIN login AS LI ON (
                    LI.userID = SN.createdBY
                AND LI.id = {$activeID}
                )
                WHERE CCSS.Tier_5 <> ''
                AND CCSS.catalogID = {$catalogID}
                ORDER BY CCSS.gradelevel, CONCAT(CCSS.state,'_',CCSS.key0)";
        
        
        
        
        
        
        
        $query = $this->db->query($sql);
        
        $tmp = array();
        
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row)
            {
                $tmp[] = array(
                    'key'   => $row->standardKey,
                    'desc'  => $row->standardText,
                    'noteID'=> $row->noteID
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
                , CASE
                    WHEN Tier_7 <> '' THEN CONCAT(Tier_5, ' -- ', Tier_6, ' -- ', Tier_7)
                    WHEN Tier_6 <> '' THEN CONCAT(Tier_5, ' -- ', Tier_6) 
                    ELSE Tier_5
                    END AS standardText
                , MATCH(Key0,Tier_1,Tier_2,Tier_3,Tier_4,Tier_5,Tier_6, Tier_7,Tier_8) AGAINST ('{$terms}' IN BOOLEAN MODE) AS score
                FROM bank_standards
                WHERE MATCH(Key0,Tier_1,Tier_2,Tier_3,Tier_4,Tier_5,Tier_6, Tier_7,Tier_8) AGAINST ('{$terms}' IN BOOLEAN MODE)
                AND catalogID IN ({$catalogList})
                ORDER BY gradelevel ASC, score DESC";
        
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