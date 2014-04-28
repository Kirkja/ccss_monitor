<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class UserObject {
    public $ScreenName;
    public $activeID;    
}

class GSAuth {

    private static $CI          = null;
    private static $userString  = null;
    private static $activeID    = null;    
    public static  $user         = null;
    
    
    public function __construct() {
        GSAuth::$CI = & get_instance();        
        GSAuth::$user = new UserObject();
    }

   
    
    /**
     * 
     * @return boolean
     */
    public function IsActive() {
        
        if (GSAuth::$CI->session->userdata('isActive')) {
            return GSAuth::$CI->session->userdata('isActive');
        }
        
        return false;
    }
    
    
    /**
     * 
     * @param type $userName
     * @return boolean
     */
    public function IsActiveUser($userName) {
        
        if (GSAuth::$CI->session->userdata('isActive')) {
            if (GSAuth::$CI->session->userdata('userName') == $userName) {
                return GSAuth::$CI->session->userdata('isActive');
            }
        }
        
        return false;
    } 
    

/**
 * 
 * @return type
 */
    public function GetActiveUser() {
        $user = null;

        if (GSAuth::IsActive()) {
            $user = GSAuth::$CI->session->userdata;
        }

        return $user;        
    } 
  
    
    
/**
 * 
 * @return type
 */
    public function GetUserObject() {
        $user = new stdClass();

        if (GSAuth::IsActive()) {
            $array = GSAuth::$CI->session->userdata;
            foreach ($array as $key => $value)
            {
                $user->$key = $value;
            }            
        }

        return $user;        
    }     
    
    public function getUserID($activeID) {
       
        $sql = "SELECT * FROM login WHERE id = {$activeID} LIMIT 1";
        $query = $this->db->query($sql);
       
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->userID;
        }
        
        return -1;
    }

    
    /**
     * 
     * @param type $un
     * @param type $uc
     * @return boolean
     */
    public function Validate($un, $uc) {
        
        if (isset($un) && isset($uc)) {
            // prevent database flooding
            $un = trim(substr(trim($un), 0, 32));
            $uc = trim(substr(trim($uc), 0, 32));

            // is not already logged in and the login info is valid
            if (!GSAuth::IsActiveUser($un) && GSAuth::IsValidUser($un, $uc)) {
                GSAuth::$CI->session->set_userdata('activeID', GSAuth::$activeID);
                GSAuth::$CI->session->set_userdata('userName', $un);
                GSAuth::$CI->session->set_userdata('userCode', $uc);               
                GSAuth::$CI->session->set_userdata('screenName', GSAuth::$userString);
                GSAuth::$CI->session->set_userdata('isActive', true);
                GSAuth::$CI->session->set_userdata('position', "Analyst");
                
                
                GSAuth::$user->screenName = "test only";
                GSAuth::$user->activeID = GSAuth::$activeID;
                                
                return true;
            }
        }        
        
        return false;
    }    
    
    
    
    /**
     * 
     * @return type
     */
    public function leave() {
        GSAuth::$CI->session->sess_destroy();
        return base_url();
    }     
    
    
    
    public function Gate() {
        if (!GSAuth::$CI->session->userdata('isActive')) {
            GSAuth::$CI->session->sess_destroy();
            redirect(base_url() . "entry");
        }
    }
    
    
    
    public function Fence() {
        if (!GSAuth::$CI->session->userdata('isActive')) {
            GSAuth::$CI->session->sess_destroy();
            return null; //redirect(base_url() . "entry");
        }
        else {
            return GSAuth::GetUserObject()->activeID;
        }
    }    
    
    //=========================================================================
    // Private methods
    //.........................................................................
    
    
    /**
     * 
     * @param type $userName
     * @param type $userCode
     * @return boolean
     */
    private function IsValidUser($userName, $userCode) {
          
        $sql ="SELECT
            LI.id as activeID,
            LI.loginName, LI.loginCode,
            CONCAT(U.nameFirst, \" \", U.nameLast) AS userString 
            FROM login AS LI
            LEFT JOIN bank_user AS U ON U.id = LI.userID
            WHERE LI.loginName COLLATE latin1_general_cs = '{$userName}'
            AND LI.loginCode COLLATE latin1_general_cs = '{$userCode}'
            AND userStatus = 'a' 
            LIMIT 1";
                    
        $query = GSAuth::$CI->db->query($sql);

        if ($query->num_rows() > 0) {

            // Set current user Info
            $row                  = $query->row();
            GSAuth::$userString   = $row->userString;
            GSAuth::$activeID     = $row->activeID;

            return true;
        }  
        
        return false;
    }    
    
}
