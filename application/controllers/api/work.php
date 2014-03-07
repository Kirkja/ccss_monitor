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
            
            foreach($query->result() as $row) {
                $record = array();
                foreach($row as $key => $value) {
                    $record[$key] = $value;
                }
                $data[] = $record;
            }
            
            echo json_encode($data);
        }
    }
}

?>