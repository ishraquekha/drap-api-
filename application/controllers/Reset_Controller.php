<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reset_Controller extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->language('common');
        $this->load->database();

        $this->load->model('user_api_model');

    }
    
    public function changepassword($key, $emailhash,$check)
    {
        if($check==1)
        {
        $data = $this->user_api_model->cheakForResetdoctor($key);
        $id = $this->user_api_model->getiddoctor($key);
        // print_r($id['id']);die;
        $Email = $data['email'];
        // $Id['id'] = $id;
        $res['id']=$id['id'];
        $res['check']=$check;
        if($data > 0)
        {
            $emailhash2 = md5($Email);
            // print_r($emailhash2);die;
            if($emailhash == $emailhash2)
            {
                $this->load->view('resetpassword', $res);
            }
            else{
                echo "Inavlid Email.";
            }
        }
        else{
            echo "Invalid Link.";
        }
        }
        else{
        $data = $this->user_api_model->cheakForResetpatient($key);
        $id = $this->user_api_model->getidpatient($key);
        // print_r($id);die;
        $Email = $data['email'];
        // $Id['id'] = $id;
        $res['id']=$id['id'];
        $res['check']=$check;
        if($data > 0)
        {
            $emailhash2 = md5($Email);
            // print_r($emailhash2);die;
            if($emailhash == $emailhash2)
            {
                $this->load->view('resetpassword', $res);
            }
            else{
                echo "Inavlid Email.";
            }
        }
        else{
            echo "Invalid Link.";
        }
    }
    }

    public function Reset()
    {
        $data = $this->input->post();
        $id = $data['id'];
        $check = $data['check'];
        $pass1 = $data['psw'];
        $pass2 = $data['cpsw'];
        if($check==1)
        {
        $password = md5($pass1);
        if($pass1 == $pass2){
            $update = $this->user_api_model->updatePassworddoctor($password, $id);
            if($update != null)
            {
                $this->load->view('resetsuccess');
            }
            else{
                echo "Sorry, Something went wrong!";
            }
        }
        else{
            echo "Password Does not matched.";
        }
        }
        else
        {
            $password = md5($pass1);
            if($pass1 == $pass2){
                $update = $this->user_api_model->updatePasswordpatient($password, $id);
                if($update != null)
                {
                    $this->load->view('resetsuccess');
                }
                else{
                    echo "Sorry, Something went wrong!";
                }
            }
            else{
                echo "Password Does not matched.";
            }
        }
    }
}