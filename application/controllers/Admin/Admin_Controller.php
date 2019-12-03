<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_Controller extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->language('common');
        $this->load->library('session');
        $this->load->library('upload');
        $this->load->model('Admin/Admin_Model');

    }

    public function login()
    {
        $this->load->view('Admin/login');
    }

    public function dashboard()
    {
        $data = $this->input->post();
        $email = $data['username'];
        $password = $data['pass'];

        $result = $this->Admin_Model->admincheak($email,$password);
        $this->session->set_userdata('admindata',$data);

        if($result != null)
        {   
            $this->session->set_flashdata('success', 'Login successfully');
            $doctor = $this->Admin_Model->getdoctorsdata();
            $request = $this->Admin_Model->getdoctorsrequest();
            $patient = $this->Admin_Model->getpatientsdata();
            $transaction = $this->Admin_Model->gettransactionsdata();
            $sp = $this->Admin_Model->getspacializations();
            $appointments = $this->Admin_Model->getappointments();
            $count['doctors'] = count($doctor);
            $count['request'] = count($request);
            $count['patient'] = count($patient);
            $count['transaction'] = count($transaction);
            $count['spacial'] = count($sp);
            $count['appoints'] = count($appointments);
            $this->template->load('template', 'contents' , 'Admin/dashboard' ,$count);
        }
        else{
            $this->session->set_flashdata('error', 'Something is wrong.');
            redirect('/Admin');
        }
    }
    public function dash()
    {
        $data = $this->session->userdata('admindata');
        if($data != null)
        {    
            $doctor = $this->Admin_Model->getdoctorsdata();
            $request = $this->Admin_Model->getdoctorsrequest();
            $patient = $this->Admin_Model->getpatientsdata();
            $transaction = $this->Admin_Model->gettransactionsdata();
            $sp = $this->Admin_Model->getspacializations();
            $appointments = $this->Admin_Model->getappointments();
            $count['doctors'] = count($doctor);
            $count['request'] = count($request);
            $count['patient'] = count($patient);
            $count['transaction'] = count($transaction);
            $count['spacial'] = count($sp);
            $count['appoints'] = count($appointments);
            $this->template->load('template', 'contents' , 'Admin/dashboard' ,$count);
        }
        else{
            echo "Invalid credential";
            $this->load->view('Admin/login');
        }
    }

    public function doctorslist()
    {
        
        $data['doctors'] = $this->Admin_Model->getdoctorsdata();
        $this->template->load('template', 'contents' , 'Admin/doctors' ,$data);

    }

    public function patientslist()
    {
        $data['patients'] = $this->Admin_Model->getpatientsdata();
        $this->template->load('template', 'contents' , 'Admin/patients' ,$data);

    }

    public function requestlist()
    {
        $data['doctors'] = $this->Admin_Model->getdoctorsrequest();
        $this->template->load('template', 'contents' , 'Admin/doctors' ,$data);
    }

    public function transactionlist()
    {
        $data['transaction'] = $this->Admin_Model->gettransactionsdata();
        $this->template->load('template', 'contents' , 'Admin/transaction' ,$data);

    }

    public function doctorsdetails($id)
    {
        $doctors['data'] = $this->Admin_Model->getdoctordetails($id);
        $doctors['list'] = $this->Admin_Model->getspacializations();
        $updaterequest = $doctors['data']['updaterequest'];
        // print_r($doctors);die;
        if($updaterequest==1){
            // echo "yes";die;
            $doctors['data'] = $this->Admin_Model->getupdatedrequest($id);
            $this->template->load('template', 'contents' , 'Admin/doctorsupdateddetails' ,$doctors);
        }
        else{
        $this->template->load('template', 'contents' , 'Admin/doctorsdetails' ,$doctors);
        }
    }

    public function updatedoctor()
    {
        $data = $this->input->post();    
        $id = $data['id'];
        date_default_timezone_set("Asia/Calcutta");
        $date = date("Y-m-d H:i:s");

        if(!empty($_FILES['image']['name'])){
            $path = './uploads/doctors/';
            $config['upload_path'] = $path;
            $config['allowed_types'] = 'avi|mpg|mpeg|wmv|gif|jpg|png|jpeg'; 
            $config['max_size'] = 0; 

                $new_name = time() . '-' .$_FILES["image"]['name']; 
                $config['file_name'] = $new_name;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('image')) 
                    {
                    $error = array('error' => $this->upload->display_errors());
                    print_r($error);
                    }

                $updatedoctor['profilepic'] = base_url().'/'.$path.'/'.$new_name;
                $updatedoctor['firstname'] = $data['first_name'];
                $updatedoctor['middlename'] = $data['middle_name'];
                $updatedoctor['lastname'] = $data['last_name'];
                $updatedoctor['email'] = $data['email'];
                $updatedoctor['phone'] = $data['phone'];
                $updatedoctor['address'] = $data['location'];
                $updatedoctor['gender'] = $data['gender'];
                $updatedoctor['dateofbirth'] = $data['dateofbirth'];
                $updatedoctor['colleaguenumber'] = $data['colleaguenumber'];
                $updatedoctor['title'] = $data['title'];
                $updatedoctor['experience'] = $data['experience'];
                $updatedoctor['experiencedetails'] = $data['experiencedetails'];
                $updatedoctor['consultationrate'] = $data['consultationrate'];
                $updatedoctor['consultationrateunit'] = $data['consultationrateunit'];
                $updatedoctor['consultationtime'] = $data['consultationtime'];
                $specialid['spacialistid'] = $data['speciality'];
                // print_r($specialid);die;    
                $this->Admin_Model->updatedoctor($updatedoctor,$id,$date);
                $res=$this->Admin_Model->checkspecial($id);
                
                if($res!=null){
                        if($specialid['spacialistid']!=0){
                    $this->Admin_Model->updatespeciality($id,$specialid);
                        }    
                }
                    else{
                        if($specialid['spacialistid']!=0){
                        $arr['doctorsid']=$id;
                        $arr['spacialistid']=$specialid['spacialistid'];
                        
                        $this->Admin_Model->insertspeciality($arr);
                        }
                    }

                if (array_key_exists("verify",$data))
                {
                    $this->session->set_flashdata('success', 'User Updated successfully');
                    $this->Admin_Model->activate($id);
                     ////old redirect method
                    // $data['doctors'] = $this->Admin_Model->getdoctorsdata();
                    // print_r($data['doctors']);die;
                    // $this->template->load('template', 'contents' , 'Admin/doctors' ,$data);
                    redirect('/doctors');
                }
                else{
                    $this->session->set_flashdata('success', 'User Updated successfully');
                    $this->Admin_Model->deactivate($id);
                   redirect('/requests');
                    // print_r($data['doctors']);die;

                     ////old redirect method
                    // $data['doctors'] = $this->Admin_Model->getdoctorsdata();
                    // $this->template->load('template', 'contents' , 'Admin/doctors' ,$data);
                    
                }
            }
            else{
                $updatedoctor['firstname'] = $data['first_name'];
                    $updatedoctor['middlename'] = $data['middle_name'];
                    $updatedoctor['lastname'] = $data['last_name'];
                    $updatedoctor['email'] = $data['email'];
                    $updatedoctor['phone'] = $data['phone'];
                    $updatedoctor['address'] = $data['location'];
                    $updatedoctor['gender'] = $data['gender'];
                    $updatedoctor['dateofbirth'] = $data['dateofbirth'];
                    $updatedoctor['colleaguenumber'] = $data['colleaguenumber'];
                    $updatedoctor['title'] = $data['title'];
                    $updatedoctor['experience'] = $data['experience'];
                    $updatedoctor['experiencedetails'] = $data['experiencedetails'];
                    $updatedoctor['consultationrate'] = $data['consultationrate'];
                    $updatedoctor['consultationrateunit'] = $data['consultationrateunit'];
                    $updatedoctor['consultationtime'] = $data['consultationtime'];
                    $specialid['spacialistid'] = $data['speciality'];
                    // print_r($specialid['spacialistid']);die;
                    $this->Admin_Model->updatedoctor($updatedoctor,$id,$date);
                    $res=$this->Admin_Model->checkspecial($id);
                    
                    if($res!=null){
                        if($specialid['spacialistid']!=0){
                            // echo'hello';
                    $this->Admin_Model->updatespeciality($id,$specialid);
                        }
                    }
                    else{
                        if($specialid['spacialistid']!=0){
                        $arr['doctorsid']=$id;
                        $arr['spacialistid']=$specialid['spacialistid'];
                    
                        $this->Admin_Model->insertspeciality($arr);
                        }
                    }
                    if (array_key_exists("verify",$data))
                    {
                        $this->Admin_Model->activate($id);
                         ////old redirect method
                        // $data['doctors'] = $this->Admin_Model->getdoctorsdata();
                        // $this->session->set_flashdata('success', 'Doctor Updated successfully');
                        // $this->template->load('template', 'contents' , 'Admin/doctors' ,$data);
                        redirect('/doctors');
                    }
                    else{
                        $this->Admin_Model->deactivate($id);
                        ////old redirect method
                        // $data['doctors'] = $this->Admin_Model->getdoctorsdata();
                        // $this->session->set_flashdata('success', 'Doctor Updated successfully');
                        // $this->template->load('template', 'contents' , 'Admin/doctors' ,$data);
                        // print_r($data);die;
                        redirect('/requests');
                    }
            }
        
    }

    public function updatepatient()
    {
        $data = $this->input->post();
        $id = $data['id'];

         if(!empty($_FILES['image']['name'])){
            $path = './uploads/patients/';
            $config['upload_path'] = $path;
            // $config['allowed_types'] = 'jpg|png|jpeg'; 
            $config['allowed_types'] = 'avi|mpg|mpeg|wmv|gif|jpg|png|jpeg'; 
            $config['max_size'] = 0; 

                $new_name = time() . '-' .$_FILES["image"]['name']; 
                $config['file_name'] = $new_name;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('image')) 
                    {
                    $error = array('error' => $this->upload->display_errors());
                    print_r($error);
                    }
                
                $updatepatient['profilepic'] = base_url().'/'.$path.'/'.$new_name;
                $updatepatient['firstname'] = $data['first_name'];
                $updatepatient['middlename'] = $data['middle_name'];
                $updatepatient['lastname'] = $data['last_name'];
                $updatepatient['email'] = $data['email'];
                $updatepatient['phone'] = $data['phone'];
                $updatepatient['address'] = $data['location'];
                $updatepatient['gender'] = $data['gender'];
                $updatepatient['dateofbirth'] = $data['dateofbirth'];
                
                $this->Admin_Model->updatepatient($updatepatient, $id);

                // $updatepatientmedical['medicalhistory'] = $data['medicalhistory'];
                // $updatepatientmedical['surgeries'] = $data['surgeries'];
                // // $updatepatientmedical['drugtaker'] = $data['drugtaker'];
                // // $updatepatientmedical['isallergictomedications'] = $data['isallergictomedications'];
                // $updatepatientmedical['allergictomedications'] = $data['allergictomedications'];
                // $updatepatientmedical['familybackground'] = $data['familybackground'];

                // $this->Admin_Model->updatepatientmedical($updatepatientmedical, $id);

            // $data['patients'] = $this->Admin_Model->getpatientsdata();
            // $this->session->set_flashdata('success', 'Patient Updated successfully');
            // $this->template->load('template', 'contents' , 'Admin/patients' ,$data);
            redirect('/patients');

                }
                else{

                    $updatepatient['firstname'] = $data['first_name'];
                    $updatepatient['middlename'] = $data['middle_name'];
                    $updatepatient['lastname'] = $data['last_name'];
                    $updatepatient['email'] = $data['email'];
                    $updatepatient['phone'] = $data['phone'];
                    $updatepatient['address'] = $data['location'];
                    $updatepatient['gender'] = $data['gender'];
                    $updatepatient['dateofbirth'] = $data['dateofbirth'];
                    
                    $this->Admin_Model->updatepatient($updatepatient, $id);
    
                    // $updatepatientmedical['medicalhistory'] = $data['medicalhistory'];
                    // $updatepatientmedical['surgeries'] = $data['surgeries'];
                    // // $updatepatientmedical['drugtaker'] = $data['drugtaker'];
                    // // $updatepatientmedical['isallergictomedications'] = $data['isallergictomedications'];
                    // $updatepatientmedical['allergictomedications'] = $data['allergictomedications'];
                    // $updatepatientmedical['familybackground'] = $data['familybackground'];
    
                    // $result = $this->Admin_Model->updatepatientmedical($updatepatientmedical, $id);
                    // print_r($result);die;
                    ////////previous retdirect
                // $data['patients'] = $this->Admin_Model->getpatientsdata();
                // $this->session->set_flashdata('success', 'Patient Updated successfully');
                // $this->template->load('template', 'contents' , 'Admin/patients' ,$data);
                redirect('/patients');    
            }
    }

    public function deletedoctor($id)
    {
        $result = $this->Admin_Model->deletedoctor($id);
        If($result != null){
            $data['doctors'] = $this->Admin_Model->getdoctorsdata();
            $this->session->set_flashdata('success', 'Doctor Removed successfully');
            $this->template->load('template', 'contents' , 'Admin/doctors' ,$data);
        }
        else{
            $data['doctors'] = $this->Admin_Model->getdoctorsdata();
            $this->session->set_flashdata('error', 'Somthing Went Wrong!');
            $this->template->load('template', 'contents' , 'Admin/doctors' ,$data);
        }
    }

    public function deletepatient($id)
    {
        $result = $this->Admin_Model->deletepatient($id);
        If($result != null){
            $data['patients'] = $this->Admin_Model->getpatientsdata();
            $this->session->set_flashdata('success', 'Patient Removed successfully');
            $this->template->load('template', 'contents' , 'Admin/patients' ,$data);
        }
        else{
            $data['patients'] = $this->Admin_Model->getpatientsdata();
            $this->session->set_flashdata('error', 'Somthing Went Wrong!');
            $this->template->load('template', 'contents' , 'Admin/patients' ,$data);
        }
    }

    public function patientsdetails($id)
    {
        $patient['data'] = $this->Admin_Model->getpatientdetails($id);
        $patient['medical'] = $this->Admin_Model->getpatientmedicaldetails($id);
        $this->template->load('template', 'contents' , 'Admin/patientdetails' ,$patient);
    }

    public function spacializations()
    {
        $spacializations['list'] = $this->Admin_Model->getspacializations();

        $this->template->load('template', 'contents' , 'Admin/spacializations' ,$spacializations);
    }

    public function addspacialization()
    {
        $data = $this->input->post();
        $sp['spacialist'] = $data['spacalization'];
        $this->Admin_Model->addspacialization($sp);
        redirect('spacializations');
    }

    public function spacilizationdetails($id)
    {
        $spacilizationdetails['data'] = $this->Admin_Model->getspacilizationdetails($id);

        $this->template->load('template', 'contents' , 'Admin/spacilizationdetails' ,$spacilizationdetails);
    }

    public function updatespacialization()
    {
        $data = $this->input->post();
        $id = $data['id'];
        $sp = $data['spname'];
        $this->Admin_Model->updatespacialization($id,$sp);
        redirect('spacializations');
    }

    public function deletespacialization($id)
    {
        $this->Admin_Model->deletespacialization($id);
        redirect('spacializations');
    }

    public function getappointments()
    {
        $appointments['data'] = $this->Admin_Model->getappointments();
       
        if($appointments)
        {
            $this->template->load('template', 'contents' , 'Admin/appointments' ,$appointments);
        }
        else{
            echo "There is not any appointment.";
        }
    }

    public function getappointmentsdetails($id)
    {
        $appoint['data'] = $this->Admin_Model->getappointmentsdetailsbyid($id);
        $appoint['doctor'] = $this->Admin_Model->getdoctordetailsforappoint($id);
        //  print_r($appoint);die;
        if($appoint)
        {
            $this->template->load('template', 'contents' , 'Admin/appointmentdetails' ,$appoint);
        }
    }

    public function logout()
	{
    $this->session->sess_destroy();
        redirect('Admin');
	}
}