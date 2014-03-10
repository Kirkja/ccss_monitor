<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Settings extends CI_Controller {

    /**
     * 
     */
    public function index() {
        // Automatically exit. Non one needs to access the root
        //
        exit();
    }

    public function login() {

        $tmp = array();
        $data = array();

        if (GSAuth::IsActive()) {
            $tmp['ScreenName'] = "Dummy Screen Name";
            $tmp['UserName'] = 'DummyUserName';
            $tmp['UserCode'] = 'DummyPassword';
        }
        $data[] = $tmp;

        echo json_encode($data);
    }

    public function user() {

        if (GSAuth::IsActive()) {
            $tmp = array();

            $tmp['screenName']  = GSAuth::GetUserObject()->screenName;
            $tmp['userName']    = GSAuth::GetUserObject()->userName;
            $tmp['activeID']    = GSAuth::GetUserObject()->activeID;;
            $tmp['userCode']    = '';

            echo json_encode($tmp);
        }
    }
    
    
    public function id() {
         if (GSAuth::IsActive()) {
             echo GSAuth::GetUserObject()->activeID;
         }
    }

    public function userupdate() {

        if (GSAuth::IsActive()) {
            $data = array();
            $errors = array();

            $x = $this->getPost();

            if (!$x) {
                $errors['name'] = "No incoming data";
            }

            if (!empty($errors)) {
                $data['success'] = false;
                $data['errors'] = $errors;
            } else {
                $data['success'] = true;
                $data['message'] = print_r($x, true);
            }

            echo json_encode($data);
        }
    }

    private function getPost() {
        $raw = file_get_contents("php://input");
        return json_decode($raw);
    }

}
