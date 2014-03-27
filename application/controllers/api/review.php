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
              
        $activeID   = GSAuth::GetUserObject()->activeID;
        $blockID    = $tmp->blockID;
        $sampleID   = $tmp->sampleID;
        $imageID    = $tmp->imageID;
              
        // Create the correct JSON payloads
        $out = array('data' => 
            $this->extractReviews($activeID, $blockID, $sampleID, $imageID)
        );

        // Set the correct JSON response header
        header('Content-Type: application/json');
        echo json_encode($out);

        //  DEBUG ONLY
        //echo "<pre>" . print_r($out, true) . "</pre>";                
    }
    
    
    public function delSCR() {
        $raw = file_get_contents("php://input");
        $tmp = json_decode($raw);        
        
        $activeID   = GSAuth::GetUserObject()->activeID;;
        $blockID    = $tmp->blockID;
        $sampleID   = $tmp->sampleID;
        $imageID    = $tmp->imageID;
        $groups     = $tmp->groups;
                
        $this->deleteReviews($activeID, $blockID, $sampleID, $imageID, $groups);
        
        $out = array('data' => 
            $this->extractReviews($activeID, $blockID, $sampleID, $imageID)
        );

        // Set the correct JSON response header
        header('Content-Type: application/json');
        echo json_encode($out);
        
        //  DEBUG ONLY
        //echo "<pre>" . print_r($out, true) . "</pre>";        
    }
    
    
    
    public function addSCR() {
        $raw = file_get_contents("php://input");
        $tmp = json_decode($raw);        
        
        $activeID   = GSAuth::GetUserObject()->activeID;;
        $blockID    = $tmp->blockID;
        $sampleID   = $tmp->sampleID;
        $imageID    = $tmp->imageID;
 
        $this->createSCR($activeID, $blockID, $sampleID, $imageID);
        
        $out = array('data' => 
            $this-> extractReviews($activeID, $blockID, $sampleID, $imageID)
        );

        // Set the correct JSON response header
        header('Content-Type: application/json');
        echo json_encode($out);
        
        //  DEBUG ONLY
        //echo "<pre>" . print_r($out, true) . "</pre>";        
    }
        
    
    public function updateSCR() {
        
        $raw = file_get_contents("php://input");
        $tmp = json_decode($raw); 
        
        
        $this->updateReviewRecord($tmp->id, $tmp->value);
        
                
        //$out = array('data' => 
        //    array('recordID' => $tmp->id, 'value' => $tmp->value)
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
    
    /**
     * 
     * @param type $recordID
     * @param type $value
     */
    private function updateReviewRecord($recordID, $value) {
        
        $sql = "UPDATE review_data SET dataValue = '{$value}' WHERE id={$recordID}";
        $this->db->query($sql);
    }
    
    
    
    
    /**
     * 
     * @param type $activeID
     * @param type $blockID
     * @param type $sampleID
     * @param type $imageID
     * @return type
     */
    private function extractReviews(
            $activeID, 
            $blockID, 
            $sampleID, 
            $imageID
        ) {
                
        $sql = "SELECT RD.*
                FROM `review_data` AS RD
                JOIN login AS LI ON LI.userID = RD.createdBY
                WHERE RD.active = 'y' 
                AND RD.blockID = {$blockID}
                AND RD.sampleID = {$sampleID}
                AND RD.imageID = {$imageID} 
                AND LI.id = {$activeID}
                GROUP BY RD.createdBY, RD.accountID, RD.projectID, RD.blockID, RD.sampleID, RD.imageID, RD.id
                ORDER BY RD.accountID, RD.projectID, RD.blockID, RD.sampleID, RD.imageID, RD.groupingID, RD.groupingOrder
                ";
                
        $data = array();
        
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {

            $record = array();
            $idx = 1;
            foreach ($query->result() as $row) {
                
                $key = sprintf("%04d",$row->groupingID);
                $id  = sprintf("%04d",$idx++);
                
                if (!array_key_exists($key, $data)) {
                    $data[$key] = array();
                }
                
                $data[$key][$id] = array(
                    'dataName'  => $row->dataName
                  , 'recordID'  => $row->id 
                  , 'dataValue' => $row->dataValue
                );
            }     
        }
        
        return $data;
    }
    
    
    
    /**
     * 
     * @param type $activeID
     * @param type $blockID
     * @param type $sampleID
     * @param type $imageID
     * @param type $groups
     */
    private function deleteReviews(
            $activeID, 
            $blockID, 
            $sampleID, 
            $imageID, 
            $groups
        ) {
        

        $ids = explode(",", $groups);
        $groupList = "";
        
        foreach ($ids as $item) {
            if (!empty($item)) { 
                $groupList .= " '{$item}' ,"; 
            }            
        }
        
        $groupList = substr($groupList, 0, strlen($groupList)-1);
                
        $sql = "UPDATE review_data
                SET active= 'n'
                WHERE blockID = {$blockID} 
                AND sampleID = {$sampleID} 
                AND imageID = {$imageID} 
                AND groupingID IN ({$groupList})
                ";
        
        $this->db->query($sql);                                  
    }
    
    
    /**
     * 
     * @param type $activeID
     * @param type $blockID
     * @param type $sampleID
     * @param type $imageID
     */
    private function createSCR(            
            $activeID, 
            $blockID, 
            $sampleID, 
            $imageID
        ) {
          
        $accountID    = 1;
        $projectID  = 1;
        
        $resp = $this->getNextRDID($activeID, $blockID, $sampleID, $imageID);
        
        if ($resp->gid > 0) {
            $resp->gid += 1;
            $sql = "INSERT INTO review_data 
                (id,accountID, projectID, blockID, sampleID, imageID, groupingID, createdBY, groupingOrder, createdON, dataName,dataValue,dataType)
                VALUES 
                (UUID_SHORT(), {$accountID},{$projectID}, {$blockID},{$sampleID},{$imageID},{$resp->gid}, {$resp->userID},1,NOW(),'id','?','text'),
                (UUID_SHORT(), {$accountID},{$projectID}, {$blockID},{$sampleID},{$imageID},{$resp->gid}, {$resp->userID},2,NOW(),'standard','no standard','button'),
                (UUID_SHORT(), {$accountID},{$projectID}, {$blockID},{$sampleID},{$imageID},{$resp->gid}, {$resp->userID},3,NOW(),'dok','','select'),
                (UUID_SHORT(), {$accountID},{$projectID}, {$blockID},{$sampleID},{$imageID},{$resp->gid}, {$resp->userID},4,NOW(),'blm','','select'),
                (UUID_SHORT(), {$accountID},{$projectID}, {$blockID},{$sampleID},{$imageID},{$resp->gid}, {$resp->userID},5,NOW(),'counter','?','text')
                ";
                
            $this->db->query($sql);
        }
        
    }
    
    /**
     * 
     * @param type $activeID
     * @param type $blockID
     * @param type $sampleID
     * @param type $imageID
     * @return type
     */
    private function getNextRDID(
            $activeID, 
            $blockID, 
            $sampleID, 
            $imageID) {
        
        $nextID = -1;
        
        $sqlA ="SELECT MAX(groupingID) AS gid, login.userID 
            FROM review_data
            JOIN login ON login.userID = review_data.createdBY
            WHERE login.id = {$activeID}
            AND blockID = {$blockID}
            AND sampleID = {$sampleID}
            AND imageID = {$imageID}";
            
        $query = $this->db->query($sqlA);
        
        if ($query->num_rows() > 0) {
            $nextID = $query->row();            
        }   
        
        return $nextID;
    }
    
    
}
?>
