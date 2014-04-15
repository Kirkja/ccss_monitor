<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class GSUtil {

    private static $CI          = null;
    
    
    public function __construct() {
        GSUtil::$CI = & get_instance();        
    }
    
    
    
    public function createAlphaCode($length, $tableName, $fieldName) {
        return GSUtil::createUniqueInTable($length, $tableName, $fieldName);
    }
    
    public function getUUID() {
        return GSUtil::getUuidInt();
    }
    
    
    
    public function GetView($filename, $data)
    {
        if (file_exists(APPPATH."views/{$filename}.php")) {
            return $this->load->view($filename, $data, true);
        }
        else {
            return null;
        }
    }    
    
    //#########################################################################
    //
    //
    
    
    private function getUuidInt() {
        $sql = "SELECT UUID_SHORT() as id";
        $query = GSUtil::$CI->db->query($sql);
        return $query->row()->id;
    }    

    private function createUniqueInTable($length, $tableName, $fieldName) {
        
        $notUnique = true;
    
        $label = "";
        
        do {
            $label  = GSUtil::createAplphaNumericLabel($length);
            $sql    = "SELECT {$fieldName} from {$tableName} where {$fieldName} = '{$label}' LIMIT 1";        
            $query  = GSUtil::$CI->db->query($sql);        
            
            if ($query->num_rows() == 0) { $notUnique = false; }
        }
        while ($notUnique);
        
        return $label;
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