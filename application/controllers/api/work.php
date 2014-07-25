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
        if (!$activeID) {             
            header('Content-Type: application/json');
            echo json_encode(array('data'=>'invalid'));
            exit();             
        }
        //-------------------------        
        
        $raw = file_get_contents("php://input");
        $tmp = json_decode($raw);
        
        if (!isset($tmp->mode))     { exit(); }           
        
        $modeText = $tmp->mode == "open" ? " IS NULL " : " IS NOT NULL ";
        
        // Gather all the available OPEN work folders assigned to the user
        $sql = "SELECT MBU.blockID, MBU.userID, DATE_FORMAT(MBU.dueON, '%M %e, %Y') AS dueON,
                BB.label, BB.alphaCode
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
                $item['alphaCode']  = $block->alphaCode;
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
                          , BI.alphaCode
                          , MSC.collectorID
                            FROM map_sample_block AS MSB
                            JOIN map_image_sample AS MIS ON MIS.sampleID = MSB.sampleID
                            JOIN bank_image AS BI ON BI.id = MIS.imageID
                            JOIN bank_sample AS BS ON BS.id = MSB.sampleID
                            JOIN bank_block AS BB ON BB.id = MSB.blockID
                            JOIN map_sample_collector AS MSC ON MSC.sampleID = MSB.sampleID
                            WHERE MSB.active= 'y'
                            AND MIS.active = 'y'
                            AND BS.active = 'y'
                            AND BB.active = 'y'
                            AND BI.active = 'y'                            
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
                        $c['alphaCode']     = $blockB->alphaCode;
                        $c['collectorID']   = $blockB->collectorID;
                        
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
                    
                    $data[] = $item;
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

     
    /*
    public function gatherSampleMeta() {
                
        $activeID = GSAuth::Fence();        
        if (!$activeID) { exit(); } 
                
        $sql = "SELECT 
                BC.subjectArea, BC.gradeLevel
                , MSC.sampleID, MSC.collectorID
                , MCSite.siteID
                , MSP.projectID
                , COUNT(MIS.imageID) AS images
                , MGG.gradeBand
                , BS.label AS sampleName
                , BSite.st_abbr as state
                FROM map_sample_collector AS MSC
                LEFT JOIN bank_sample AS BS ON BS.id = MSC.sampleID
                LEFT JOIN bank_collector AS BC ON BC.id = MSC.collectorID
                LEFT JOIN map_collector_site MCSite ON MCSite.collectorID = MSC.collectorID
                LEFT JOIN map_image_sample AS MIS ON MIS.sampleID = MSC.sampleID
                LEFT JOIN map_site_project AS MSP ON MSP.siteID = MCSite.siteID
                LEFT JOIN map_gradelevel_gradeband AS MGG ON MGG.gradeLevel = BC.gradeLevel
                LEFT JOIN bank_site AS BSite ON BSite.id = MCSite.siteID
                WHERE MSP.active = 'y'
                AND MCSite.active = 'y'
                AND MSC.active = 'y'
                AND BC.active = 'y'
                AND MIS.active = 'y'
                GROUP BY state, MSP.projectID, MCSite.siteID, BC.gradeLevel, BC.subjectArea, MSC.collectorID, MSC.sampleID
                HAVING images > 0
                ORDER BY state,CONVERT(BC.gradeLevel, SIGNED INTEGER) DESC, BC.subjectArea, MSC.collectorID, MSC.sampleID";
        
        $query = $this->db->query($sql);
        
        $blocks         = array();
        $threshold      = 30;
        $imageCount     = 0;
        $idx            = 0;
        
        $currentSubject     = "";
        $currentGradeLevel  = 0;
        $currentState = "";
        
        $idxFlag = false;
        
        if ($query->num_rows() > 0) {

            foreach ($query->result() as $row) {
                 
                if ($currentState != $row->state) {
                    $currentState = $row->state;
                    $idxFlag = true;
                }                
                
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
                    'sampleName'    => $row->sampleName,
                    'gradeBand'     => $row->gradeBand,
                    'state'         => $row->state
                    );
            }

        }
        
        // build out the links
        $sampleCount = 0;
        $blockCount =0;
        
        foreach ($blocks as $blockData) {
            
            if ($this->checkSampleBlock($blockData) == 0) {
                
                $blockCount +=1;
                
                //$alphaCode  = $this->createUniqueInTable(8, 'bank_block', 'label');

                $alphaCode  = GSUtil::createAlphaCode(8, 'bank_block', 'label');
                
                $blockLabel = $blockData[0]['subjectArea'] ." - ". $blockData[0]['gradeLevel'];

                $blockID    = $this->createBlock($blockLabel, $alphaCode);
                
                $this->mapCatalogsToBlock($blockID, $blockData[0]['subjectArea'], $blockData[0]['state']);
                
                $sampleCount += $this->mapSamplesToBlock($blockID, $blockData);
                
                $this->mapUsersToBlock($blockID, $blockData[0]['subjectArea'], $blockData[0]['gradeBand']);
                
            }
            else {
                echo "<div>Possible collision in sample to block mapping</div>";
            }
           
        } 
        
        echo "Processed {$sampleCount} samples into {$blockCount} working folders";
    }
    
    */
    
    
    /*
    private function mapCatalogsToBlock($blockID, $subjectArea, $state) {
        $sql = "SELECT 
                BC.id, BC.state, BC.year, BC.label, MSS.subjectband 
                FROM bank_catalog AS BC
                LEFT JOIN map_subjectarea_subjectband AS MSS ON MSS.subjectband = BC.subjectBand
                WHERE MSS.subjectArea = '{$subjectArea}'
                AND BC.state = '$state'";
                
        $query = $this->db->query($sql);
        
        if ($query->num_rows() > 0) {
            echo "<ul>";
            foreach($query->result() as $row) {
                echo "<li>adding catalog for {$row->state} {$row->year} {$row->label}</li>";
                                
                $sql = "INSERT INTO `map_catalog_block` (id, catalogID, blockID, active) 
                        VALUES (UUID_SHORT(), {$row->id}, {$blockID}, 'y') 
                        ON DUPLICATE KEY UPDATE active = VALUES(active)";
                        
                //$this->db->query($sql);
            }
            echo "</ul>";
        }
    }
    
    */
    
    
    /*
    private function mapUsersToBlock($blockID, $subjectArea, $gradeBand) {
        echo "<p>Assigning users who prefer {$gradeBand} {$subjectArea} for block {$blockID}</p>";
        
        $assignments = array();
        
        $sql = "SELECT 
                MRU.*
                , user.nameLast, user.nameFirst
                FROM `map_rprefs_user` AS MRU
                LEFT JOIN `map_subjectarea_subjectband` AS MSS ON MSS.subjectBand = MRU.subjectBand
                LEFT JOIN bank_user as user ON user.id = MRU.userID
                WHERE MSS.subjectArea = '{$subjectArea}'
                AND MRU.gradeBand = '{$gradeBand}'";
                
        $query = $this->db->query($sql);
        
        if ($query->num_rows() > 0)
        {   
            echo "<ol>";
            foreach ($query->result() as $row) {
                if (isset($row->nameLast)) { 
                    echo "<li>{$row->nameLast}, {$row->nameFirst}</li>";  
                    
                    $sqlB = "INSERT INTO `map_block_user` (id, blockID, userID, active) 
                            VALUES (UUID_SHORT(), {$blockID}, {$row->userID}, 'y') 
                            ON DUPLICATE KEY UPDATE active = VALUES(active)";
                    
                    //$this->db->query($sqlB);
                                    
                }
            }
            echo "</ol>";
        }
    }
    
    */
    
    
    
    /*
    private function createBlock($label, $alphaCode) {
        //$blockID = $this->getUuidInt();
        
        $blockID = GSUtil::getUUID();
        
        echo "<div>Creating block {$label} [{$blockID}]</div>";
                
        $sql = "INSERT INTO bank_block 
            (id, label, createdON, createdBY, active, alphaCode) VALUES
            ({$blockID}, '{$label}', NOW(), 1, 'y', '{$alphaCode}')";
        
        //$query = $this->db->query($sql);
        
        return $blockID;
    }
    
    */
    
    
    /*
    private function mapSamplesToBlock($blockID, $samples) {
        
        $sampleCount = 0;
        
        echo "<ul>";
        
        foreach ($samples as $sample) {
            
            $sampleCount +=1;

            $sampleLabel = GSUtil::createAlphaCode(8, 'map_sample_block', 'label');
            
            echo "<li>Mapping {$sampleLabel} [{$sample['sampleID']}]  = {$sample['subjectArea']}, {$sample['gradeBand']}, {$sample['images']}</li>";
            
            
            $sql = "INSERT INTO map_sample_block 
                    (id, sampleID, blockID, label, createdON, createdBY, active) VALUES
                    (UUID_SHORT(), {$sample['sampleID']}, {$blockID}, '{$sampleLabel}', NOW(), 1, 'y')";
            
            //$this->db->query($sql);
                        
            $sqlB = "UPDATE bank_sample SET label = '{$sampleLabel}', active= 'y' WHERE id = {$sample['sampleID']} LIMIT 1";            
            
            //$this->db->query($sqlB);
        } 
        
        echo "</ul>";
        
        return $sampleCount;
    }
    
    */
    
    //========================================================================

    private function loadFolder($row) {
        $record = array();
        foreach ($row as $key => $value) {
            $record[$key] = $value;
        }

        return $record;
    }



    /*
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
    */
    
    
    
    
}

?>
