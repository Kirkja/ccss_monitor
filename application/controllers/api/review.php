<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Functionality for use in the review items and notes panel
 */
class Review extends CI_Controller {

    /**
     * 
     */
    public function index() {
        // Automatically exit. No one needs to access the root
        //
        exit();
    }
    
    /**
     *  Return a JSON strin with any review items for the selected sample
     */
    public function getReviewData() {
        
        // Fence out any invalid users
        $activeID = GSAuth::Fence();        
        if (!$activeID) {             
            header('Content-Type: application/json');
            echo json_encode(array('data'=>'invalid'));
            exit();             
        }
        //-------------------------
        
        // Retrieve the POST data off the buffer
        $raw = file_get_contents("php://input");
        $tmp = json_decode($raw);
                  
        // Reject the process unless all the required values are preent
        if (!isset($tmp->blockID))     { exit(); }        
        if (!isset($tmp->sampleID))    { exit(); }                
        if (!isset($tmp->imageID))     { exit(); }                

        // Create the correct JSON payload from the SQL results
        $out = array('data' => 
            $this->getReviews(
                    $activeID, 
                    $tmp->blockID, 
                    $tmp->sampleID, 
                    $tmp->imageID)
        );

        // Set the correct JSON response header
        header('Content-Type: application/json');
        echo json_encode($out);

        //  DEBUG ONLY
        //echo "<pre>" . print_r($out, true) . "</pre>";                
    }
    
    
    
    /*
     * Delete the selected SCR row from the review items
     */
    public function delSCR() {
        
        $activeID = GSAuth::Fence();        
        if (!$activeID) { exit(); }
        //-------------------------        
        
        $raw = file_get_contents("php://input");
        $tmp = json_decode($raw);        
        
        if (!isset($tmp->blockID))     { exit(); }        
        if (!isset($tmp->sampleID))    { exit(); }                
        if (!isset($tmp->imageID))     { exit(); }           
        if (!isset($tmp->groups))      { exit(); }    
   
        $this->deleteReviews(
                $activeID, 
                $tmp->blockID, 
                $tmp->sampleID, 
                $tmp->imageID, 
                $tmp->groups);
        
        $out = array('data' => 
            $this->getReviews($activeID, 
                $tmp->blockID, 
                $tmp->sampleID, 
                $tmp->imageID)
        );

        // Set the correct JSON response header
        header('Content-Type: application/json');
        echo json_encode($out);
        
        //  DEBUG ONLY
        //echo "<pre>" . print_r($out, true) . "</pre>";        
    }
    
    
    
    public function addSCR() {
        
        $activeID = GSAuth::Fence();        
        if (!$activeID) { exit(); }
        //-------------------------
                
        $raw = file_get_contents("php://input");
        $tmp = json_decode($raw);        
          
        if (!isset($tmp->blockID))     { exit(); }        
        if (!isset($tmp->sampleID))    { exit(); }                
        if (!isset($tmp->imageID))     { exit(); }
 
        // insert an empty record for this review
        $this->createSCR(
                $activeID, 
                $tmp->blockID, 
                $tmp->sampleID, 
                $tmp->imageID,
                $tmp->collectorID);
        
        // return this newly created record
        $out = array('data' => 
            $this->getReviews(
                $activeID, 
                $tmp->blockID, 
                $tmp->sampleID, 
                $tmp->imageID)
        );

        // Set the correct JSON response header
        header('Content-Type: application/json');
        echo json_encode($out);
        
        //  DEBUG ONLY
        //echo "<pre>" . print_r($out, true) . "</pre>";        
    }
        
    
    
    
   public function addFilledSCR() {
       
        $activeID = GSAuth::Fence();        
        if (!$activeID) { exit(); }
        //-------------------------
                
        $raw = file_get_contents("php://input");
        $tmp = json_decode($raw);        
                
        if (!isset($tmp->blockID))     { exit(); }
        if (!isset($tmp->sampleID))    { exit(); }
        if (!isset($tmp->imageID))     { exit(); }
        if (!isset($tmp->stdKey))      { exit(); }
 
        $this->createFilledSCR(
                $activeID, 
                $tmp->blockID, 
                $tmp->sampleID, 
                $tmp->imageID, 
                $tmp->stdKey,
                $tmp->collectorID);
        
        $out = array('data' => 
            $this->getReviews(
                    $activeID, 
                    $tmp->blockID, 
                    $tmp->sampleID, 
                    $tmp->imageID)
        );

        // Set the correct JSON response header
        header('Content-Type: application/json');
        echo json_encode($out);
        
        //  DEBUG ONLY
        //echo "<pre>" . print_r($out, true) . "</pre>";        
    }
    
    

    
   public function addSpecial() {
       
        $activeID = GSAuth::Fence();        
        if (!$activeID) { exit(); }
        //-------------------------
                
        $raw = file_get_contents("php://input");
        $tmp = json_decode($raw);        
                
        if (!isset($tmp->blockID))     { exit(); }
        if (!isset($tmp->sampleID))    { exit(); }
        if (!isset($tmp->imageID))     { exit(); }
        if (!isset($tmp->stdKey))      { exit(); }
 
        $this->createSpecial(
                $activeID, 
                $tmp->blockID, 
                $tmp->sampleID, 
                $tmp->imageID, 
                $tmp->stdKey,
                $tmp->collectorID);
        
        $out = array('data' => 
            $this->getReviews(
                    $activeID, 
                    $tmp->blockID, 
                    $tmp->sampleID, 
                    $tmp->imageID)
        );

        // Set the correct JSON response header
        header('Content-Type: application/json');
        echo json_encode($out);
        
        //  DEBUG ONLY
        //echo "<pre>" . print_r($out, true) . "</pre>";        
    }
        
    
    
    
    
    public function updateSCR() {
        
        $activeID = GSAuth::Fence();        
        if (!$activeID) { exit(); }
        //-------------------------
                        
        $raw = file_get_contents("php://input");
        $tmp = json_decode($raw); 
                
        if (!isset($tmp->id))     { exit(); }        
        if (!isset($tmp->value))  { exit(); }      
        
        $this->updateReviewRecord($tmp->id, $tmp->value);
    }
    
    
    
    public function updateSTD() {
        
        $activeID = GSAuth::Fence();        
        if (!$activeID) { exit(); }
        //-------------------------
                        
        $raw = file_get_contents("php://input");
        $tmp = json_decode($raw); 
                     
        if (!isset($tmp->id))       { exit(); }        
        if (!isset($tmp->value))    { exit(); }                          
        
        $test = $this->testStandard($tmp->value);
        
        if ($test) {
            $this->updateReviewRecord($tmp->id, $tmp->value);
        }
        
        $out = array('data' => 
            $test
        );

        // Set the correct JSON response header
        header('Content-Type: application/json');
        echo json_encode($out);        
    }
    
    
    
    public function getNote() {
        
        $activeID = GSAuth::Fence();        
        if (!$activeID) { exit(); }
        //-------------------------
        
        $raw = file_get_contents("php://input");
        $tmp = json_decode($raw);         
        
        if (!isset($tmp->blockID))     { exit(); }
        if (!isset($tmp->sampleID))    { exit(); }
        if (!isset($tmp->imageID))     { exit(); }
        if (!isset($tmp->groupingID))  { exit(); }
                              
        $out = array('data' => 
            $this->extractNote(
                    $activeID, 
                    $tmp->blockID, 
                    $tmp->sampleID, 
                    $tmp->imageID, 
                    $tmp->groupingID)
        );

        // Set the correct JSON response header
        header('Content-Type: application/json');
        echo json_encode($out);                        
    }
    
    
    public function saveNote() {
        
        $activeID = GSAuth::Fence();  
        
        $t = array();
        
        if (!$activeID) { 
            $t['status'] = 'active failed';
            header('Content-Type: application/json');
            echo json_encode(array('data' => $t));             
        }
        else {
            $raw = file_get_contents("php://input");
            $tmp = json_decode($raw);  
            
            $userID = GSAuth::getUserID($activeID);
                    
            if ($userID < 0)                { $t[] = 'user failed'; }
            if (!isset($tmp->blockID))      { $t[] = 'block failed'; }
            if (!isset($tmp->sampleID))     { $t[] = 'sample failed'; }
            if (!isset($tmp->imageID))      { $t[] = 'image failed'; }
            if (!isset($tmp->groupingID))   { $t[] = 'group failed'; }
            if (!isset($tmp->noteID))       { $t[] = 'note failed'; }
            if (!isset($tmp->noteText))     { $t[] = 'text failed'; }
            if (!isset($tmp->mode))         { $t[] = 'mode failed'; }            

            if (count($t) > 0) {
                header('Content-Type: application/json');
                echo json_encode(array('data' => $t)); 
                exit();
            }
           
            if ($tmp->mode == "add") {
                $t = $this->addNote(
                     $userID
                    ,$tmp->blockID
                    ,$tmp->sampleID
                    ,$tmp->imageID
                    ,$tmp->groupingID
                    ,$tmp->noteID
                    ,$tmp->noteText);
            }
            else if ($tmp->mode == "update") {
                $t = $this->updateNote(                    
                     $tmp->noteID
                    ,$tmp->noteText);
            }            
            
            header('Content-Type: application/json');
            echo json_encode(array('data' => $t)); 
        }
    }
    
    
    public function xsaveNote() {
                        
        $t = array();
        
        $activeID = GSAuth::Fence(); 
                
        if (!$activeID) { 
            $t['status'] = 'active failed';
            header('Content-Type: application/json');
            echo json_encode(array('data' => $t));           
            exit();             
        }
        //-------------------------
        
        
        $raw = file_get_contents("php://input");
        $tmp = json_decode($raw);  
        
        $userID = GSAuth::getUserID($activeID);
        
        if ($userID < 0)                { $t[] = 'user failed'; }
        if (!isset($tmp->blockID))      { $t[] = 'block failed'; }
        if (!isset($tmp->sampleID))     { $t[] = 'sample failed'; }
        if (!isset($tmp->imageID))      { $t[] = 'image failed'; }
        if (!isset($tmp->groupingID))   { $t[] = 'group failed'; }
        if (!isset($tmp->noteID))       { $t[] = 'note failed'; }
        if (!isset($tmp->noteText))     { $t[] = 'text failed'; }
        if (!isset($tmp->mode))         { $t[] = 'mode failed'; }
                 
        if (size($t) > 0) {
            header('Content-Type: application/json');
            echo json_encode($out = array('data' => $t));
            exit();
        }
        
        $x = array('status' => 'failed');
       
        /*
        if ($tmp->mode == "add") {
            $x = $this->addNote(
                     $userID
                    ,$tmp->blockID
                    ,$tmp->sampleID
                    ,$tmp->imageID
                    ,$tmp->groupingID
                    ,$tmp->noteID
                    ,$tmp->noteText);
        }
        else if ($tmp->mode == "update") {
            $x = $this->updateNote(                    
                $tmp->noteID
                ,$tmp->noteText);
        }
        */
        
        $out = array('data' => $x);

        // Set the correct JSON response header
        header('Content-Type: application/json');
        echo json_encode($out);        
    }
    
    
 
    public function delNote() {

        $activeID = GSAuth::Fence();        
        if (!$activeID) { exit(); }
        //-------------------------
        
        $raw = file_get_contents("php://input");
        $tmp = json_decode($raw);         
        
        if (!isset($tmp->noteID))       { exit(); }
       
        $out = array('data' => 
            $this->eraseNote($tmp->noteID)
        );

        // Set the correct JSON response header
        header('Content-Type: application/json');
        echo json_encode($out);        
    }
       
    
    
    public function setBlank() {
        $activeID = GSAuth::Fence();        
        if (!$activeID) { exit(); }
        //-------------------------
        
        $raw = file_get_contents("php://input");
        $tmp = json_decode($raw);   
        
        if (!isset($tmp->imageID))       { exit(); }
        
        $this->setImageAsBlank($tmp->imageID);
        

        $out = array('data' => 
            "true"
        );

        // Set the correct JSON response header
        header('Content-Type: application/json');
        echo json_encode($out);             
    }
    
    
    
    public function completeFolder() {
        $activeID = GSAuth::Fence();        
        if (!$activeID) { exit(); }
        //-------------------------
        
        $raw = file_get_contents("php://input");
        $tmp = json_decode($raw);   
        
        if (!isset($tmp->bid))       { exit(); }
        
        
        $out = array('data' => 
            $this->setFolderCompleted($tmp->bid, $activeID)
        );

        // Set the correct JSON response header
        header('Content-Type: application/json');
        echo json_encode($out);        
    }
    
    
    //=======================================================================
    // PRIVATE METHODS
    //
    
    private function setFolderCompleted($blockID, $activeID) {
        
        $sql = "UPDATE `map_block_user` AS MBU
                LEFT JOIN login ON login.userID = MBU.userID
                SET completedON = NOW()
                WHERE MBU.blockID = {$blockID}
                AND login.id = {$activeID}";
        
        $result = $this->db->query($sql);
                
        return "true";
    }
    
    
    
    private function setImageAsBlank($imageID) {
        $sql = "UPDATE bank_image 
                SET active = 'b' 
                WHERE id = {$imageID} 
                LIMIT 1";
        
        $this->db->query($sql);
    }
    
    private function addNote(
            $userID,
            $blockID,
            $sampleID,
            $imageID,
            $groupingID,
            $noteID,
            $noteText) 
    {
         $sql = "INSERT INTO review_note  
                (id, blockID, sampleID, imageID, groupingID, 
                createdBY, createdON, note, active)
                VALUES (                       
                    UUID_SHORT(),                        
                    {$blockID},                      
                    {$sampleID},                      
                    {$imageID},                      
                    {$groupingID},                       
                    {$userID},                       
                    NOW(),                        
                    '{$noteText}',                        
                    'y'
                )";
        
        
        $a = $this->db->query($sql);
        
        return array (
            'status'    => $a
        );
             
    }
    
    
    
    private function updateNote(            
            $noteID,
            $noteText) {
               
        $sql = "UPDATE review_note
                    SET note = '{$noteText}'
                    WHERE id = {$noteID}
                    LIMIT 1";
                    
        $a = $this->db->query($sql);
        
        return array (
            'status'    => $a
        );
    }
    
    
    
    
    private function eraseNote($noteID) {
        
        if ($noteID > 0) {
            $sql = "UPDATE review_note
                    SET active = 'n'
                    WHERE id = {$noteID}
                    LIMIT 1;";
                    
            $this->db->query($sql);
        }
        else {
            
        }
        
        return "deleted";
    }
        
    
    
    
    private function extractNote(
            $activeID, 
            $blockID, 
            $sampleID, 
            $imageID, 
            $groupingID) {
        
        $sql = "SELECT RN.id, RN.note, 
                DATE_FORMAT(RN.createdON,'%b %d %Y, %h:%i %p') as createdON
                FROM review_note AS RN
                LEFT JOIN login AS LI ON LI.userID = RN.createdBY
                WHERE RN.active = 'y'
                AND RN.blockID = {$blockID}
                AND RN.sampleID = {$sampleID}
                AND RN.imageID = {$imageID}
                AND RN.groupingID = {$groupingID}
                AND LI.id = {$activeID} LIMIT 1";
        
        $query = $this->db->query($sql);
        
        $data = array();
        
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $data['id']     = $row->id;            
            $data['note']   = $row->note;
            $data['stamp']  = $row->createdON;            
        }
            
        return $data;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    /**
     * Tests whether the standard is a valid on record
     * 
     * @param type $stdKey
     * @return boolean
     */
    private function testStandard($stdKey) {
        
        $acceptable = array(
            'NIKS'
        );
        
        if (in_array($stdKey, $acceptable)) {
            return true;
        }
        
        $sql ="SELECT * 
                FROM bank_standards AS CCSS
                WHERE CCSS.Tier_2 <> ''
                AND CONCAT(CCSS.state,'_',CCSS.key0) = '{$stdKey}'
                GROUP BY CCSS.state, CCSS.key0
                LIMIT 1";
                
        $query = $this->db->query($sql);
        
        if ($query->num_rows() > 0) { return true; }
        
        return false;
    }
    
    
    
    
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
    
    private function getReviews($aid, $bid, $sid, $iid) {
        
        $sql = "SELECT RD.*,
            RN.id AS noteID
            FROM `review_data` AS RD
            JOIN login AS LI ON LI.userID = RD.createdBY
            LEFT JOIN review_note AS RN ON (
                        RN.blockID = RD.blockID
                    AND RN.sampleID = RD.sampleID
                    AND RN.imageID = RD.imageID
                    AND RN.createdBY = LI.userID
                    AND RN.groupingID = RD.groupingID
                    AND RN.active = 'y'
            )
            WHERE RD.active = 'y' 
            AND RD.blockID = {$bid}
            AND RD.sampleID = {$sid}
            AND RD.imageID = {$iid}
            AND LI.id = {$aid}
            GROUP BY RD.createdBY, RD.accountID, RD.projectID, RD.blockID, RD.sampleID, RD.imageID, RD.id
            ORDER BY RD.accountID, RD.projectID, RD.blockID, RD.sampleID, RD.imageID, RD.groupingID, RD.groupingOrder";

        
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
                
                $data[$key]['cell'][$id] = array(
                    'dataName'  => $row->dataName
                  , 'recordID'  => $row->id 
                  , 'dataValue' => $row->dataValue
                );
                
                $data[$key]['note'] = $row->noteID;
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
            $imageID,
            $collectorID)
    {
          
        $accountID  = 1;
        $projectID  = 1;
        
        $resp = $this->getNextRDID($activeID, $blockID, $sampleID, $imageID);
        
        $resp->gid = $resp->gid == null ? 1 : $resp->gid;
        
        if ($resp->gid > 0) {
            $resp->gid += 1;
            $sql = "INSERT INTO review_data 
                (id,accountID, projectID, collectorID, blockID, sampleID, imageID, groupingID, createdBY, groupingOrder, createdON, dataName,dataValue,dataType)
                VALUES 
                (UUID_SHORT(), {$accountID},{$projectID},{$collectorID}, {$blockID},{$sampleID},{$imageID},{$resp->gid}, {$resp->userID},1,NOW(),'id','','text'),
                (UUID_SHORT(), {$accountID},{$projectID},{$collectorID}, {$blockID},{$sampleID},{$imageID},{$resp->gid}, {$resp->userID},2,NOW(),'standard','','button'),
                (UUID_SHORT(), {$accountID},{$projectID},{$collectorID}, {$blockID},{$sampleID},{$imageID},{$resp->gid}, {$resp->userID},3,NOW(),'dok','','select'),
                (UUID_SHORT(), {$accountID},{$projectID},{$collectorID}, {$blockID},{$sampleID},{$imageID},{$resp->gid}, {$resp->userID},4,NOW(),'blm','','select'),
                (UUID_SHORT(), {$accountID},{$projectID},{$collectorID}, {$blockID},{$sampleID},{$imageID},{$resp->gid}, {$resp->userID},5,NOW(),'counter','','text')
                ";
                
            $this->db->query($sql);
        }        
    }
    
    
    
    private function createFilledSCR(            
            $activeID,             
            $blockID, 
            $sampleID, 
            $imageID,
            $std,
            $collectorID
        ) {
          
        $accountID  = 1;
        $projectID  = 1;
        
        $resp = $this->getNextRDID($activeID, $blockID, $sampleID, $imageID);
        
        $resp->gid = $resp->gid == null ? 1 : $resp->gid;
        
        if ($resp->gid > 0) {
            $resp->gid += 1;
            $sql = "INSERT INTO review_data 
                (id,accountID, projectID, collectorID, blockID, sampleID, imageID, groupingID, createdBY, groupingOrder, createdON, dataName,dataValue,dataType)
                VALUES 
                (UUID_SHORT(), {$accountID},{$projectID},{$collectorID}, {$blockID},{$sampleID},{$imageID},{$resp->gid}, {$resp->userID},1,NOW(),'id','','text'),
                (UUID_SHORT(), {$accountID},{$projectID},{$collectorID}, {$blockID},{$sampleID},{$imageID},{$resp->gid}, {$resp->userID},2,NOW(),'standard','{$std}','button'),
                (UUID_SHORT(), {$accountID},{$projectID},{$collectorID}, {$blockID},{$sampleID},{$imageID},{$resp->gid}, {$resp->userID},3,NOW(),'dok','','select'),
                (UUID_SHORT(), {$accountID},{$projectID},{$collectorID}, {$blockID},{$sampleID},{$imageID},{$resp->gid}, {$resp->userID},4,NOW(),'blm','','select'),
                (UUID_SHORT(), {$accountID},{$projectID},{$collectorID}, {$blockID},{$sampleID},{$imageID},{$resp->gid}, {$resp->userID},5,NOW(),'counter','','text')
                ";
                
            $this->db->query($sql);
        }        
    }
    
    private function createSpecial(         
            $activeID, 
            $blockID, 
            $sampleID, 
            $imageID,
            $std,
            $collectorID
        ) {
          
        $accountID  = 1;
        $projectID  = 1;
        
        $resp = $this->getNextRDID($activeID, $blockID, $sampleID, $imageID);
        
        $resp->gid = $resp->gid == null ? 1 : $resp->gid;
        
        if ($resp->gid > 0) {
            $resp->gid += 1;
            $sql = "INSERT INTO review_data 
                (id,accountID, projectID, collectorID, blockID, sampleID, imageID, groupingID, createdBY, groupingOrder, createdON, dataName,dataValue,dataType)
                VALUES 
                (UUID_SHORT(), {$accountID},{$projectID}, {$collectorID}, {$blockID}, {$sampleID},{$imageID},{$resp->gid}, {$resp->userID},1,NOW(),'id','','text'),
                (UUID_SHORT(), {$accountID},{$projectID}, {$collectorID}, {$blockID}, {$sampleID},{$imageID},{$resp->gid}, {$resp->userID},2,NOW(),'special','{$std}','select'),
                (UUID_SHORT(), {$accountID},{$projectID}, {$collectorID}, {$blockID}, {$sampleID},{$imageID},{$resp->gid}, {$resp->userID},5,NOW(),'counter','','text')
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
