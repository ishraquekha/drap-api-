<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');
// use Aws\Common\Aws;
// use Aws\Ses\SesClient;
class User_api extends REST_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->language('common');
        $this->load->model('user_api_model');
        $this->load->model('Common_model');
        $this->load->database();
        $this->load->library('Awslib'); //load S3 library

        $this->datetime = date('Y-m-d H:i:s');
    }
    
    private function Authorized() {
        $request = $this->head();
        if (isset($request['Authtoken']) && !empty($request['Authtoken'])) {
            return $request['Authtoken'];
        } else {
            return "not authenticated";
        }
    }



    function patientlogin_post()
    {
        $postDataArr = $this->post();

        $email = $postDataArr['email'];
        $password= md5($postDataArr['password']);
       
        $result = $this->user_api_model->getUserInfo($email, $password);

        if($result != '' || $result != null)
        {
            // if($result['isEmailVerified']==0){
            //     $this->response(array(
            //         'status_code' => 0,
            //         'message' => "Please verify your email.",
            //         "result" => 'login failed'
            //     ));
            // }
            // else 
            if($result['IsActive']==0 || $result['IsDeleted']==1){
                $this->response(array(
                    'status_code' => 0,
                    'message' => "User is inactive or not exists.",
                    "result" => 'login failed'
                ));
            } 
            else
            {
                $userId = $result["patientid"];
                $result = $this->user_api_model->getUserDetailsById($userId);
                $this->response(array('status_code' => 200, 'message' => "Login Successful.","result" => $result));
            }
        }
        else
        {
            $this->response(array('status_code' => 0, 'message' => "Invalid email or password"));
        }
    }

    function doctorlogin_post()
    {
        $postDataArr = $this->post();

        $email = $postDataArr['email'];
        $password= md5($postDataArr['password']);
       
        $result = $this->user_api_model->getDoctorUserInfo($email, $password);

        if($result != '' || $result != null)
        {
            // if($result['isEmailVerified']==0){
            //     $this->response(array(
            //         'status_code' => 0,
            //         'message' => "Please verify your email.",
            //         "result" => 'login failed'
            //     ));
            // }
            // else 
            if($result['isverified']==0 ){
                $this->response(array(
                    'status_code' => 0,
                    'message' => "Your profile is under reviewing.",
                    "result" => 'login failed'
                ));
            } 
            if($result['isactive']==0 || $result['isdeleted']==1){
                $this->response(array(
                    'status_code' => 0,
                    'message' => "User is inactive or not exists.",
                    "result" => 'login failed'
                ));
            } 
            else
            {
                $this->response(array('status_code' => 200, 'message' => "Login Successful.","result" => $result));
            }
        }
        else
        {
            $this->response(array('status_code' => 0, 'message' => "Invalid email or password"));
        }
    }

    function patientsignup_post()
    {
            $postDataArr = $this->post();
            if ($postDataArr) 
            {
                $email = $postDataArr["email"];

                
                $result = $this->user_api_model->checkEmailExistspatient($email);
                if(count($result)>0)
                {
                   $this->response(array('status_code' => 0, 'message' => "Email address already exist!"));
                }
                else
                {
                    // $ejabberd_user = str_replace(".", "_", $email);
                    // $ejabberd_user = str_replace("@", "_", $email).'_drapp';
                    $ejabberd_user = preg_replace("/[.-@]/", "_", $email).'_drapp';
                    // print_r($ejabberd_user);die;
                    $this->load->helper('date');

                    $userInfo['firstname'] = $postDataArr['firstname'];
                    $userInfo['middlename'] = $postDataArr['middlename'];
                    $userInfo['email'] = $postDataArr['email'];
                    $userInfo['password'] = md5($postDataArr['password']);
                    $userInfo['lastname'] = $postDataArr['lastname'];
                    $userInfo['phone'] = $postDataArr['phone'];
                    $userInfo['address'] = $postDataArr['address'];
                    $userInfo['gender'] = $postDataArr['gender'];
                    $userInfo['dateofbirth'] = $postDataArr['dob'];
                    $userInfo['latitude'] = $postDataArr['latitude'];
                    $userInfo['longitude'] = $postDataArr['longitude'];
                    $userInfo['profilepic'] = "";
                    $userInfo['ejabberduser'] = $ejabberd_user;                   
                    $userInfo['created_at'] = date('Y-m-d h:i:s');
                    $userId = $this->user_api_model->addNewUser($userInfo);
                    if($userId > 0)
                    { 
                        ///// for chat server 
                        // $data_array =  array(
                        //     "user" => $ejabberd_user,
                        //     "password" => "123456",
                        //     "host" => "172.31.39.194"
                        // );
        
                        // $url = "http://18.222.180.107:5280/api/register";
                        // try {
                        //     $make_call = $this->user_api_model->callEjabberdAPI('POST', $url, json_encode($data_array));
                           
                        // }
                        // catch (Exception $e) {
                        //     $error = 'Message: ' .$e->getMessage();
                        //     $response["error"] = $error;
                        // } 
                        //////
                        $customerData = $this->user_api_model->getUserDetailsById($userId);
                        $this->response(
                            array(
                                'status_code' => 200, 
                                'message' =>"Sign Up Successful.", 
                                "result" => $customerData
                            )
                        );   
                    }
                    else 
                    {
                        $this->response(
                        array(
                            'status_code' => 200,
                            'message' => 'Some error occured try again later.'
                        ));
                    }
                    /** Email Notification */
                    // $this->load->library('encrypt');
                    // $user_type = "customer";
                    // $msg = $postDataArr['email']."##".$user_type."##".time();
                    // $key = 'Nabin*&^%$#';

                    // $token = $this->encrypt->encode($msg, $key);
                    // $reset_link = base_url()."login/confirm_email?token=".$token;
                    // $subject = "Rez Rising - Email Confirmation";
                    // $message = "<div><p>welcome to Rez Rising. Please click to verify account</p><p><a href=".$reset_link.">Follow this link to email confirmation.</a></p></div>";

                    // $this->sendmail($email,$subject,$message);
                   
                }
            } 
        
            else 
            {
                $this->response(
                array(
                    'status_code' => MISSING_PARAMETER,
                    'message' => $this->lang->line('parameter_missing')
                ));
            }
        //}    
    }

    function doctorsignup_post()
    {
        $postDataArr = $this->post();
        if ($postDataArr) 
        {
            $email = $postDataArr["email"];

            $result = $this->user_api_model->checkDoctorEmailExists($email);
            if(count($result)>0)
            {
                $this->response(array('status_code' => 0, 'message' => "Email address already exist!"));
            }
            else
            {
                $identityDocument_url = "";
                $postGraduateDocument_url = "";
                $legalDocument_url = "";
                $heathCertificate_url="";
                $msg = "";
                if (!empty($_FILES['identitydocument'])) 
                {
                    try
                    {
                        $name = $_FILES['identitydocument']['name'];    
                        if($name != '' || $name != null) 
                        {
                            $fileName = time() . '-' . $_FILES['identitydocument']['name'];
                            $tmpFilePath = $_FILES['identitydocument']['tmp_name'];
                            $type = $_FILES['identitydocument']['type'];
                            $identityDocument_url = $this->upload_file($tmpFilePath, $fileName, $type);
                        }
                    }
                    catch (Exception $e) {
                        $msg = $e->getMessage();
                        $msg = wordwrap($msg, 70);
                    }
                }

                if (!empty($_FILES['eunacom'])) 
                {
                    try
                    {
                        $name = $_FILES['eunacom']['name'];    
                        if($name != '' || $name != null) 
                        {
                            $fileName = time() . '-' . $_FILES['eunacom']['name'];
                            $tmpFilePath = $_FILES['eunacom']['tmp_name'];
                            $type = $_FILES['eunacom']['type'];
                            $postGraduateDocument_url = $this->upload_file($tmpFilePath, $fileName, $type);
                        }
                    }
                    catch (Exception $e) {
                        $msg = $e->getMessage();
                        $msg = wordwrap($msg, 70);
                    }
                }
                ///// Health Certificate
                if (!empty($_FILES['healthcertificate'])) 
                {
                    try
                    {
                        $name = $_FILES['healthcertificate']['name'];    
                        if($name != '' || $name != null) 
                        {
                            $fileName = time() . '-' . $_FILES['healthcertificate']['name'];
                            $tmpFilePath = $_FILES['healthcertificate']['tmp_name'];
                            $type = $_FILES['healthcertificate']['type'];
                            $heathCertificate_url = $this->upload_file($tmpFilePath, $fileName, $type);
                        }
                    }
                    catch (Exception $e) {
                        $msg = $e->getMessage();
                        $msg = wordwrap($msg, 70);
                    }
                }
                
                /////
                if (!empty($_FILES['legaldocument'])) 
                {
                    try
                    {
                        $name = $_FILES['legaldocument']['name'];    
                        if($name != '' || $name != null) 
                        {
                            $fileName = time() . '-' . $_FILES['legaldocument']['name'];
                            $tmpFilePath = $_FILES['legaldocument']['tmp_name'];
                            $type = $_FILES['legaldocument']['type'];
                            $legalDocument_url = $this->upload_file($tmpFilePath, $fileName, $type);
                        }
                    }
                    catch (Exception $e) {
                        $msg = $e->getMessage();
                        $msg = wordwrap($msg, 70);
                    }
                }
                $ejabberd_user = preg_replace("/[.-@]/", "_", $email).'_drapp';

                $this->load->helper('date');
                $userInfo['idnumber'] = $postDataArr['idnumber'];
                $userInfo['firstname'] = $postDataArr['firstname'];
                $userInfo['middlename'] = $postDataArr['middlename'];
                $userInfo['lastname'] = $postDataArr['lastname'];
                $userInfo['email'] = $postDataArr['email'];
                $userInfo['password'] = md5($postDataArr['password']);
                $userInfo['phone'] = $postDataArr['phone'];
                $userInfo['address'] = $postDataArr['address'];
                $userInfo['gender'] = $postDataArr['gender'];
                $userInfo['dateofbirth'] = $postDataArr['dob'];
                $userInfo['latitude'] = $postDataArr['latitude'];
                $userInfo['longitude'] = $postDataArr['longitude'];
                $userInfo['colleaguenumber'] = $postDataArr['colleaguenumber'];
                $userInfo['university'] = $postDataArr['university'];
                $userInfo['title'] = $postDataArr['title'];
                $userInfo['experience'] = $postDataArr['experience'];
                $userInfo['experiencedetails'] = $postDataArr['experiencedetails'];
                $userInfo['consultationrate'] = $postDataArr['consultationrate'];
                $userInfo['consultationrateunit'] = $postDataArr['consultationrateunit'];
                $userInfo['consultationtime'] = $postDataArr['consultationtime'];
                $userInfo['identitydocument'] = $identityDocument_url;
                $userInfo['eunacom'] = $postGraduateDocument_url;
                $userInfo['legaldocument'] = $legalDocument_url;
                $userInfo['healthcertificate'] = $heathCertificate_url;


                $userInfo['profilepic'] = "";
                
                $userInfo['ejabberduser'] = $ejabberd_user;       
                $userInfo['created_at'] = date('Y-m-d h:i:s');
                $userId = $this->user_api_model->addDoctorUser($userInfo);
                if($userId > 0)
                { 
                    ///// for chat server 
                    // $data_array =  array(
                    //     "user" => $ejabberd_user,
                    //     "password" => "123456",
                    //     "host" => "172.31.39.194"
                    // );
    
                    // $url = "http://18.222.180.107:5280/api/register";
                    // try {
                    //     $make_call = $this->user_api_model->callEjabberdAPI('POST', $url, json_encode($data_array));
                    // }
                    // catch (Exception $e) {
                    //     $error = 'Message: ' .$e->getMessage();
                    //     $response["error"] = $error;
                    // } 
                    //////
                         $customerData = $this->user_api_model->getDoctorsDetailsById($userId);

                         $this->response(
                            array(
                                'status_code' => 200, 
                                'message' =>"Sign Up Successful.", 
                                "result" => $customerData,
                                "error" => $msg
                            )
                        );
                    }
                /** Email Notification */
                // $this->load->library('encrypt');
                // $user_type = "customer";
                // $msg = $postDataArr['email']."##".$user_type."##".time();
                // $key = 'Nabin*&^%$#';

                // $token = $this->encrypt->encode($msg, $key);
                // $reset_link = base_url()."login/confirm_email?token=".$token;
                // $subject = "Rez Rising - Email Confirmation";
                // $message = "<div><p>welcome to Rez Rising. Please click to verify account</p><p><a href=".$reset_link.">Follow this link to email confirmation.</a></p></div>";

                // $this->sendmail($email,$subject,$message);
                $this->response(
                    array(
                        'status_code' => 200, 
                        'message' =>"Sign Up Successful.", 
                        "result" => $customerData,
                        "error" => $msg
                    )
                );
            }
        } 
        else 
        {
            $this->response(
            array(
                'status_code' => MISSING_PARAMETER,
                'message' => $this->lang->line('parameter_missing')
            ));
        }
        //}    
    }

    function updatepatientmedicaldetails_post()
    {
            $postDataArr = $this->post();
            if ($postDataArr) 
            {
                $id = $postDataArr["patientid"];
                
                $this->load->helper('date');
                $userInfo['medicalhistory'] = $postDataArr['medicalhistory'];
                $userInfo['surgeries'] = $postDataArr['surgeries'];
                $userInfo['drugtaker'] = $postDataArr['drugtaker'];
                $userInfo['isallergictomedications'] = $postDataArr['isallergictomedications'];
                $userInfo['allergictomedications'] = ($postDataArr['allergictomedications']);
                $userInfo['familybackground'] = $postDataArr['familybackground'];
                $userInfo['istobacco'] = $postDataArr['istobacco'];
                $userInfo['tobaccorating'] = $postDataArr['tobaccorating'];
                $userInfo['isalcohol'] = $postDataArr['isalcohol'];
                $userInfo['alcoholrating'] = $postDataArr['alcoholrating'];
                $userInfo['isdrugs'] = $postDataArr['isdrugs'];
                $userInfo['drugsdetails'] = $postDataArr['drugsdetails'];
                $userInfo['isphysicalactivity'] = $postDataArr['isphysicalactivity'];
                $userInfo['physicalactivitydetails'] = $postDataArr['physicalactivitydetails'];
                $userInfo['ispragnancy'] = $postDataArr['ispragnancy'];
                $userInfo['pragnancydetails'] = $postDataArr['pragnancydetails'];
                $userInfo['isbirth'] = $postDataArr['isbirth'];
                $userInfo['birthdetails'] = $postDataArr['birthdetails'];
                $userInfo['isabortions'] = $postDataArr['isabortions'];
                $userInfo['abortiondetails'] = $postDataArr['abortiondetails'];
                $userInfo['iscontraceptives'] = $postDataArr['iscontraceptives'];
                $userInfo['contraceptivesdetails'] = $postDataArr['contraceptivesdetails'];
                $userInfo['physicalactivityrating'] = $postDataArr['physicalactivityrating'];
                $userInfo['drugrating'] = $postDataArr['drugrating'];
                $userInfo['lastmansturationdate'] = $postDataArr['lastmansturationdate'];
                
                $userInfo['updated_at'] = date('Y-m-d h:i:s');
                $result = $this->user_api_model->update_single('patientmedicalinformation', $userInfo, array(
                    'where' => array(
                        'patientid' => $id,
                        'isactive' => 1
                    )
                ));
                if($result)
                {
                    $customerData = $this->user_api_model->getUserDetailsById($id); 
                    $this->response(
                        array(
                            'status_code' => 200, 
                            'message' =>"Profile updated succesfully", 
                            "result" => $customerData
                        )
                    );
                }
                else
                {
                    $this->response(array(
                        'status_code' => 0,
                        'message' => 'Update failed',
                        "result" => array()
                    ));
                }
            } 
            else 
            {
                $this->response(
                array(
                    'status_code' => MISSING_PARAMETER,
                    'message' => $this->lang->line('parameter_missing')
                ));
            }
        //}    
    }


    function editPatientProfile_post()
    {
        $postDataArr = $this->post();
        if ($postDataArr) 
        {
            $this->load->helper('date');
            try 
            {
                $id = $postDataArr['patientid'];
                $userInfo['firstname'] = $postDataArr['firstname'];
                $userInfo['middlename'] = $postDataArr['middlename'];
                $userInfo['lastname'] = $postDataArr['lastname'];
                $userInfo['phone'] = $postDataArr['phone'];
                $userInfo['address'] = $postDataArr['address'];
                $userInfo['gender'] = $postDataArr['gender'];
                $userInfo['dateofbirth'] = $postDataArr['dob'];
                $userInfo['latitude'] = $postDataArr['latitude'];
                $userInfo['longitude'] = $postDataArr['longitude'];

                $userInfo['updated_at'] = date('Y-m-d h:i:s');

                $result = $this->user_api_model->update_single('patient', $userInfo, array(
                    'where' => array(
                        'id' => $id,
                        'IsActive' => 1
                    )
                ));



                if($result)
                {
                    $userData = $this->user_api_model->getUserDetailsById($id);
                    $this->response(
                        array(
                            'status_code' => 200, 
                            'message' =>"Update Successful.", 
                            "result" => $userData
                        )
                    );
                }
                else
                {
                    $this->response(array(
                        'status_code' => 0,
                        'message' => 'Update failed',
                        "result" => array()
                    ));
                }
            }
            catch (Exception $e) {
                $msg = $e->getMessage();
                $msg = wordwrap($msg, 70);
                $this->response(array(
                    'status_code' => 0,
                    'message' => $msg
                ));
            }
        }
        else
        {
            $this->response(
            array(
                'status_code' => MISSING_PARAMETER,
                'message' => $this->lang->line('parameter_missing')
            ));
        }
    }

    function editDoctorProfile_post()
    {
        $postDataArr = $this->post();
        if ($postDataArr) 
        {
            $this->load->helper('date');
            try 
            {
                $identityDocument_url = "";
                $postGraduateDocument_url = "";
                $legalDocument_url = "";
                $heathCertificate_url= "";
                $msg = "";
                if (!empty($_FILES['identitydocument'])) 
                {
                    try
                    {
                        $name = $_FILES['identitydocument']['name'];    
                        if($name != '' || $name != null) 
                        {
                            $fileName = time() . '-' . $_FILES['identitydocument']['name'];
                            $tmpFilePath = $_FILES['identitydocument']['tmp_name'];
                            $type = $_FILES['identitydocument']['type'];
                            $identityDocument_url = $this->upload_file($tmpFilePath, $fileName, $type);
                        }
                    }
                    catch (Exception $e) {
                        $msg = $e->getMessage();
                        $msg = wordwrap($msg, 70);
                    }
                }

                if (!empty($_FILES['eunacom'])) 
                {
                    try
                    {
                        $name = $_FILES['eunacom']['name'];    
                        if($name != '' || $name != null) 
                        {
                            $fileName = time() . '-' . $_FILES['eunacom']['name'];
                            $tmpFilePath = $_FILES['eunacom']['tmp_name'];
                            $type = $_FILES['eunacom']['type'];
                            $postGraduateDocument_url = $this->upload_file($tmpFilePath, $fileName, $type);
                        }
                    }
                    catch (Exception $e) {
                        $msg = $e->getMessage();
                        $msg = wordwrap($msg, 70);
                    }
                }
                  ///// Health Certificate
                  if (!empty($_FILES['healthcertificate'])) 
                  {
                      try
                      {
                          $name = $_FILES['healthcertificate']['name'];    
                          if($name != '' || $name != null) 
                          {
                              $fileName = time() . '-' . $_FILES['healthcertificate']['name'];
                              $tmpFilePath = $_FILES['healthcertificate']['tmp_name'];
                              $type = $_FILES['healthcertificate']['type'];
                              $heathCertificate_url = $this->upload_file($tmpFilePath, $fileName, $type);
                          }
                      }
                      catch (Exception $e) {
                          $msg = $e->getMessage();
                          $msg = wordwrap($msg, 70);
                      }
                  }
                  
                  /////
                if (!empty($_FILES['legaldocument'])) 
                {
                    try
                    {
                        $name = $_FILES['legaldocument']['name'];    
                        if($name != '' || $name != null) 
                        {
                            $fileName = time() . '-' . $_FILES['legaldocument']['name'];
                            $tmpFilePath = $_FILES['legaldocument']['tmp_name'];
                            $type = $_FILES['legaldocument']['type'];
                            $legalDocument_url = $this->upload_file($tmpFilePath, $fileName, $type);
                        }
                    }
                    catch (Exception $e) {
                        $msg = $e->getMessage();
                        $msg = wordwrap($msg, 70);
                    }
                }

                $this->load->helper('date');
                
                $id = $postDataArr['doctorid'];
                $userInfo['dr_id'] = $id;
                $userInfo['identitydocument'] = $identityDocument_url;
                $userInfo['eunacom'] = $postGraduateDocument_url;
                $userInfo['legaldocument'] = $legalDocument_url;
                $userInfo['healthcertificate'] = $heathCertificate_url;
                $userInfo['created_at'] = date('Y-m-d h:i:s');

                $result = $this->Common_model->insert_single('doctoredithistory', $userInfo);
                if($result)
                {
                    $user['isverified'] = 0;
                    $user['updaterequest'] = 1;
                    $updateresult = $this->user_api_model->update_single('doctor', $user, array(
                        'where' => array(
                            'id' => $id,
                            'IsActive' => 1
                        )
                    ));
                    if($updateresult)
                    {
                        $userData = $this->user_api_model->getDoctorsDetailsById($id);
                        $this->response(
                            array(
                                'status_code' => 200, 
                                'message' =>"Profile update request send to admin, Your changes will be reflected once admin approved the request", 
                                "result" => $userData
                            )
                        );
                    }
                    else{
                        $this->response(array(
                            'status_code' => 0,
                            'message' => 'Update failed',
                            "result" => array()
                        ));
                    }
                }
                else
                {
                    $this->response(array(
                        'status_code' => 0,
                        'message' => 'Insert failed',
                        "result" => array()
                    ));
                }
            }
            catch (Exception $e) {
                $msg = $e->getMessage();
                $msg = wordwrap($msg, 70);
                $this->response(array(
                    'status_code' => 0,
                    'message' => $msg
                ));
            }
        }
        else
        {
            $this->response(
            array(
                'status_code' => MISSING_PARAMETER,
                'message' => $this->lang->line('parameter_missing')
            ));
        }
    }

    function editProfilePic_post()
    {
        $url = "";
        $postDataArr = $this->post();
        if (!empty($_FILES['image'])) 
        {
            $file_url = "";
            $userId = $postDataArr['userid'];
            $isPatient = $postDataArr['ispatient'];
           
            try
            {
                $name = $_FILES['image']['name'];    
                if($name != '' || $name != null) 
                {
                    $fileName = time() . '-' . $_FILES['image']['name'];
                    $tmpFilePath = $_FILES['image']['tmp_name'];
                    $type = $_FILES['image']['type'];
                    $file_url = $this->upload_file($tmpFilePath, $fileName, $type);
                }
            }
            catch (Exception $e) {
                $msg = $e->getMessage();
                $msg = wordwrap($msg, 70);
                $this->response(array(
                    'status_code' => 0,
                    'message' => $msg
                ));
            }

            $userInfo['profilepic'] = $file_url;
            $userInfo['updated_at'] = date('Y-m-d h:i:s');

            if($isPatient == 1){
                $result = $this->user_api_model->update_single('patient', $userInfo, array(
                    'where' => array(
                        'id' => $userId,
                        'IsActive' => 1
                    )
                ));

                if($result)
                {
                    $userData = $this->user_api_model->getUserDetailsById($userId);
                    $this->response(
                        array(
                            'status_code' => 200, 
                            'message' =>"Update Successful.", 
                            "result" => $userData
                        )
                    );
                }
                else
                {
                    $this->response(array(
                        'status_code' => 0,
                        'message' => 'Update failed',
                        "result" => array()
                    ));
                }
            }
            else{
                $result = $this->user_api_model->update_single('doctor', $userInfo, array(
                    'where' => array(
                        'id' => $userId,
                        'isactive' => 1
                    )
                ));

                if($result)
                {
                    $userData = $this->user_api_model->getDoctorsDetailsById($userId);
                    $this->response(
                        array(
                            'status_code' => 200, 
                            'message' =>"Update Successful.", 
                            "result" => $userData
                        )
                    );
                }
                else
                {
                    $this->response(array(
                        'status_code' => 0,
                        'message' => 'Update failed',
                        "result" => array()
                    ));
                }
            }
            
            
        }
        else
        {
            $this->response(
            array(
                'status_code' => MISSING_PARAMETER,
                'message' => "No image found!"
            ));
        }
    }

    function doctorprofiledetails_get($doctorid)
    {
        // $postDataArr = $this->post();
        // $doctorid = $id;

        $result = $this->user_api_model->getdoctordetails($doctorid);
        if($result > 0)
        {
            $this->response(
                array(
                    'status_code' => 200, 
                    'message' =>"Show detail succesfully", 
                    "result" => $result
                )
            );
        }
        else{
            $this->response(array(
                'status_code' => 0,
                'message' => 'show detail failed',
                "result" => array()
            ));
        }
    }

    function getspacializationslist_get()
    {
        $spacialization = $this->user_api_model->getspacializations();
        if($spacialization)
        {
            $this->response(
                array(
                    'status_code' => 200, 
                    'message' =>"get succesfully", 
                    "result" => $spacialization
                )
            );
        }
        else{
            $this->response(array(
                'status_code' => 0,
                'message' => 'getting spacialization failed',
                "result" => array()
            ));
        }
    }

    function patientpreference_post()
    {
        $postData = $this->post();

        $patientid = $postData['patientid'];
        $preference['rate'] = $postData['rate'];
        $preference['duration'] = $postData['duration'];
        $preference['spacialist'] = $postData['spacialist'];
        $check=$this->user_api_model->getcheckpatient($patientid);
        // print_r($patientid);die;
                // print_r($c['patientid']);die;
        if($check != null)
        {

    $result = $this->user_api_model->patientpreferenceupdate($patientid,$preference);

    $this->response(
        array(
            'status_code' => 200, 
            'message' =>"preference updated.", 
            "result" => $result
        ));
        }
    else
       {
           
        $preference['patientid'] = $patientid;
        $preference['rate'] = $postData['rate'];
        $preference['duration'] = $postData['duration'];
        $preference['spacialist'] = $postData['spacialist'];

            $preferenceid = $this->user_api_model->insertpreference($preference);

            $preferencedata = $this->user_api_model->getpreferencedata($preferenceid);
            if($preferencedata)
            {
                $this->response(
                    array(
                        'status_code' => 200, 
                        'message' =>"insert succesfully", 
                        "result" => $preferencedata
                    )
                );
            }
            else
            {
                $this->response(array(
                    'status_code' => 0,
                    'message' => 'Insert failed',
                    "result" => array()
                ));
            } 
        }

    }

    public function getdoctorsbypreference_get($patientid, $latitude, $longitude)
    {
        $finelresult=array();
        $preferencedata = $this->user_api_model->getpatientpreference($patientid);
        // print_r($preferencedata);die;
        if($preferencedata != null)
        {
            $spacialistid = $preferencedata['spacialist'];
            $spacialization = $this->user_api_model->getspacialistdoctor($spacialistid);
            // print_r($spacialization);die;
            if($spacialization)
            {
                foreach($spacialization as $s)
                {
                    $doctorid = $s['doctorsid'];
                    // print_r($doctorid);
                    $rate = $preferencedata['rate'];
                    $duration = $preferencedata['duration'];
                    $result = $this->user_api_model->getpreferencedoctors($patientid, $rate, $duration, $latitude, $longitude, $doctorid); 
                    // print_r($result);
                    array_push($finelresult,$result);
                    // print_r($finelresult);
                }

                
                // $doctorid = $spacialization['doctorsid'];
                // $rate = $preferencedata['rate'];
                // $duration = $preferencedata['duration'];
                // $result = $this->user_api_model->getpreferencedoctors($patientid, $rate, $duration, $latitude, $longitude, $doctorid); 
                // print_r($finelresult);die;
               
                if($finelresult[0]!=null)
                {
                    // print_r($patientid);die;
                    $this->response(
                        array(
                            'status_code' => 200, 
                            'message' =>"Preference set succesfully",
                            "result" => $finelresult
                        )
                    );
                }
                else
                {
                    $this->response(array(
                        'status_code' => 0,
                        'message' => 'There is no doctor available according to your preference',
                        "result" => array()
                    ));
                }
            }
            else{
                $rate = $preferencedata['rate'];
                $duration = $preferencedata['duration'];
                //   print_r($rate);die;
                $result = $this->user_api_model->getdoctors($patientid, $latitude, $longitude, $rate, $duration);
                if($result!=null)
            {
                $this->response(
                    array(
                        'status_code' => 200, 
                        'message' =>"get doctors succesfully...", 
                        "result" => $result
                    )
                );
            }else{
                 $this->response(array(
                    'status_code' => 0,
                    'message' => 'There is not any doctor available now...',
                    "result" => array()
                    ));
                }
            }
        }
        else
        {
            
            // []
            $result = $this->user_api_model->getdoctorswithnorate($patientid, $latitude, $longitude);
      //////for block ------------
             
            if($result!=null)
            {
                $this->response(
                    array(
                        'status_code' => 200, 
                        'message' =>"get doctors succesfully", 
                        "result" => $result
                    )
                );
            }
            else{
                $this->response(array(
                    'status_code' => 0,
                    'message' => 'There is not any doctor available now.',
                    "result" => array()
                ));
            }
        }
        
    }

    public function insertappointments_post()
    {
        $postData = $this->post();
        $this->load->helper('date');
        $appoint['patientid'] = $postData['patientid'];
        $appoint['doctorid'] = $postData['doctorid'];
        $appoint['consultationreason'] = $postData['consultationreason'];
        $appoint['scheduleddate'] = $postData['scheduleddate'];
        $appoint['timeofarrivel'] = $postData['timeofarrivel'];
        // $appoint['doctorspaciality'] = $postData['doctorspaciality'];
        // $appoint['url'] = $postData['url'];
        $appoint['created_date'] = date('Y-m-d h:i:s');
        
        $insertid = $this->user_api_model->insertappointments($appoint);

        $appointments = $this->user_api_model->getappointments($insertid);

        if($appointments)
        {
            $this->response(
                array(
                    'status_code' => 200, 
                    'message' =>"Appointment insert succesfully",
                    "result" => $appointments
                )
            );
        }
        else{
            $this->response(array(
                'status_code' => 0,
                'message' => 'Insert failed',
                "result" => array()
            ));
        }

    }
//////////////////////////////////////////////////////////////////////

    public function getpatientappointments_post()
    { 
        
        $postDataArr = $this->post(); 
        
        $id = $postDataArr['id'];
        $date = $postDataArr['date'];
        if($date != null || $date != "")
        {
             
            $id = $postDataArr['id'];
            $date = $postDataArr['date'];
            $lastdate = $postDataArr['lastdate'];
        $appointments = $this->user_api_model->getappointmentbydate($id,$date,$lastdate);
        $dd=date('Y-m-d');
        $now = new DateTime();
        $now->setTimezone(new DateTimezone('Asia/Kolkata'));
        $dt=$now->format('H:i:s');
        $datetime = $dd . ' ' . $dt;
        
        if($appointments) 
        {
            // $array=array();
            foreach ($appointments as $row) {
                $row['datetime'] = $datetime;
                $data[] = $row;
             }
            // $appointments['datetime']=$datetime;
            // array_push($array,$appointments);
            $this->response(
                array(
                    'status_code' => 200, 
                    'message' =>"Patient's Appointment get succesfully",
                    "result" => $data
                )
            );
        }
        else{
            $this->response(array(
                'status_code' => 0,
                'message' => 'There is not any Appointments for this patient',
                "result" => array()
            ));
        }
        }
        else
          {
        $appointments = $this->user_api_model->getpatientappointments($id);

        if($appointments) 
        {
            $this->response(
                array(
                    'status_code' => 200, 
                    'message' =>"Patient's Appointment get succesfully",
                    "result" => $appointments
                )
            );
        }
        else{
            $this->response(array(
                'status_code' => 0,
                'message' => 'There is not any Appointments for this patient',
                "result" => array()
            ));
        }
    }
    }

    public function getdoctorappointments_post()
    {
        $postDataArr = $this->post(); 
        
        $id = $postDataArr['doctorid'];
        $date = $postDataArr['date'];
        
        //   echo $time;
        if($date != null || $date != "")
        {
             
            $id = $postDataArr['doctorid'];
            $date = $postDataArr['date'];
            $lastdate = $postDataArr['lastdate'];
           
        $appointments = $this->user_api_model->getdoctorappointmentsbydate($id,$date,$lastdate);
        //   foreach($appointments as $p)
        //   {
        //       $a = $p['scheduleddate'];
        //       $b = $p['timeofarrivel'];
        //     //  echo $data;
        //    $datetime = $a . ' ' . $b;
        // //    $pastdate = strtotime($datetime);
  
        //     $dd=date('Y-m-d H:i:s');
        //    if($datetime >= $dd)
        //    {
        //     $this->response(
        //         array(
        //             'status_code' => 200, 
        //             'message' =>"Doctor's Appointment get succesfully",
        //             "result" => $p
        //         )
        //     );
        //    }
        //   }
        if($appointments) 
        {
            $this->response(
                array(
                    'status_code' => 200, 
                    'message' =>"Doctor's Appointment get succesfully",
                    "result" => $appointments
                )
            );
        }
        else{
            $this->response(array(
                'status_code' => 0,
                'message' => 'There not any Appointments for this doctor',
                "result" => array()
            ));
        }
    }
    else
    {
    $appointments = $this->user_api_model->getdoctorappointments($id);

        if($appointments) 
        {
            $this->response(
                array(
                    'status_code' => 200, 
                    'message' =>"Doctor's Appointment get succesfully",
                    "result" => $appointments
                )
            );
        }
        else{
            $this->response(array(
                'status_code' => 0,
                'message' => 'There not any Appointments for this doctor',
                "result" => array()
            ));
        }
    }
    }

    public function getdoctoraccept_get($appointmentid)
    {
        $result = $this->user_api_model->getacceptedbydoctor($appointmentid);

        if($result)
        {
            $this->response(
                array(
                    'status_code' => 200, 
                    'message' =>"Doctor's Acceptance succesfully",
                    "result" => $result
                )
            );
        }
        else{
            $this->response(array(
                'status_code' => 0,
                'message' => "Doctors Acceptance failed",
                "result" => array()
            ));
        }
    }

    public function getdoctorreject_get($appointmentid)
    {
        $result = $this->user_api_model->getrejectedbydoctor($appointmentid);

        if($result)
        {
            $this->response(
                array(
                    'status_code' => 200, 
                    'message' =>"Doctor's Rejected succesfully",
                    "result" => $result
                )
            );
        }
        else{
            $this->response(array(
                'status_code' => 0,
                'message' => "Doctors Rejection failed",
                "result" => array()
            ));
        }
    }

    public function getcancelledbypatient_get($appointmentid)
    {
        $result = $this->user_api_model->getcancelledbypatient($appointmentid);

        if($result)
        {
            $this->response(
                array(
                    'status_code' => 200, 
                    'message' =>"Patient cancelled succesfully",
                    "result" => $result
                )
            );
        }
        else{
            $this->response(array(
                'status_code' => 0,
                'message' => "Patient cancelled failed",
                "result" => array()
            ));
        }
    }


    public function sendEmail_get($id){
 
        $params = array(
        'credentials'=> array(
        'key'=> SECRET_KEY,
        'secret'=> SECRET_CODE,
        ),
        'region'=>'us-east-1',
        'version'=>'latest'
        );
        $SesClient = $client = Aws\Ses\SesClient::factory($params);
         
        $sender_email = 'developerdream121@gmail.com';
        $recipient_emails = ['manishvishwakarma081.rv@gmail.com'];
         
        // Specify a configuration set. If you do not want to use a configuration comment it or delete.
        //$configuration_set = 'ConfigSet';
         
        $subject = 'Test Email From Techalltype';
        $plaintext_body = 'This email was sent with Amazon SES using the AWS SDK for PHP.' ;
        $html_body = '<h1>Test Email From Techalltype</h1>';
        $char_set = 'UTF-8';
         
        try {
        $result = $SesClient->sendEmail([
        'Destination'=> [
        'ToAddresses'=> $recipient_emails,
        ],
        'ReplyToAddresses'=> [$sender_email],
        'Source'=> $sender_email,
        'Message'=> [
        'Body'=> [
        'Html'=> [
        'Charset'=> $char_set,
        'Data'=> $html_body,
        ],
        'Text'=> [
        'Charset'=> $char_set,
        'Data'=> $plaintext_body,
        ],
        ],
        'Subject'=> [
        'Charset'=> $char_set,
        'Data'=> $subject,
        ],
        ],
        // If you aren't using a configuration set, comment or delete the following line
        //'ConfigurationSetName' => $configuration_set,
        ]);
        $messageId = $result['MessageId'];
        echo("Email sent! Message ID: $messageId"."\n");
        } catch (AwsException $e) {
        // output error message if fails
        echo $e->getMessage();
        echo("The email was not sent. Error message: ".$e->getAwsErrorMessage()."\n");
        echo "\n";
        }
    }

    public function resetPassword_post(){

        $postData = $this->post();
        $email = $postData['email'];
        $check = $postData['check'];
        if ($check==1)
        {
            $result = $this->user_api_model->checkEmailExistsdoctor($email);
        
            if($result == null)
            {
                $this->response(array(
                    'status_code' => 0,
                    'message' => 'Email is not registered, please cheak your email.',
                    "result" => array()
                ));   
            }
            else{
    
            $key = md5(microtime().rand());
            $emailhash = md5($email);
            $this->user_api_model->updatetokendoctor($key, $email);
              
            $params = array(
                'credentials'=> array(
                'key'=> SECRET_KEY,
                'secret'=> SECRET_CODE,
                    ),
                'region'=>'us-east-1',
                'version'=>'latest'
            );
           
            $SesClient = $client = Aws\Ses\SesClient::factory($params);   
            $sender_email = 'developerdream121@gmail.com';
            $recipient_emails = [$email];
            $url = 'http://drap.us-east-2.elasticbeanstalk.com/index.php/ResetPassword/'.$key.'/'.$emailhash.'/'.$check;
            // print_r($url);die;
             
            // Specify a configuration set. If you do not want to use a configuration comment it or delete.
            //$configuration_set = 'ConfigSet';
             
            $subject = 'Reset Password - Drapp';
            $html_body = 'Please click on this <a href='.$url.'>click here</a> link to Re-set your password ';
            $char_set = 'UTF-8';
             
            try {
                    $result = $SesClient->sendEmail([
                        'Destination'=> [
                        'ToAddresses'=> $recipient_emails,
                    ],
                    'ReplyToAddresses'=> [$sender_email],
                    'Source'=> $sender_email,
                    'Message'=> [
                        'Body'=> [
                            'Html'=> [
                                'Charset'=> $char_set,
                                'Data'=> $html_body,
                            ],
    
                        ],
                        'Subject'=> [
                            'Charset'=> $char_set,
                            'Data'=> $subject,
                        ],
                    ],
                // If you aren't using a configuration set, comment or delete the following line
                //'ConfigurationSetName' => $configuration_set,
                ]);
                $messageId = $result['MessageId'];
                echo("Email sent! Message ID: $messageId"."\n");
            } catch (AwsException $e) {
                // output error message if fails
                echo $e->getMessage();
                echo("The email was not sent. Error message: ".$e->getAwsErrorMessage()."\n");
                echo "\n";
            }
            }
        }
        
        else{
        $result = $this->user_api_model->checkEmailExistspatient($email);
        
        if($result == null)
        {
            $this->response(array(
                'status_code' => 0,
                'message' => 'Email is not registered, please cheak your email.',
                "result" => array()
            ));   
        }
        else{

        $key = md5(microtime().rand());
        $emailhash = md5($email);
        $this->user_api_model->updatetokenpatient($key, $email);
          
        $params = array(
            'credentials'=> array(
            'key'=> SECRET_KEY,
            'secret'=> SECRET_CODE,
                ),
            'region'=>'us-east-1',
            'version'=>'latest'
        );
       
        $SesClient = $client = Aws\Ses\SesClient::factory($params);   
        $sender_email = 'developerdream121@gmail.com';
        $recipient_emails = [$email];
        $url = 'http://drap.us-east-2.elasticbeanstalk.com/index.php/ResetPassword/'.$key.'/'.$emailhash.'/'.$check;
        // print_r($url);die;
         
        // Specify a configuration set. If you do not want to use a configuration comment it or delete.
        //$configuration_set = 'ConfigSet';
         
        $subject = 'Reset Password - Drapp';
        $html_body = 'Please click on this <a href='.$url.'>click here</a> link to Re-set your password ';
        $char_set = 'UTF-8';
         
        try {
                $result = $SesClient->sendEmail([
                    'Destination'=> [
                    'ToAddresses'=> $recipient_emails,
                ],
                'ReplyToAddresses'=> [$sender_email],
                'Source'=> $sender_email,
                'Message'=> [
                    'Body'=> [
                        'Html'=> [
                            'Charset'=> $char_set,
                            'Data'=> $html_body,
                        ],

                    ],
                    'Subject'=> [
                        'Charset'=> $char_set,
                        'Data'=> $subject,
                    ],
                ],
            // If you aren't using a configuration set, comment or delete the following line
            //'ConfigurationSetName' => $configuration_set,
            ]);
            $messageId = $result['MessageId'];
            echo("Email sent! Message ID: $messageId"."\n");
        } catch (AwsException $e) {
            // output error message if fails
            echo $e->getMessage();
            echo("The email was not sent. Error message: ".$e->getAwsErrorMessage()."\n");
            echo "\n";
        }
        }
    }
    }

    function upload_file($file, $fileName, $type)
	{
        $client = Aws\S3\S3Client::factory(
            array(
                'credentials' => array(
                'key'    => SECRET_KEY,
                'secret' => SECRET_CODE
            ),
            'region' => 'us-east-2',
            'version' => 'latest'
        )
        );
	
        try 
        {
            $client->putObject(array
            (
                'Bucket'=>BUCKET_NAME,
                'Key' =>  $fileName,
                'SourceFile' => $file,
                'StorageClass' => 'REDUCED_REDUNDANCY',
                'ContentType' => $type,
                'ACL' => 'public-read'
            ));
            $message = "S3 Upload Successful.";
            $url='https://s3.us-east-2.amazonaws.com/'.BUCKET_NAME.'/'.$fileName;
           
            return $url;
        } 
        catch (Aws\S3\Exception\S3Exception $e) {
            return $e->getMessage();
        }
    }

    






































    function changePassword_post()
    {
        $postDataArr = $this->post();
        if($postDataArr)
        {
            $oldPassword = md5($postDataArr['oldPassword']);
            $newPassword = md5($postDataArr['newPassword']);
            $userId = $postDataArr['userId'];
            $result = $this->user_api_model->verifyUser($userId, $oldPassword);
            if(count($result)>0) {
                //$resultU = $this->user_api_model->updatePassword($newPassword, $oldPassword);
                $userInfo = array();
                $userInfo['password'] = $newPassword;
                $userInfo['updatedDate'] = date('Y-m-d h:i:s');

                $resultU = $this->user_api_model->update_single('tbl_users', $userInfo, array(
                    'where' => array(
                        'userId' => $userId,
                        'isActive' => 1
                    )
                ));
                if($resultU)
                {
                    $this->response(
                        array(
                            'status_code' => 200, 
                            'message' =>"Password update successful.", 
                            "result" => array()
                        )
                    );
                }
                else
                {
                    $this->response(
                        array(
                            'status_code' => 0, 
                            'message' =>"Password update failed, Please try later.", 
                            "result" => array()
                        )
                    );
                }
            }
            else
            {
                $this->response(
                    array(
                        'status_code' => 0, 
                        'message' =>"Password not verified", 
                        "result" => array()
                    )
                );
            }
        }
    }

    function setPrefferedSettings_post()
    {
        $postDataArr = $this->post();
        if ($postDataArr) 
        {
            $userId = $postDataArr['userId'];
            $userInfo['priceSetting'] = $postDataArr['price'];
            $userInfo['rangeSetting'] = $postDataArr['distanceInMiles'];
            $userInfo['categoriesSettings'] = $postDataArr['categoryIds'];
            $userInfo['isAvailable'] = $postDataArr['available'];
           
            try {
                $result = $this->user_api_model->update_single('tbl_users', $userInfo, array(
                    'where' => array(
                        'userId' => $userId,
                        'isActive' => 1
                    )
                ));
                if($result)
                {
                    $userData = $this->user_api_model->getPrefferedSettings($userId);
                    $this->response(
                        array(
                            'status_code' => 200, 
                            'message' =>"Update Successful.", 
                            "result" => $userData
                        )
                    );
                }
                else
                {
                    $this->response(array(
                        'status_code' => 0,
                        'message' => 'Update failed',
                        "result" => array()
                    ));
                }
            }
            catch (Exception $e) {
                $msg = $e->getMessage();
                $msg = wordwrap($msg, 70);
                $this->response(array(
                    'status_code' => 0,
                    'message' => $msg
                ));
            }
        }
        else
        {
            $this->response(
            array(
                'status_code' => MISSING_PARAMETER,
                'message' => $this->lang->line('parameter_missing')
            ));
        }
    }

    function setNotificationSetting_post()
    {
        $postDataArr = $this->post();
        if ($postDataArr) 
        {
            $userId = $postDataArr['userId'];
            $userInfo['notificationSetting'] = $postDataArr['notification'];
           
            try {
                $result = $this->user_api_model->update_single('tbl_users', $userInfo, array(
                    'where' => array(
                        'userId' => $userId,
                        'isActive' => 1
                    )
                ));
                if($result)
                {
                    $userData = $this->user_api_model->getPrefferedSettings($userId);
                    $this->response(
                        array(
                            'status_code' => 200, 
                            'message' =>"Update Successful.", 
                            "result" => $userData
                        )
                    );
                }
                else
                {
                    $this->response(array(
                        'status_code' => 0,
                        'message' => 'Update failed',
                        "result" => array()
                    ));
                }
            }
            catch (Exception $e) {
                $msg = $e->getMessage();
                $msg = wordwrap($msg, 70);
                $this->response(array(
                    'status_code' => 0,
                    'message' => $msg
                ));
            }
        }
        else
        {
            $this->response(
            array(
                'status_code' => MISSING_PARAMETER,
                'message' => $this->lang->line('parameter_missing')
            ));
        }
    }

    function getPrefferedSettings_get($userId)
    {
        $result = $this->user_api_model->getPrefferedSettings($userId);

        if(count($result)>0)
        {
            $this->response(array(
                'status_code' => 200, 
                'message' => "settings",
                'result' => $result
            ));
        }
        else
        {
            $this->response(array('status_code' => 0, 'message' => "No Task Found!"));
        }
    }

    function feedbackReview_post()
    {
        $postDataArr = $this->post();
        if ($postDataArr) 
        {
            $userInfo['UserId'] = $postDataArr['userId'];
            $userInfo['ReviewerId'] = $postDataArr['reviewerId'];
            $userInfo['Rating'] = $postDataArr['rating'];
            $userInfo['CommentText'] = $postDataArr['comment'];

            try
            {
                
                $result = $this->user_api_model->feedbackReview($userInfo);
               
                if($result > 0){
                    $this->response(
                        array(
                            'status_code' => 200, 
                            'message' =>"success.", 
                            "result" => $result
                        )
                    );
                }
                else {
                    $this->response(
                        array(
                            'status_code' => 200, 
                            'message' =>"failed.", 
                            "result" => $result
                        )
                    );
                }            
            }
            catch (Exception $e) {
                $msg = $e->getMessage();
                $msg = wordwrap($msg, 70);
                $this->response(array(
                    'status_code' => 0,
                    'message' => $msg
                ));
            }
        }
        else
        {
            $this->response(
            array(
                'status_code' => MISSING_PARAMETER,
                'message' => $this->lang->line('parameter_missing')
            ));
        }
    }
    //----update patient address

function updatepatientaddress_post()
{
    $postDataArr = $this->post();   
   
    if($postDataArr)
    {
      
        $userid = $postDataArr['id'];
        $userInfo = $postDataArr['address'];
        // print_r($id);die;
        $result = $this->user_api_model->updatepatientaddress($userid, $userInfo);
        $this->response(
            array(
                'status_code' => 200, 
                'message' =>"Address update successful.", 
                "result" => $result
            )
            
        );
    }
    else 
{
    $this->response(
        array(
            'status_code' => 404, 
            'message' =>"Address update Unsuccessful.", 
            "result" => $result
        )
    );
}
}

////------patient details

function patientdetails_post()
{
    $postDataArr = $this->post(); 
    $id = $postDataArr['id'];
    $result = $this->user_api_model->patientdetails($id);
    if($result != null)
    {
    $this->response(
        array(
            'status_code' => 200, 
            'message' =>"details get successful.", 
            "result" => $result
        )
    );
}
else 
{
    $this->response(
        array(
            'status_code' => 404, 
            'message' =>"details not found.", 
            "result" => $result
        )
    );
}
}
////get patient appointment by date
// function getappointmentbydate_post()
// {
//     $postDataArr = $this->post(); 
//     $id = $postDataArr['id'];
//     // print_r($id);die;
//     $date = $postDataArr['date'];
//     $lastdate = $postDataArr['lastdate'];
// $appointments = $this->user_api_model->getappointmentbydate($id,$date,$lastdate);

// if($appointments) 
// {
//     $this->response(
//         array(
//             'status_code' => 200, 
//             'message' =>"Patient's Appointment get succesfully",
//             "result" => $appointments
//         )
//     );
// }
// else{
//     $this->response(array(
//         'status_code' => 0,
//         'message' => 'There is not any Appointments for this patient',
//         "result" => array()
//     ));
// }
// }
/// for favourite doctor insert and update
function patientfav_post()
{
     $postDataArr = $this->post();
        $patientid = $postDataArr['patientid'];
        $doctorid = $postDataArr['doctorid'];
        $isfav = $postDataArr['isfav'];
        $check=$this->user_api_model->getcheckfavdoctor($patientid,$doctorid);
        // print_r($patientid);die;
                // print_r($c['patientid']);die;
        if($check != null)
        {
            $result = $this->user_api_model->patientfavupdate($patientid,$doctorid,$isfav);
            $this->response(
            array(
                'status_code' => 200, 
                'message' =>"favourite list update successful.", 
                "result" => $result
            ));
        }
        else
        {
            
     $data ['patientid'] = $patientid;
     $data ['doctorid'] = $doctorid;
     $data ['isfav'] = $isfav;
            // echo "hi"; die;
            $fav = $this->user_api_model->patientfav($data);

            if($fav != null)
            {
                $this->response(
                array(
                    'status_code' => 200, 
                    'message' =>"doctor added to favourite successful.", 
                    "result" => $fav
                ));
            }
            else 
            {
                $this->response(
                    array(
                        'status_code' => 404, 
                        'message' =>"doctor not added in favourite list.", 
                        "result" => $fav
                    )
                );
            }
        }
        
    }
        //update favourite by patient
        // function patientfavupdate_post()
        // {

        //         $postDataArr = $this->post();   
            
        //         if($postDataArr)
        //         {
                
        //             $patientid = $postDataArr['patientid'];
        //             $doctorid = $postDataArr['doctorid'];
        //             $fav = $postDataArr['isfav'];
        //             // print_r($id);die;
        //             $result = $this->user_api_model->patientfavupdate($patientid, $doctorid, $fav);
        //             $this->response(
        //                 array(
        //                     'status_code' => 200, 
        //                     'message' =>"favourite list update successful.", 
        //                     "result" => $result
        //                 )
                        
        //             );
        //         }
        //     }
    ///////get fav doctor
    function getfavdoctor_post()
    {
        $postDataArr = $this->post();
        $patientid = $postDataArr['patientid'];
        $result = $this->user_api_model->getfavdoctor($patientid);
        if($result !=null)
        {
        $this->response(
            array(
                'status_code' => 200, 
                'message' =>"favourite Doctor list.", 
                "result" => $result
            )
            
        );
    }
    else 
    {
        $this->response(
            array(
                'status_code' => 404, 
                'message' =>"not found.", 
                "result" => $result
            )
        );
    }
    }
    ///// doctor block by patient
   function  doctorblock_post()
   {
    $postDataArr = $this->post();
     
    
    $patientid = $postDataArr['patientid'];
    $doctorid = $postDataArr['doctorid'];
    $isblocked= $postDataArr['isblocked'];
    $check=$this->user_api_model->getcheckfavdoctor($patientid,$doctorid);
        // print_r($patientid);die;
                // print_r($c['patientid']);die;
        if($check != null)
        {

    $result = $this->user_api_model->doctorblockupdate($patientid,$doctorid,$isblocked);

    $this->response(
        array(
            'status_code' => 200, 
            'message' =>"blocked status updated.", 
            "result" => $result
        ));
        }
    
  else
  {

              
    $data ['patientid'] = $patientid;
    $data ['doctorid'] = $doctorid;
    $data ['isblocked'] = $isblocked;

    $block = $this->user_api_model->doctorblock($data);
    if($block != null)
    {
    $this->response(
        array(
            'status_code' => 200, 
            'message' =>"doctor blocked successfully.", 
            "result" => $block
        )
    );
    }
      else 
      {
    $this->response(
        array(
            'status_code' => 404, 
            'message' =>"doctor not added in blocked  list.", 
            "result" => $block
        )
    );

      }
   }
}
    function doctorprefrenceupdate_post()
    {
        $postDataArr = $this->post();
     
    $id = $postDataArr['id'];
    // $data['latitude']= $postDataArr['latitude'];
    // $data['longitude'] = $postDataArr['longitude'];
    // $data['address']= $postDataArr['address'];
    // $data['consultationrate']= $postDataArr['consultationrate'];
    // $data['consultationtime']= $postDataArr['consultationtime'];
    
    $result=$this->user_api_model->doctorpreferenceupdate($id,$postDataArr);
//    print_r($result);die;
    if($result != null)
        {
            
            $get=$this->user_api_model->getdoctorfulldetails($id);
            $this->response(
                array(
                    'status_code' => 200, 
                    'message' =>"Prefrence set successfully.", 
                    "result" => $get
                ));
        }
        else
       {
            $this->response(
               array(
                  'status_code' => 404, 
                  'message' =>"prefrence not set.", 
                  "result" => $result
                    ));
        }
    }

    /// for rating doctor 

    function doctorrating_post()
    {
        $postDataArr = $this->post();
        $id = $postDataArr['doctorid'];
        $rating = $postDataArr['rating'];
        $result=$this->user_api_model->doctorrating($id,$rating);
         
        if($result != null)
        {
            
            // $get=$this->user_api_model->getdoctorfulldetails($id);
            $this->response(
                array(
                    'status_code' => 200, 
                    'message' =>"Rating set successfully.", 
                    "result" => $result
                ));
        }
        else
       {
            $this->response(
               array(
                  'status_code' => 404, 
                  'message' =>"rating not set.", 
                  "result" => $result
                    ));
        }
    }
    //// get doctor rating
    function getdoctorrating_post()
     {
    $postDataArr = $this->post();
      $id = $postDataArr['doctorid'];
      $result=$this->user_api_model->getdoctorrating($id);
      if($result != null)
        {
            
            // $get=$this->user_api_model->getdoctorfulldetails($id);
            $this->response(
                array(
                    'status_code' => 200, 
                    'message' =>"Rating set successfully.", 
                    "result" => $result
                ));
        }
        else
       {
            $this->response(
               array(
                  'status_code' => 404, 
                  'message' =>"prefrence not set.", 
                  "result" => $result
                    ));
        }
     }

     ///for patient rating
     function patientrating_post()
     {
         $postDataArr = $this->post();
         $id = $postDataArr['id'];
         $rating = $postDataArr['rating'];
         $result=$this->user_api_model->patientrating($id,$rating);
          
         if($result != null)
         {
             
             // $get=$this->user_api_model->getdoctorfulldetails($id);
             $this->response(
                 array(
                     'status_code' => 200, 
                     'message' =>"Rating set successfully.", 
                     "result" => $result
                 ));
         }
         else
        {
             $this->response(
                array(
                   'status_code' => 404, 
                   'message' =>"rating not set.", 
                   "result" => $result
                     ));
         }
     }

     // get patient rating

     //// get doctor rating
    function getpatientrating_post()
    {
   $postDataArr = $this->post();
     $id = $postDataArr['id'];
     $result=$this->user_api_model->getpatientrating($id);
     if($result != null)
       {
           
           // $get=$this->user_api_model->getdoctorfulldetails($id);
           $this->response(
               array(
                   'status_code' => 200, 
                   'message' =>"Rating set successfully.", 
                   "result" => $result
               ));
       }
       else
      {
           $this->response(
              array(
                 'status_code' => 404, 
                 'message' =>"prefrence not set.", 
                 "result" => $result
                   ));
       }
    }
    ////get doctor past appointment
    function getdoctorpastappointment_post()
       {
        $postDataArr = $this->post();
        $id = $postDataArr['doctorid'];
        $date=date('Y-m-d');
        // print_r($date);die;
        $result=$this->user_api_model->getdoctorpastappointment($id,$date);
        if($result != null)
            {
                $this->response(
                    array(
                        'status_code' => 200, 
                        'message' =>"appointment get successfully.", 
                        "result" => $result
                    ));

            }
            else
            {
                 $this->response(
                    array(
                       'status_code' => 404, 
                       'message' =>"didn't have any appointment.", 
                       "result" => $result
                         ));
             }
       }

       /// get doctor patient past appointment

       function getpatientpastappointment_post()
         {
            $postDataArr = $this->post();
            $id = $postDataArr['id'];
            $date=date('Y-m-d');
            $result=$this->user_api_model->getpatientpastappointment($id,$date);
            if($result != null)
            {
                $this->response(
                    array(
                        'status_code' => 200, 
                        'message' =>"appointment get successfully.", 
                        "result" => $result
                    ));

            }
            else
            {
                 $this->response(
                    array(
                       'status_code' => 404, 
                       'message' =>"didn't have any appointment.", 
                       "result" => $result
                         ));
             }

         }


         //////////////// region select
         function getregion_get()
         {
             $result = $this->user_api_model->getregion();
             if($result)
             {
                 $this->response(
                     array(
                         'status_code' => 200, 
                         'message' =>"region.", 
                         "result" => $result
                     )
                 );
             }
             else
             {
                 $this->response(array(
                     'status_code' => 200,
                     'message' => 'region not found',
                     "result" => array()
                 ));
             }
     } 
         
        //////////////// comunas select
        function getcomunas_get()
        {
            $result = $this->user_api_model->getcomunas();
            if($result)
            {
                $this->response(
                    array(
                        'status_code' => 200, 
                        'message' =>"get comunas.", 
                        "result" => $result
                    )
                );
            }
            else
            {
                $this->response(array(
                    'status_code' => 200,
                    'message' => 'comunas not found',
                    "result" => array()
                ));
            }
    }
    //////// Update doctor Desc
    function updatedoctorexp_post()
    {
    $postDataArr = $this->post();   
   
    if($postDataArr)
    {
      
        $userid = $postDataArr['id'];
        $userInfo = $postDataArr['experiencedetails'];
        // print_r($id);die;
        $result = $this->user_api_model->updatedoctorexp($userid, $userInfo);
        $this->response(
            array(
                'status_code' => 200, 
                'message' =>"Experience details updated successful.", 
                "result" => $result
                    )
                    
                );
            }
            else 
        {
            $this->response(
                array(
                    'status_code' => 404, 
                    'message' =>"Experience details update Unsuccessful.", 
                    "result" => $result
                )
            );
        }
    } 
}