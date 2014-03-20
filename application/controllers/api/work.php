<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Work extends CI_Controller {

    /**
     * 
     */
    public function index() {
        // Automatically exit. Non one needs to access the root
        //
        exit();
    }

    public function getwork() {

        $sql = "SELECT 
                BA.assignedTO,
                BA.blockID, 
                BB.label, 
                BB.itemCount, 
                CASE payRate
                        WHEN 'A' THEN  BB.payBase * BB.itemCount * 2.5
                        WHEN 'B' THEN  BB.payBase * BB.itemCount * 1.5
                        WHEN 'C' THEN  BB.payBase * BB.itemCount * 1.0 
                        WHEN 'D' THEN  BB.payBase * BB.itemCount * 0.75
                        WHEN 'E' THEN  BB.payBase * BB.itemCount * 0.50
                        WHEN 'F' THEN  BB.payBase * BB.itemCount * 0.25
                        ELSE BB.payBase * BB.itemCount
                END cashValue,
                BA.dueON, 
                BA.completedON
                FROM block_assignment AS BA
                LEFT JOIN block_bank AS BB ON BB.id = BA.blockID
                LEFT JOIN login AS LI ON LI.userID = BA.assignedTO
                WHERE LI.id = '802387cc-a583-11e3-aa5d-0014d16a86e4'
                ORDER BY BA.dueON";

        $query = $this->db->query($sql);

        $data = array();

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $row) {
                $record = array();
                foreach ($row as $key => $value) {
                    $record[$key] = $value;
                }

                $record['children'] = array();
                $data[] = $record;
            }

            echo json_encode($data);
        }
    }

    
    public function getwork2() {
        
        $id = "802387cc-a583-11e3-aa5d-0014d16a86e4";

        $sql = "SELECT
                BA.blockID, BA.assignedTO, DATE_FORMAT(BA.dueON, '%M %e, %Y') as dueON,
                BB.label
                FROM `block_assignment` AS BA
                JOIN `login` AS LI ON LI.userID = BA.assignedTO
                LEFT JOIN `block_bank` AS BB ON BB.id = BA.blockID
                WHERE BA.active = 'y'
                AND LI.userStatus = 'a'
                AND LI.id = '{$id}'
                AND BA.completedON IS NULL 
                ORDER BY BA.dueON, BB.label";

        $query = $this->db->query($sql);

        $data = array();

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $block) {

                $item['label']      = $block->label;
                $item['id']         = $block->blockID;
                $item['cashValue']  = 0.00;
                $item['dueON']      = $block->dueON;
                $item['children']   = array();
               
                $sqlB = "SELECT 
                        samples.id AS id,
                        samples.sampleName as label,
                        samples.sampleImage,
                        samples.samplePath,
                        CASE samples.payRate
                                WHEN 'A' THEN  0.50 * 2.50
                                WHEN 'B' THEN  0.50 * 1.50
                                WHEN 'C' THEN  0.50 * 1.00
                                WHEN 'D' THEN  0.50 * 0.75
                                WHEN 'E' THEN  0.50 * 0.50
                                WHEN 'F' THEN  0.50 * 0.25
                                ELSE 0.50
                        END AS cashValue,
                        SR.reviewedON
                        FROM `block_samples` AS BS
                        LEFT JOIN `sample_bank` AS samples ON samples.id = BS.sampleID
                        LEFT JOIN sample_review SR ON (SR.blockID = BS.blockID AND SR.sampleID = BS.sampleID)
                        WHERE BS.blockID = '{$block->blockID}'
                        AND BS.active = 'y'";

                $queryB = $this->db->query($sqlB);

                if ($queryB->num_rows() > 0) {
                   
                   foreach ($queryB->result() as $blockB) {
                        $c = array();
                        $c['label'] = trim($blockB->label);
                        $c['id'] = $blockB->id;
                        $c['imageName'] = $blockB->sampleImage;
                        $c['imagePath'] = $blockB->samplePath;
                        $c['cashValue'] = $blockB->cashValue;  
                        if ($blockB->reviewedON) {
                            $c['completed']  = 'completed';
                        }
                        else {
                            $c['completed']  = '';
                        }
                        
                        $c['children'] = array();
                                          
                        $item['children'][] = $c;
                        
                        $item['cashValue'] += $blockB->cashValue;
                    }
                }
                             
                $data[] = $item;                                
            }
            
            // Create the correct JSON payloads
            $out = array('data'=>$data);
            
            // Set the correct JSON response header
           header('Content-Type: application/json');
           echo json_encode($out);          
           
           //  DEBUG ONLY
           //echo "<pre>" . print_r($out, true) . "</pre>";
        }
    }

    
    
    public function getwork3() {
        
        $id = "802387cc-a583-11e3-aa5d-0014d16a86e4";
        $sql = "SELECT
                BA.blockID, BA.assignedTO, DATE_FORMAT(BA.dueON, '%M %e, %Y') as dueON,
                BB.label
                FROM `block_assignment` AS BA
                JOIN `login` AS LI ON LI.userID = BA.assignedTO
                LEFT JOIN `block_bank` AS BB ON BB.id = BA.blockID
                WHERE BA.active = 'y'
                AND LI.userStatus = 'a'
                AND LI.id = '{$id}'
                AND BA.completedON IS NOT NULL 
                ORDER BY BA.dueON, BB.label";

        $query = $this->db->query($sql);

        $data = array();

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $block) {

                $item['label']      = $block->label;
                $item['id']         = $block->blockID;
                $item['cashValue']  = 0.00;
                $item['dueON']      = $block->dueON;
                $item['children']   = array();
               
                $sqlB = "SELECT 
                        samples.id AS id,
                        samples.sampleName as label,
                        samples.sampleImage,
                        samples.samplePath,
                        CASE samples.payRate
                                WHEN 'A' THEN  0.50 * 2.50
                                WHEN 'B' THEN  0.50 * 1.50
                                WHEN 'C' THEN  0.50 * 1.00
                                WHEN 'D' THEN  0.50 * 0.75
                                WHEN 'E' THEN  0.50 * 0.50
                                WHEN 'F' THEN  0.50 * 0.25
                                ELSE 0.50
                        END AS cashValue,
                        SR.reviewedON
                        FROM `block_samples` AS BS
                        LEFT JOIN `sample_bank` AS samples ON samples.id = BS.sampleID
                        LEFT JOIN sample_review SR ON (SR.blockID = BS.blockID AND SR.sampleID = BS.sampleID)
                        WHERE BS.blockID = '{$block->blockID}'
                        AND BS.active = 'y'";

                $queryB = $this->db->query($sqlB);

                if ($queryB->num_rows() > 0) {
                   
                   foreach ($queryB->result() as $blockB) {
                        $c = array();
                        $c['label'] = trim($blockB->label);
                        $c['id'] = $blockB->id;
                        $c['imageName'] = $blockB->sampleImage;
                        $c['imagePath'] = $blockB->samplePath;
                        $c['cashValue'] = $blockB->cashValue;  
                        if ($blockB->reviewedON) {
                            $c['completed']  = 'completed';
                        }
                        else {
                            $c['completed']  = '';
                        }
                        
                        $c['children'] = array();
                                          
                        $item['children'][] = $c;
                        
                        $item['cashValue'] += $blockB->cashValue;
                    }
                }
                             
                $data[] = $item;                                
            }
            
            // Create the correct JSON payloads
            $out = array('data'=>$data);
            
            // Set the correct JSON response header
           header('Content-Type: application/json');
           echo json_encode($out);          
           
           //  DEBUG ONLY
           //echo "<pre>" . print_r($out, true) . "</pre>";
        }
    }
    
    
    
    public function getreview() {
        
        // read in the POST, and parse into into an object 
        $raw = file_get_contents("php://input"); 
        $tmp = json_decode($raw);           
        
        
        $form = array();
        $form['first']  = array('type' => 'text', 'label' => 'First Name', 'val' => 'Ben');
        $form['last']   = array('type' => 'text', 'label' => 'Last Name', 'val' => 'Jones');
        $form['block']  = array('type' => 'text', 'label' => 'Block', 'val' => $tmp->blockID);
        $form['submit'] = array('type' => 'submit');
        
        $stub = array();
        $stub[] = array('name' =>'first', 'type' => 'text', 'label' => 'First Name', 'val' => 'Ben');
        $stub[] = array('name' =>'last','type' => 'text', 'label' => 'Last Name', 'val' => 'Jones');
        $stub[] = array('name' =>'blockID','type' => 'text', 'label' => 'Block ID', 'val' => $tmp->blockID);
        $stub[] = array('name' =>'sampleID','type' => 'text', 'label' => 'Sample ID', 'val' => $tmp->sampleID);

        $data = array();
        
        $data['message'] = "Recieved userID =  " . $tmp->userID .", blockID = " . $tmp->blockID;
        $data['template'] = $form;
        $data['form'] = $stub;
        
        
        $out = array('data'=>$data);
        
        // Set the correct JSON response header
        header('Content-Type: application/json');
        echo json_encode($out);            
    }
    
    
    
    
    private function loadFolder($row) {
        $record = array();
        foreach ($row as $key => $value) {
            $record[$key] = $value;
        }

        return $record;
    }

}

?>