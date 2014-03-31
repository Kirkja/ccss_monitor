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

    
    /**
     * 
     */
    public function getassignments() {
        
        $raw = file_get_contents("php://input");
        $tmp = json_decode($raw);
        
        $mode = $tmp->mode;
        
        $modeText = $mode == "open" ? " IS NULL " : " IS NOT NULL ";
        
        // Gather all the available OPEN blocks assigned to user
        
        //$activeID = '95466432296386561';
        
        $activeID = GSAuth::GetUserObject()->activeID;
        
        $sql = "SELECT MBU.blockID, MBU.userID, DATE_FORMAT(MBU.dueON, '%M %e, %Y') AS dueON,
                BB.label
                FROM `map_block_user` AS MBU
                LEFT JOIN bank_block AS BB ON BB.id = MBU.blockID
                LEFT JOIN login AS LI ON LI.userID = MBU.userID
                WHERE MBU.blockID IS NOT NULL
                AND MBU.completedON {$modeText}
                AND MBU.active = 'y'
                AND LI.id = {$activeID}
                ORDER BY MBU.dueON, BB.label"
                ;
                               
        $query = $this->db->query($sql);

        $data = array();
        
        if ($query->num_rows() > 0) {

            foreach ($query->result() as $block) {

                $item['label']      = $block->label;
                $item['id']         = $block->blockID;
                $item['cashValue']  = 0.00;
                $item['dueON']      = $block->dueON;
                $item['children']   = array();
                
                //-- Gather all the available samples for a specific block
                $sqlB = "SELECT 
                            CONCAT(BS.label,'-',MIS.sequence) AS sampleLabel
                          , MSB.blockID
                          , MSB.sampleID
                          , MIS.imageID
                          , CONCAT(BI.imagePath, BI.imageName) AS image
                          , CASE BI.payRate
                          WHEN 'A' THEN  0.50 * 2.50
                          WHEN 'B' THEN  0.50 * 1.50
                          WHEN 'C' THEN  0.50 * 1.00
                          WHEN 'D' THEN  0.50 * 0.75
                          WHEN 'E' THEN  0.50 * 0.50
                          WHEN 'F' THEN  0.50 * 0.25
                          ELSE 0.50
                          END AS cashValue
                          FROM map_sample_block AS MSB
                          JOIN map_image_sample AS MIS ON MIS.sampleID = MSB.sampleID
                          JOIN bank_image AS BI ON BI.id = MIS.imageID
                          JOIN bank_sample AS BS ON BS.id = MSB.sampleID
                          WHERE MSB.active= 'y'
                          AND MSB.blockID = {$block->blockID}
                          GROUP BY MSB.blockID, MIS.sampleID, MIS.imageID";
                
                $queryB = $this->db->query($sqlB);

                if ($queryB->num_rows() > 0) {

                    foreach ($queryB->result() as $blockB) {
                        $c = array();                       
                        $c['label']         = trim($blockB->sampleLabel);
                        $c['id']            = $blockB->sampleID;
                        $c['image']         = $blockB->image;
                        $c['imageID']       = $blockB->imageID;
                        $c['cashValue']     = $blockB->cashValue;
                        $c['blockID']       = $block->blockID;
                        $c['blockName']     = $block->label;
                        
                        // test is there is a review, then mark as completed
                        // just place the date completed
                        $c['completed'] = $mode == 'open' ? 'n' : 'y';
                        
                        // samples have no children, but its needed just in case
                        $c['children']  = array();

                        $item['children'][] = $c;
                        
                        // add the sample cash value to the block
                        $item['cashValue'] += $blockB->cashValue;
                    }
                }

                $data[] = $item;
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

     
    
    public function getReview() {
        $raw = file_get_contents("php://input");
        $tmp = json_decode($raw);
        
        $data = array();
                
        $data[] = array("name" => "last", "type" => "text", "label" => "Last Name", "value" => "Jones");
        $data[] = array("name" => "first", "type" => "text", "label" => "First Name", "value" => "Ben");
        $data[] = array("name" => "address", "type" => "text", "label" => "Address", "value" => "321 Something Lane");
        $data[] = array("name" => "checker", "type" => "checkbox", "label" => "Checker", "value" => "checked");
        
        $data[] = array("name" => "DOK[]", "type" => "radio", "label" => "DOK", "value" => array(
                array("label"=>"DOK-1", "value"=>"DOK-1", "selected"=>""),
                array("label"=>"DOK-2", "value"=>"DOK-2", "selected"=>"true"),
                array("label"=>"DOK-3", "value"=>"DOK-3", "selected"=>""),
                array("label"=>"DOK-4", "value"=>"DOK-4", "selected"=>"")
            ));
        
        $data[] = array("name" => "Blooms[]", "type" => "select", "label" => "Blooms", "value" => array(
                array("label"=>"BLM-1", "value"=>"BLM-1", "selected"=>""),
                array("label"=>"BLM-2", "value"=>"BLM-2", "selected"=>"selected"),
                array("label"=>"BLM-3", "value"=>"BLM-3", "selected"=>""),
                array("label"=>"BLM-4", "value"=>"BLM-4", "selected"=>""),
                array("label"=>"BLM-5", "value"=>"BLM-5", "selected"=>""),
                array("label"=>"BLM-6", "value"=>"BLM-6", "selected"=>""),            
            
            ));                
        $data[] = array("name" => "DOKB[]", "type" => "select", "label" => "DOK", "value" => array(
                array("label"=>"DOK-1", "value"=>"DOK-1", "selected"=>""),
                array("label"=>"DOK-2", "value"=>"DOK-2", "selected"=>""),
                array("label"=>"DOK-3", "value"=>"DOK-3", "selected"=>"selected"),
                array("label"=>"DOK-4", "value"=>"DOK-4", "selected"=>"")
            ));        
        
        // Create the correct JSON payloads
        $out = array('data' => $data);

        // Set the correct JSON response header
        header('Content-Type: application/json');
        echo json_encode($out);        
    }
    
    
    
    
    
    
    
    
    //========================================================================

    private function loadFolder($row) {
        $record = array();
        foreach ($row as $key => $value) {
            $record[$key] = $value;
        }

        return $record;
    }

}

?>
