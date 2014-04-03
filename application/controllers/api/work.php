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
        
        $activeID = GSAuth::Fence();        
        if (!$activeID) { exit(); }
        //-------------------------        
        
        $raw = file_get_contents("php://input");
        $tmp = json_decode($raw);
        
        if (!isset($tmp->mode))     { exit(); }           
        
        $modeText = $tmp->mode == "open" ? " IS NULL " : " IS NOT NULL ";
        
        // Gather all the available OPEN work folders assigned to the user
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
                            WHEN 'A' THEN  BB.baseValue * 2.50
                            WHEN 'B' THEN  BB.baseValue * 1.50
                            WHEN 'C' THEN  BB.baseValue * 1.00
                            WHEN 'D' THEN  BB.baseValue * 0.75
                            WHEN 'E' THEN  BB.baseValue * 0.50
                            WHEN 'F' THEN  BB.baseValue * 0.25
                            ELSE BB.baseValue
                            END AS cashValue
                            FROM map_sample_block AS MSB
                            JOIN map_image_sample AS MIS ON MIS.sampleID = MSB.sampleID
                            JOIN bank_image AS BI ON BI.id = MIS.imageID
                            JOIN bank_sample AS BS ON BS.id = MSB.sampleID
                            JOIN bank_block AS BB ON BB.id = MSB.blockID
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
                        $c['completed'] = $tmp->mode == 'open' ? 'n' : 'y';
                        
                        // samples have no children, but its needed just in case
                        $c['children']  = array();

                        // add the samples as children to the block
                        $item['children'][] = $c;
                        
                        // add the sample cash value to the block
                        $item['cashValue'] += $blockB->cashValue;
                    }
                     $item['cashValue'] = sprintf('%01.2f',$item['cashValue']);
                    
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

     
    
    public function gatherSampleMeta() {
                
        $activeID = GSAuth::Fence();        
        if (!$activeID) { exit(); } 
        
        $sql = "SELECT
                            BP.accountID 
                          , MSP.projectID
                          , BC.siteID, MSC.collectorID, MSC.sampleID
                          , BC.subjectArea, BC.gradeLevel
                          , COUNT(MIS.imageID) AS images
                          , BS.label AS sampleName
                  FROM map_sample_collector AS MSC
                  LEFT JOIN bank_collector AS BC ON BC.id = MSC.collectorID
                  LEFT JOIN map_site_project AS MSP ON MSP.siteID = BC.siteID
                  LEFT JOIN bank_project AS BP ON BP.id = MSP.projectID
                  LEFT JOIN bank_sample AS BS ON BS.id = MSC.sampleID
                  LEFT JOIN map_image_sample AS MIS ON MIS.sampleID = MSC.sampleID
                  WHERE BP.active = 'y'
                          AND MSP.active = 'y'
                          AND MSC.active = 'y'
                          AND BC.active = 'y'
                          AND BS.active = 'y'
                          AND MIS.active = 'y'
                  GROUP BY BP.accountID
                          , MSP.projectID
                          , BC.siteID
                          , MSC.collectorID
                          , MSC.sampleID
                          , BC.subjectArea
                          , BC.gradeLevel	
                  HAVING images > 0";
        
        $query = $this->db->query($sql);
        
        $blocks         = array();
        $threshold      = 2;
        $imageCount     = 0;
        $idx            = 0;
        
        $currentSubject     = "";
        $currentGradeLevel  = 0;
        
        $idxFlag = false;
        
        if ($query->num_rows() > 0) {

            foreach ($query->result() as $row) {
                  

                if ($currentSubject != $row->subjectArea) {
                    $currentSubject = $row->subjectArea;
                    $idxFlag = true;
                }
                
                if ($currentGradeLevel != $row->gradeLevel) {
                    $currentGradeLevel  = $row->gradeLevel;               
                    $idxFlag = true;
                }
                
                
                if ($imageCount > $threshold) {
                    $idxFlag = true;
                }
                
                if ($idxFlag) {
                    $idx++;
                    $idxFlag = false;
                    $imageCount = 0;
                }
                
                $imageCount += $row->images;
               
                $blocks[$idx][] = array(
                    'sampleID'      => $row->sampleID,
                    'subjectArea'   => $currentSubject,
                    'gradeLevel'    => $currentGradeLevel,
                    'images'        => $row->images,
                    'sampleName'    => $row->sampleName
                    );
            }
            
            //echo "<pre>". print_r($blocks, true) ."</pre>";
        }
        
        
        foreach ($blocks as $blockData) {
            //echo "<pre>". print_r($block, true) . "</pre>";
            if ($this->checkSampleBlock($blockData) == 0) {
                $blockLabel = $this->createAplphaNumericLabel(10);

                $blockID = $this->createBlock($blockLabel);
                
                $this->mapSamplesToBlock($blockID, $blockData);
            }
            else {
                echo "<div>Possible collision in sample to block mapping</div>";
            }
            //echo "<p/>";
        }
        
        
        
    }
    
    private function createBlock($label) {
        echo "<div>Creating block {$label}</div>";
        
        return "some-block-id";
    }
    
    
    private function mapSamplesToBlock($blockID, $samples) {
        
        echo "<ul>";
        
        foreach ($samples as $sample) {
            
            $sampleLabel = $this->createAplphaNumericLabel(10);
            
            echo "<li>Mapping {$sample['sampleID']} as {$sampleLabel}</li>";
            
        } 
        
        echo "</ul>";
    }
    
    
    
    //========================================================================

    private function loadFolder($row) {
        $record = array();
        foreach ($row as $key => $value) {
            $record[$key] = $value;
        }

        return $record;
    }

    
    private function checkSampleBlock($block) {
        
        $ids = "";

        foreach ($block as $sample) {
            $ids .= "{$sample['sampleID']}";
            $ids .= ",";
        }
        
        $ids =  trim($ids, ",");
        
        $sql = "SELECT 
                * FROM map_sample_block 
                WHERE sampleID 
                IN ( {$ids} )";
                
        $query = $this->db->query($sql);
        
        return $query->num_rows();        
    }
    
    private function createAplphaNumericLabel($length) {
        $letters = array('A','B','C','D','E', 'F','G','H','J','K','L','M','N','P','Q','R','S','T','U','W','X','Y','Z');
        $digits = array('2','3','5','6','7','8','9');
        
        $keys = array_merge($letters, $digits);
        
        $key = "";
        
        for ($idx=0; $idx < $length; $idx++) {
            $key .= $keys[array_rand($keys)];
        }
        
        return $key;
    }
    
    
    
    
    
}

?>
