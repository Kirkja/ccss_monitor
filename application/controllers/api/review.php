<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Review extends CI_Controller {

    /**
     * 
     */
    public function index() {
        // Automatically exit. Non one needs to access the root
        //
        exit();
    }
    
    
    public function getReviewData() {
        
        $raw = file_get_contents("php://input");
        $tmp = json_decode($raw);
        
        //$activeID = '95466432296386561';
        //$blockID  = '95473327128182789';
        //$sampleID = '95466432296386569';
        
        
        $activeID   = GSAuth::GetUserObject()->activeID;
        $blockID    = $tmp->blockID;
        $sampleID   = $tmp->sampleID;
        $imageID    = $tmp->imageID;
        
        //$blockID  = '95473327128182789';
        //$sampleID = '95466432296386569'; 
                
        
        
        $sql = "SELECT RD.*
                FROM `review_data` AS RD
                JOIN login AS LI ON LI.userID = RD.createdBY
                WHERE RD.blockID = {$blockID}
                AND RD.sampleID = {$sampleID}
                AND RD.imageID = {$imageID} 
                AND LI.id = {$activeID}
                GROUP BY RD.createdBY, RD.accountID, RD.projectID, RD.blockID, RD.sampleID, RD.imageID, RD.id
                ORDER BY RD.accountID, RD.projectID, RD.blockID, RD.sampleID, RD.imageID, RD.groupingID, RD.groupingOrder
                ";
        
        
        $data = array();
        
        $query = $this->db->query($sql);
        
        //echo $sql;
        
  
        if ($query->num_rows() > 0) {

            $record = array();
            $idx = 1;
            foreach ($query->result() as $row) {
                
                if (!array_key_exists($row->groupingID, $data)) {
                    $data[$row->groupingID] = array();
                }
                
                $data[$row->groupingID][$idx++] = array(
                    'dataName'  => $row->dataName
                  , 'recordID'  => $row->id 
                  , 'dataValue' => $row->dataValue
                );
            } 
            
        }
        
        
        
        
        // Create the correct JSON payloads
        $out = array('data' => $data);

        // Set the correct JSON response header
        header('Content-Type: application/json');
        echo json_encode($out);

        //  DEBUG ONLY
        //echo "<pre>" . print_r($out, true) . "</pre>";                
    }
    
    
    
    
    
    
}
?>
