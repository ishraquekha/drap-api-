<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class User_api_model extends CI_Model
{
    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getUserInfo($email, $password)
    {
        $this->db->select('id as patientid, email, firstname, middlename, lastname, profilepic, address, phone, 
        gender, latitude, longitude, IsActive, IsDeleted');
        $this->db->from('patient');
        $this->db->where('IsActive', 1);
        $this->db->where('IsDeleted', 0);
        $this->db->where('email', $email);
		$this->db->where('password', $password);
        $query = $this->db->get();
        
        return $query->row_array();
    }
    
    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
    */
    function getDoctorUserInfo($email, $password)
    {
        $this->db->select('doctor.id as doctorid,doctor.idnumber,doctor.firstname,doctor.middlename,doctor.lastname,(doctor.rating) / (doctor.ratebypatient) as rating,  doctor.email,doctor.password,doctor.phone,doctor.address,doctor.gender,
        doctor.dateofbirth,doctor.profilepic,doctor.colleaguenumber,doctor.university,doctor.title,doctor.experience,doctor.experiencedetails,doctor.consultationrate,
        doctor.consultationrateunit,doctor.consultationtime,doctor.latitude,doctor.longitude,doctor.isverified,doctor.isactive,doctor.isdeleted,doctor.ispediatrics,doctor.radius,doctor.isavailable,doctor.identitydocument,doctor.eunacom,doctor.legaldocument,doctor.healthcertificate,doctor.stripe_id');
        $this->db->from('doctor');
         
        $this->db->where('doctor.email', $email);
        $this->db->where('doctor.password', $password);
        $this->db->where('doctor.isactive', 1);
        $this->db->where('doctor.isdeleted', 0);
       
        $query = $this->db->get();
        
        return $query->row_array();
    }
    

    

    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $userId : This is user id
     * @return {mixed} $result : This is searched result
    */
    function checkEmailExistspatient($email)
    {
        $this->db->select("email");
        $this->db->from("patient");
        $this->db->where("email", $email);   
        $this->db->where("isDeleted", 0);   
        $this->db->where("isActive", 1);   
        
        $query = $this->db->get();

        return $query->result();
    }
    ////for doctor
    function checkEmailExistsdoctor($email)
    {
        $this->db->select("email");
        $this->db->from("doctor");
        $this->db->where("email", $email);   
        $this->db->where("isDeleted", 0);   
        $this->db->where("isActive", 1);   
        
        $query = $this->db->get();

        return $query->result();
    }

        /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $userId : This is user id
     * @return {mixed} $result : This is searched result
    */
    function checkDetailExists($patientid)
    {
        $this->db->select("id");
        $this->db->from("paymentmethod");
        $this->db->where("patientid", $patientid);      
        $this->db->where("isActive", 1);   
        
        $query = $this->db->get();

        return $query->result();
    }

        /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $userId : This is user id
     * @return {mixed} $result : This is searched result
    */
    function checkDoctorEmailExists($email)
    {
        $this->db->select("email");
        $this->db->from("doctor");
        $this->db->where("email", $email);   
        $this->db->where("isdeleted", 0);   
        $this->db->where("isactive", 1);   
        
        $query = $this->db->get();

        return $query->result();
    }
    
    function addNewUser($userInfo)
    {
        $this->db->trans_start();
        $this->db->insert('patient', $userInfo);
        
        $insert_id = $this->db->insert_id();

        $userMedicalInfo = array("patientid" => $insert_id);
        $this->db->insert('patientmedicalinformation', $userMedicalInfo);
        
        $this->db->trans_complete();
        
        return $insert_id;
    } 

    function addDoctorUser($userInfo)
    {
        $this->db->trans_start();
        $this->db->insert('doctor', $userInfo);
        
        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();
        
        return $insert_id;
    } 

    function feedbackReview($userInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_reviews', $userInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }

   /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $userId : This is user id
     * @return {mixed} $result : This is searched result
     */
    function verifyUser($userId, $password)
    {
        $this->db->select("userId, Email");
        $this->db->from("tbl_users");
        $this->db->where("password", $password);   
        $this->db->where("userId", $userId);   
        $this->db->where("isDeleted", 0);   
        $this->db->where("isActive", 1);   
        
        $query = $this->db->get();

        return $query->result();
    }
    /////
    function getdoctorswithnorate($patientid,$latitude, $longitude)
    {
        $blockedList = $this->getBlockedDoctors($patientid);
       
        $this->db->select('doctor.id,doctor.firstname,doctor.middlename,doctor.lastname,(rating) / (ratebypatient) as rating,  doctor.email,doctor.password,doctor.phone,doctor.address,doctor.gender,
        doctor.dateofbirth,doctor.profilepic,doctor.colleaguenumber,doctor.title,doctor.experience,doctor.experiencedetails,doctor.consultationrate,
        doctor.consultationrateunit,doctor.consultationtime,doctor.latitude,doctor.longitude,doctor.isavailable,status.isfav,doctor.radius as radius, (SELECT (3956 * 2 * ASIN(SQRT( POWER(SIN(('.$latitude.' - latitude) * pi()/180 / 2), 2) + 
        COS('.$latitude.' * pi()/180) * COS(latitude * pi()/180) * POWER(SIN(('.$longitude.' - longitude) * pi()/180 / 2), 2) )))) as distance');
        $this->db->from('doctor');
        $this->db->join('status', 'doctor.id=status.doctorid and status.patientid='.$patientid , 'LEFT'); 
        $this->db->where('doctor.isactive', 1);
        $this->db->where('doctor.isverified', 1);
        // $this->db->where('doctor.consultationrate <=', $rate);
        // $this->db->where('doctor.consultationtime <=', $duration);
        $this->db->where_not_in('doctor.id', $blockedList);
        $this->db->having('distance < radius');

        // $this->db->where_in('doctor.id', $isfav);
        $query = $this->db->get();
        
        return $query->result_array();
    }
// for blocked getdoctors

    function getdoctors($patientid,$latitude, $longitude, $rate, $duration)
    {
        $blockedList = $this->getBlockedDoctors($patientid);
       
        $this->db->select('doctor.id,doctor.idnumber,doctor.firstname,doctor.middlename,doctor.lastname,(rating) / (ratebypatient) as rating,  doctor.email,doctor.password,doctor.phone,doctor.address,doctor.gender,
        doctor.dateofbirth,doctor.profilepic,doctor.colleaguenumber,doctor.title,doctor.experience,doctor.experiencedetails,doctor.university,doctor.eunacom,doctor.radius as radius,doctor.consultationrate,
        doctor.consultationrateunit,doctor.consultationtime,doctor.latitude,doctor.longitude,doctor.isavailable,status.isfav, (SELECT (3956 * 2 * ASIN(SQRT( POWER(SIN(('.$latitude.' - latitude) * pi()/180 / 2), 2) + 
        COS('.$latitude.' * pi()/180) * COS(latitude * pi()/180) * POWER(SIN(('.$longitude.' - longitude) * pi()/180 / 2), 2) )))) as distance');
        $this->db->from('doctor');
        $this->db->join('status', 'doctor.id=status.doctorid and status.patientid='.$patientid , 'LEFT'); 
        $this->db->where('doctor.isactive', 1);
        $this->db->where('doctor.isverified', 1);
        $this->db->where('doctor.consultationrate <=', $rate);
        $this->db->where('doctor.consultationtime <=', $duration);
        $this->db->where('doctor.isavailable', 1);
        $this->db->where_not_in('doctor.id', $blockedList);
        $this->db->having('distance < radius');

        // $this->db->where_in('doctor.id', $isfav);
        $query = $this->db->get();
        
        return $query->result_array();
    }

    // function getfavDoctors($patientid)
    // {
    //     $this->db->select('doctorid');
    //     $this->db->from('status');
    //     $this->db->where('patientid', $patientid);
    //     $this->db->where('isfav', 1);
    //     $query = $this->db->get();
    //     $result = $query->result_array();
    //     $returnResponse = '';
    //     foreach($result as $r){
    //         if($returnResponse == ''){
    //             $returnResponse = $r['doctorid'];
    //         }else{
    //             $returnResponse = $returnResponse . ',' . $r['doctorid'];
    //         }
    //     }
    //     // $returnResponse = implode(',', $result);
    //     return $returnResponse;

    // }
    function getBlockedDoctors($patientid)
    {
        $this->db->select('doctorid');
        $this->db->from('status');
        $this->db->where('patientid', $patientid);
        $this->db->where('isblocked', 1);
        $query = $this->db->get();
        $result = $query->result_array();
        $returnResponse = '';
        foreach($result as $r){
            if($returnResponse == ''){
                $returnResponse = $r['doctorid'];
            }else{
                $returnResponse = $returnResponse . ',' . $r['doctorid'];
            }
        }
        // $returnResponse = implode(',', $result);
        return $returnResponse;

    }

    // original -------------------------------

    // function getdoctors()
    // {
    //     $this->db->select('id,firstname,middlename,lastname,email,password,phone,address,gender,
    //     dateofbirth,profilepic,colleaguenumber,title,experience,experiencedetails,consultationrate,
    //     consultationrateunit,consultationtime,latitude,longitude');
    //     $this->db->from('doctor');
    //     $this->db->where('isactive', 1);
    //     $this->db->where('isverified', 1);
    //     $query = $this->db->get();

    //     return $query->result_array();
    // }
    
    
     /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getUserDetailsById($userId)
    {
        $this->db->select('p.`id` as patientid , p.`email`, p.`firstname`, p.`middlename`, p.`lastname`, 
        p.`phone`, p.`profilepic`, p.`address`, p.`gender`, p.`dateofbirth`, 
        p.IsActive, p.IsDeleted, p.latitude, p.longitude, 
        `pmi.medicalhistory`, `pmi.surgeries`, `pmi.drugtaker`, `pmi.isallergictomedications`, 
        `pmi.allergictomedications`, `pmi.istobacco`, `pmi.tobaccorating`, `pmi.isalcohol`, 
        `pmi.alcoholrating`, `pmi.isdrugs`, `pmi.drugsdetails`, `pmi.isphysicalactivity`, 
        `pmi.physicalactivitydetails`, `pmi.ispragnancy`, `pmi.pragnancydetails`, `pmi.isbirth`, 
        `pmi.birthdetails`, `pmi.isabortions`, `pmi.abortiondetails`, `pmi.iscontraceptives`, 
        `pmi.contraceptivesdetails`, pmi.`physicalactivityrating` , pmi.`drugrating`, pmi.`lastmansturationdate`, pmi.`familybackground`,(p.`rating`) / (p.`ratebydoctor`) as rating,p.`ejabberduser`');
        $this->db->from('patient p');
        $this->db->join('patientmedicalinformation pmi', 'p.id = pmi.patientid');
        $this->db->where('p.id', $userId);
        $this->db->where('p.IsActive', 1);
        $this->db->where('p.IsDeleted', 0);

        $query = $this->db->get();
        
        return $query->row_array();
    }

         /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getDoctorsDetailsById($userId)
    {


        $this->db->select('doctor.id as doctorid,doctor.idnumber,doctor.firstname,doctor.middlename,doctor.lastname,(rating) / (ratebypatient) as rating, doctor.email,doctor.password,doctor.phone,doctor.address,doctor.gender,
        doctor.dateofbirth,doctor.profilepic,doctor.colleaguenumber,doctor.university,doctor.title,doctor.experience,doctor.experiencedetails,doctor.consultationrate,
        doctor.consultationrateunit,doctor.consultationtime,doctor.latitude,doctor.longitude,doctor.isactive,doctor.ispediatrics,doctor.radius,doctor.isavailable,doctor.ejabberduser,doctor.identitydocument,doctor.eunacom,doctor.legaldocument,doctor.healthcertificate');
        $this->db->from('doctor');
        // $this->db->join('rating', 'd.id=rating.doctorid');
        $this->db->where('doctor.id', $userId);
        $this->db->where('doctor.isactive', 1);
        $this->db->where('doctor.isdeleted', 0);
    //     $this->db->select('d.`id` as doctorid , d.`email`, d.`firstname`, d.`middlename`, d.`lastname`, 
    //    d.`(rating) / (ratebypatient) as rating`,  d.`phone`, d.`profilepic`, d.`address`, d.`gender`, d.`dateofbirth`, 
    //     d.IsActive, d.IsDeleted, d.latitude, d.longitude, 
    //     `d.colleaguenumber`, `d.title`, `d.experience`, `d.experiencedetails`, 
    //     `d.consultationrate`, `d.consultationrateunit`, `d.consultationtime`, `d.identitydocument`, `d.eunacom`, `d.legaldocument`,`d.isdeleted`, `d.isactive`,`d.radius`,`d.ispediatrics`,`d.isavailable`');
    //     $this->db->from('doctor d');
    //     // $this->db->join('rating', 'd.id=rating.doctorid');
    //     $this->db->where('d.id', $userId);
    //     $this->db->where('d.isactive', 1);
    //     $this->db->where('d.isdeleted', 0);

        $query = $this->db->get();
        
        return $query->row_array();
    }

         /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getPrefferedSettings($userId)
    {
        $this->db->select('UserId, isAvailable, priceSetting, rangeSetting, categoriesSettings, notificationSetting');
        $this->db->from('tbl_users');
        $this->db->where('userId', $userId);
        $this->db->where('isActive', 1);
        $this->db->where('isDeleted', 0);

        $query = $this->db->get();

        $settings = $query->row_array();
        $categories = explode('_', $settings['categoriesSettings']);

        $this->db->select('Id, Title');
        $this->db->from('tbl_category');
        $this->db->where_in($categories);
        $this->db->where('isActive', 1);
        $this->db->where('isDeleted', 0);

        $queryCat = $this->db->get();
        $preferredCat = $queryCat->result_array();

        $userSetting = array();
        $userSetting['available'] = $settings['isAvailable'];
        $userSetting['price'] = $settings['priceSetting'];
        $userSetting['distanceInMiles'] = $settings['rangeSetting'];
        $userSetting['notification'] = $settings['notificationSetting'];
        $userSetting['categoryIds'] = $preferredCat;
         
        return $userSetting;

    }

    function getdoctordetails($doctorid)
    {
        $this->db->select('id, firstname, middlename, lastname, email, password, phone, address,
         gender, dateofbirth, profilepic, colleaguenumber,university, title, experience, experiencedetails,
          consultationrate, consultationrateunit, consultationtime, latitude, longitude, identitydocument,
           eunacom, legaldocument, heathcertificate');
        $this->db->from('doctor');
        $this->db->where('id', $doctorid);
        $this->db->where('isactive', 1);
        $this->db->where('isdeleted', 0);

        $query = $this->db->get();

        return $query->row_array();
    }

    function getspacializations()
    {
 
        $this->db->select('*');
        $this->db->from('spacializations');
        $this->db->where('isactive', 1);
        $this->db->where('isdeleted', 0);
        $query = $this->db->get();

        return $query->result_array();
    }

    function insertpreference($preference)
    {
        $this->db->trans_start();
        $this->db->insert('patientpreferences', $preference);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }

    //// check for patient preference existing in table or not
    function getcheckpatient($patientid)
        {
        $this->db->select('1');
        $this->db->from('patientpreferences');
        $this->db->where('patientid', $patientid);
        $query = $this->db->get();
        return $query->row_array();   
        }
    /////// Update patient preference

    function patientpreferenceupdate($id,$preference)
    {
        $this->db->where('patientid', $id);
        $result=$this->db->update('patientpreferences', $preference);
        return $result;
    }


    function getpreferencedata($preferenceid)
    {
        $this->db->select('id, patientid, rate, duration, spacialist');
        $this->db->from('patientpreferences');
        $this->db->where('id', $preferenceid);
        $query = $this->db->get();

        return $query->row_array();
    }

    function getpatientpreference($patientid)
    {
        $this->db->select('id, rate, duration, spacialist');
        $this->db->from('patientpreferences');
        $this->db->where('patientid', $patientid);
        $query = $this->db->get();

        return $query->row_array();
    }


    function getspacialistdoctor($spacialistid)
    {
        $this->db->select('doctorsid');
        $this->db->from('doctorspacializations');
        $this->db->where('spacialistid', $spacialistid);
        $query = $this->db->get();

        return $query->result_array();
        
    }

    function getpreferencedoctors($patientid, $rate, $duration, $latitude, $longitude, $doctorid)
    {
        // print_r($doctorid);die;
        // foreach($doctorid as $d);
        // print_r($d);die;
        // print_r($rate);die;
        $blockedList = $this->getBlockedDoctors($patientid);
        $this->db->select('doctor.id,doctor.idnumber,doctor.profilepic,doctor.firstname, doctor.middlename, doctor.lastname,(doctor.rating) / (doctor.ratebypatient) as rating,  doctor.email, doctor.experience, doctor.experiencedetails, doctor.university,doctor.eunacom, doctor.phone, doctor.address, doctor.gender, doctor.consultationrate, doctor.consultationtime, doctor.latitude, doctor.longitude,doctor.isavailable,doctor.radius as radius,status.isfav,
        (SELECT (3956 * 2 * ASIN(SQRT( POWER(SIN(('.$latitude.' - latitude) * pi()/180 / 2), 2) + 
        COS('.$latitude.' * pi()/180) * COS(latitude * pi()/180) * POWER(SIN(('.$longitude.' - longitude) * pi()/180 / 2), 2) )))) as distance');
        $this->db->from('doctor');
        $this->db->join('status', 'doctor.id=status.doctorid and status.patientid='.$patientid , 'LEFT');
        $this->db->where('doctor.consultationrate <=', $rate);
        $this->db->where('doctor.consultationtime <=', $duration);
        $this->db->where('doctor.id', $doctorid);
        $this->db->where('doctor.isactive', 1);
        $this->db->where('doctor.isverified', 1);
        $this->db->where('doctor.isavailable', 1);
        $this->db->where_not_in('doctor.id', $blockedList);
        $this->db->having('distance < radius');
        $query = $this->db->get();
        // print_r($query);die;
        
        return $query->row_array();
        
    }

    function insertappointments($appoint)
    {
        $this->db->trans_start();
        $this->db->insert('appointments', $appoint);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }

    function getappointments($insertid)
    {
        $this->db->select('id,patientid,doctorid,timeofarrivel,isaccepted,scheduleddate,created_date,accept_time,isrefund,iscancelledbypatient, CONCAT_WS( " ", appointments.scheduleddate, appointments.timeofarrivel ) AS dat ', FALSE);
        $this->db->from('appointments');
        $this->db->where('id', $insertid);
        $query = $this->db->get();

        return $query->row_array();
    }

    function getpatientappointments($patientid)
    {
        $this->db->select('appointments.id,appointments.patientid,appointments.doctorid,doctor.firstname,doctor.lastname,(doctor.rating) / (doctor.ratebypatient) as rating,doctor.profilepic,doctor.consultationrate,doctor.address,appointments.scheduleddate,appointments.timeofarrivel,
        appointments.isaccepted,appointments.isrejected,appointments.iscancelledbypatient,appointments.accept_time as acceptedtime');
        $this->db->from('appointments');
        $this->db->join('doctor', 'doctor.id=appointments.doctorid');
        $this->db->where('appointments.patientid', $patientid);
        $this->db->where('appointments.isactive', 1);
        $query = $this->db->get();

        return $query->result_array();
    }

    function getdoctorappointments($doctorid)
    {
        $this->db->select('appointments.id,appointments.patientid,appointments.doctorid,appointments.scheduleddate,appointments.timeofarrivel,
        appointments.isaccepted,appointments.isrejected,appointments.iscancelledbypatient,patient.firstname,patient.lastname,(patient.rating) / (patient.ratebydoctor) as rating,patient.profilepic,patient.dateofbirth,patient.address,doctor.consultationrate,doctor.consultationtime');
        $this->db->from('appointments');
        $this->db->join('patient', 'appointments.patientid=patient.id');
        $this->db->join('doctor', 'appointments.doctorid=doctor.id');
        $this->db->where('appointments.doctorid', $doctorid);
        $this->db->where('appointments.iscancelledbypatient', 0);
        $this->db->where('appointments.isactive', 1);
        // $this->db->where('appointments.isaccepted', 0);
        $query = $this->db->get();

        return $query->result_array();
    }
    
    function getacceptedbydoctor($appointmentid)
    {   
        $dd=date('Y-m-d');
        $now = new DateTime();
        $now->setTimezone(new DateTimezone('Asia/Kolkata'));
        $dt=$now->format('H:i:s');
        $date = $dd . ' ' . $dt;
        // $date=date('Y-m-d H:i:s');
        $this->db->set('isaccepted', 1);
        $this->db->set('isrejected', 0);
        $this->db->set('accept_time',$date );
        $this->db->where('id', $appointmentid);
        $query = $this->db->update('appointments');

        return $query;
    }

    function getrejectedbydoctor($appointmentid)
    {
        $this->db->set('isrejected', 1);
        $this->db->set('isaccepted', 0);
        $this->db->where('id', $appointmentid);
        $query = $this->db->update('appointments');

        return $query;
    }

    function getcancelledbypatient($appointmentid)
    {
        $this->db->set('iscancelledbypatient', 1);
        $this->db->where('id', $appointmentid);
        $query = $this->db->update('appointments');

        return $query;
    }

    function updatetokenpatient($key, $email)
    {
        $this->db->set('remember_token', $key);
        $this->db->set('isreset', 1);
        $this->db->where('email', $email);
        $query = $this->db->update('patient');

        return $query;
    }
    ////
    function updatetokendoctor($key, $email)
    {
        $this->db->set('remember_token', $key);
        $this->db->set('isreset', 1);
        $this->db->where('email', $email);
        $query = $this->db->update('doctor');

        return $query;
    }

    function cheakForResetpatient($key)
    {
        $this->db->select('email');
        $this->db->where('isreset', 1);
        $this->db->from('patient');
        $this->db->where('remember_token', $key);
        $query = $this->db->get();

        return $query->row_array();
    }

    function getidpatient($key)
    {
        $this->db->select('id');
        $this->db->where('isreset', 1);
        $this->db->from('patient');
        $this->db->where('remember_token', $key);
        $query = $this->db->get();

        return $query->row_array();
    }
    /////doctor 

    function cheakForResetdoctor($key)
    {
        $this->db->select('email');
        $this->db->where('isreset', 1);
        $this->db->from('doctor');
        $this->db->where('remember_token', $key);
        $query = $this->db->get();

        return $query->row_array();
    }

    function getiddoctor($key)
    {
        $this->db->select('id');
        $this->db->from('doctor');
        $this->db->where('remember_token', $key);
        $this->db->where('isreset', 1);
        $query = $this->db->get();
        return $query->row_array();
    }
    function updatePasswordpatient($password, $id)
    {
        $this->db->set('password', $password);
        $this->db->set('isreset', 0);
        $this->db->set('remember_token', null);
        $this->db->where('id', $id);
        $query = $this->db->update('patient');

        return $query;
    }
    ///// doctor update password
    function updatePassworddoctor($password, $id)
    {
        $this->db->set('password', $password);
        $this->db->set('isreset', 0);
        $this->db->set('remember_token', null);
        $this->db->where('id', $id);
        $query = $this->db->update('doctor');

        return $query;
    }
    
    function addPaymentInfo($paymentInfo)
    {
        $this->db->trans_start();
        $this->db->insert('paymentmethod', $paymentInfo);
        
        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();
        
        return $insert_id;
    } 


    /**
     * Update details in DB
     *
     * @access	public
     * @param	string
     * @param	array
     * @param	array
     * @return	string
    */
    public function update_single($table, $updates, $conditions = array()) {
        //If there are conditions
        if (count($conditions) > 0) {
            $this->condition_handler($conditions);
        }
        return $this->db->update($table, $updates);
    }

    public function fetch_data($table, $fields = '*', $conditions = array(), $returnRow = false, $raw_query = FALSE) {
        //Preparing query
        if($raw_query) {
            $this->db->select($fields, FALSE);
        } else{
            $this->db->select($fields);
        }
        $this->db->from($table);

        //If there are conditions
        if (count($conditions) > 0) {
            $this->condition_handler($conditions);
        }
        $query = $this->db->get();
        //Return
        
        return $returnRow ? $query->row_array() : $query->result_array();
    }

    
    /**
     * Handle different conditions of query
     *
     * @access	public
     * @param	array
     * @return	bool
     */
    private function condition_handler($conditions) {
        //Where
        if (array_key_exists('where', $conditions)) {

            //Iterate all where's
            foreach ($conditions['where'] as $key => $val) {
                $this->db->where($key, $val);
            }
        }

        //Where In
        if (array_key_exists('where_in', $conditions)) {

            //Iterate all where in's
            foreach ($conditions['where_in'] as $key => $val) {
                $this->db->where_in($key, $val);
            }
        }

        //Where Not In
        if (array_key_exists('where_not_in', $conditions)) {

            //Iterate all where in's
            foreach ($conditions['where_not_in'] as $key => $val) {
                $this->db->where_not_in($key, $val);
            }
        }

        //Having
        if (array_key_exists('having', $conditions)) {
            $this->db->having($conditions['having']);
        }

        //Group By
        if (array_key_exists('group_by', $conditions)) {
            $this->db->group_by($conditions['group_by']);
        }

        //Order By
        if (array_key_exists('order_by', $conditions)) {

            //Iterate all order by's
            foreach ($conditions['order_by'] as $key => $val) {
                $this->db->order_by($key, $val);
            }
        }

        //Like
        if (array_key_exists('like', $conditions)) {

            //Iterate all likes
            $i = 1;
            foreach ($conditions['like'] as $key => $val) {
                if($i == 1){
                   $this->db->like('LOWER('.$key.')', strtolower($val), 'after');
               }else{
                   $this->db->or_like('LOWER('.$key.')', strtolower($val), 'after');
               }
               $i++;
           }
       }

        //Limit
       if (array_key_exists('limit', $conditions)) {

            //If offset is there too?
        if (count($conditions['limit']) == 1) {
            $this->db->limit($conditions['limit'][0]);
        } else {
            $this->db->limit($conditions['limit'][0], $conditions['limit'][1]);
        }
    }
        }
        // address update
        function updatepatientaddress($id, $address)
        {
            $this->db->set('address', $address);
            $this->db->where('id', $id);
            $query = $this->db->update('patient');
            // print_r($query);die;
            return $query;
        }
        ///

        ///------patient details
        function patientdetails($id)
        { $this->db->select('p.`id` as patientid , p.`email`, p.`firstname`, p.`middlename`, p.`lastname`, 
            p.`phone`, p.`profilepic`, p.`address`, p.`gender`, p.`dateofbirth`, 
            p.IsActive, p.IsDeleted, p.latitude, p.longitude, 
            `pmi.medicalhistory`, `pmi.surgeries`, `pmi.drugtaker`, `pmi.isallergictomedications`, 
            `pmi.allergictomedications`, `pmi.istobacco`, `pmi.tobaccorating`, `pmi.isalcohol`, 
            `pmi.alcoholrating`, `pmi.isdrugs`, `pmi.drugsdetails`, `pmi.isphysicalactivity`, 
            `pmi.physicalactivitydetails`, `pmi.ispragnancy`, `pmi.pragnancydetails`, `pmi.isbirth`, 
            `pmi.birthdetails`, `pmi.isabortions`, `pmi.abortiondetails`, `pmi.iscontraceptives`, 
            `pmi.contraceptivesdetails`, pmi.`physicalactivityrating` , pmi.`drugrating`, pmi.`lastmansturationdate`, pmi.`familybackground`,(p.`rating`) / (p.`ratebydoctor`) as rating,p.`ejabberduser`');
            $this->db->from('patient p');
            $this->db->join('patientmedicalinformation pmi', 'p.id = pmi.patientid');
            $this->db->where('p.id', $id);
            $this->db->where('p.IsActive', 1);
            $this->db->where('p.IsDeleted', 0);
    
            $query = $this->db->get();
            
            return $query->row_array();
        }
        //get appointment by patient date
        function getappointmentbydate($id,$date,$lastdate)
        {
            // print_r($id);die;
            $dd=date('Y-m-d');
            $now = new DateTime();
            $now->setTimezone(new DateTimezone('Asia/Kolkata'));
            $dt=$now->format('H:i:s');
            $datetime = $dd . ' ' . $dt;
            $this->db->select('appointments.id,appointments.patientid,appointments.consultationreason,appointments.doctorid,doctor.firstname,doctor.lastname,(doctor.rating) / (doctor.ratebypatient) as rating,doctor.profilepic,doctor.consultationrate,doctor.address,appointments.scheduleddate,appointments.timeofarrivel,
            appointments.isaccepted,appointments.isrejected,appointments.iscancelledbypatient,appointments.accept_time as acceptedtime, CONCAT_WS( " ", appointments.scheduleddate, appointments.timeofarrivel ) AS dat ', FALSE);
            $this->db->from('appointments');
            $this->db->join('doctor', 'doctor.id=appointments.doctorid');
            $this->db->where('appointments.patientid', $id);
            $this->db->where('appointments.scheduleddate >=', $date);
            $this->db->where('appointments.scheduleddate <=', $lastdate);
            $this->db->having('dat >=', $datetime);
            $this->db->order_by('appointments.timeofarrivel', 'asc');
            $this->db->where('appointments.iscancelledbypatient', 0);
            $this->db->where('appointments.isactive', 1);   
            $query = $this->db->get();
            return $query->result_array();
        }
        ////get appointment by doctor date

        function getdoctorappointmentsbydate($doctorid,$date,$lastdate)
        {

            $dd=date('Y-m-d');
            $now = new DateTime();
            $now->setTimezone(new DateTimezone('Asia/Kolkata'));
            $dt=$now->format('H:i:s');
            $datetime = $dd . ' ' . $dt;
            $this->db->select('appointments.id,appointments.patientid,appointments.consultationreason,appointments.doctorid,appointments.scheduleddate,appointments.timeofarrivel,
            appointments.isaccepted,appointments.isrejected,appointments.iscancelledbypatient,patient.firstname,patient.lastname,(patient.rating) / (patient.ratebydoctor) as rating,patient.profilepic,patient.dateofbirth,patient.address,doctor.consultationrate,doctor.consultationtime
            , CONCAT_WS( " ", appointments.scheduleddate, appointments.timeofarrivel ) AS dat ', FALSE);
            // $this->db->select("CONCAT(appointments.scheduleddate, ' ', appointments.timeofarrivel) AS dat", FALSE);
            // $this->db->select('cast(concat('appointments.scheduleddate' .' '. 'appointments.timeofarrivel') as datetime) as dt');
            $this->db->from('appointments');
            $this->db->join('patient', 'appointments.patientid=patient.id');
            $this->db->join('doctor', 'appointments.doctorid=doctor.id');
            $this->db->where('appointments.isrejected', 0);
            $this->db->where('appointments.doctorid', $doctorid);
            $this->db->where('appointments.scheduleddate >=', $date);
            $this->db->where('appointments.scheduleddate <=', $lastdate);
            $this->db->having('dat >=', $datetime);
            // $this->db->where('appointments.scheduleddate >=',$dd)->where('appointments.timeofarrivel >=', $dt);
            $this->db->order_by('appointments.scheduleddate ', 'asc');
            $this->db->order_by('appointments.timeofarrivel', 'asc');
            // $this->db->where('appointments.iscancelledbypatient', 0);
            $this->db->where('appointments.isactive', 1);
            $query = $this->db->get();
    
            return $query->result_array();
        }
        ///for fav doctor
        function patientfav($data)
        {
            $this->db->trans_start();
            $fav= $this->db->insert('status', $data);
                
                

                $this->db->trans_complete();
                
                return $fav  ;
        }
        ////update favourite by patient
        function patientfavupdate($patientid,$doctorid,$fav)
        {
            $this->db->set('isfav', $fav);
            $this->db->where('doctorid', $doctorid);
            $this->db->where('patientid', $patientid);
            $query = $this->db->update('status');
            // print_r($query);die;
            return $query;
        }
        /////for checking favorite doctor exist or not
        function getcheckfavdoctor($patientid, $doctorid)
        {
        $this->db->select('1');
        $this->db->from('status');
        $this->db->where('patientid', $patientid);
        $this->db->where('doctorid', $doctorid);
        $query = $this->db->get();
        return $query->row_array();   
        }
        ///////get fav doctor
        function getfavdoctor($id)
        {
            $this->db->select('doctor.id,doctor.firstname,doctor.middlename,doctor.lastname,(doctor.rating) / (doctor.ratebypatient) as rating, doctor.email,doctor.password,doctor.phone,doctor.address,doctor.gender,
            doctor.dateofbirth,doctor.profilepic,doctor.colleaguenumber,doctor.title,doctor.experience,doctor.experiencedetails,doctor.consultationrate,
            doctor.consultationrateunit,doctor.consultationtime,doctor.latitude,doctor.longitude,status.isfav');
            $this->db->from('status');
            $this->db->join('doctor', 'doctor.id=status.doctorid');
            $this->db->where('status.patientid', $id);
            $this->db->where('status.isfav', 1);
            $this->db->where('status.isblocked', 0);
            $query = $this->db->get();

            return $query->result_array();   
        }
        //doctor block by patient
        function doctorblock($data)
        {
            $this->db->trans_start();
            $block= $this->db->insert('status', $data);
                
                

                $this->db->trans_complete();
                
                return $block  ;
        }
    ///////doctor blocked stats update
    function doctorblockupdate($patientid,$doctorid,$isblocked)
    {
        $this->db->set('isblocked', $isblocked);
        $this->db->where('doctorid', $doctorid);
        $this->db->where('patientid', $patientid);
        $query = $this->db->update('status');
        // print_r($query);die;
        return $query;
    }

    ///set doctor prefrence update
    function doctorpreferenceupdate($id,$data)
    {
        $this->db->where('id', $id);
      $result=$this->db->update('doctor', $data);
      return $result;
    }
    //// getting indivual doctor details
    function getdoctorfulldetails($id)
    {
        $this->db->select('doctor.id as doctorid,doctor.firstname,doctor.middlename,doctor.lastname,(rating) / (ratebypatient) as rating, doctor.email,doctor.password,doctor.phone,doctor.address,doctor.gender,
        doctor.dateofbirth,doctor.profilepic,doctor.colleaguenumber,doctor.university,doctor.title,doctor.experience,doctor.experiencedetails,doctor.consultationrate,
        doctor.consultationrateunit,doctor.consultationtime,doctor.latitude,doctor.longitude,doctor.isactive,doctor.ispediatrics,doctor.radius,doctor.isavailable');
        $this->db->from('doctor');
         
        $this->db->where('doctor.id', $id);
        $get=$this->db->get();
        return $get->row_array();
    }
    ////for doctor rating
    function doctorrating($doctorid,$rate)
    {
        $this->db->set('ratebypatient', 'ratebypatient+1', false);
        $this->db->set('rating', 'rating+'.$rate, false);
        $this->db->where('id', $doctorid);
        $query = $this->db->update('doctor');
        // print_r($query);die;
        return $query;
    }

    ///get doctor rating

    function getdoctorrating($id)
    {
        $this->db->select('(rating) / (ratebypatient) as rating');
        $this->db->where('id', $id);
        $this->db->from('doctor');
        $rating = $this->db->get();
        return $rating->row_array(); 
    }

    /// for patient rating

    function patientrating($id,$rate)
    {
        $this->db->set('ratebydoctor', 'ratebydoctor+1', false);
        $this->db->set('rating', 'rating+'.$rate, false);
        $this->db->where('id', $id);
        $query = $this->db->update('patient');
        // print_r($query);die;
        return $query;
    }

    /// get patient rating

    
    function getpatientrating($id)
    {
        $this->db->select('(rating) / (ratebydoctor) as rating');
        $this->db->where('id', $id);
        $this->db->from('patient');
        $rating = $this->db->get();
        return $rating->row_array(); 
    }

    ////  get  doctor  past  appointment

    function getdoctorpastappointment($id,$date)
    {
        $this->db->select('appointments.id,appointments.patientid,appointments.consultationreason,appointments.doctorid,appointments.scheduleddate,appointments.timeofarrivel,
        appointments.isaccepted,appointments.isrejected,appointments.iscancelledbypatient,patient.firstname,patient.lastname,(patient.rating) / (patient.ratebydoctor) as rating,patient.profilepic,patient.dateofbirth,patient.address,doctor.consultationrate,doctor.consultationtime');
        $this->db->from('appointments');
        $this->db->join('patient', 'appointments.patientid=patient.id');
        $this->db->join('doctor', 'appointments.doctorid=doctor.id');
        $this->db->where('appointments.isrejected', 0);
        $this->db->where('appointments.doctorid', $id);
        $this->db->where('appointments.scheduleddate <=', $date);
        $this->db->order_by('appointments.scheduleddate', 'asc');
        $this->db->where('appointments.iscancelledbypatient', 0);
        $this->db->where('appointments.isactive', 1);
        $query = $this->db->get();

        return $query->result_array();   
    }

    //get  patient past appointment

    function getpatientpastappointment($id,$date)
    {
        $this->db->select('appointments.id,appointments.patientid,appointments.consultationreason,appointments.doctorid,doctor.firstname,doctor.lastname,(doctor.rating) / (doctor.ratebypatient) as rating,doctor.profilepic,doctor.consultationrate,doctor.address,appointments.scheduleddate,appointments.timeofarrivel,
        appointments.isaccepted,appointments.isrejected,appointments.iscancelledbypatient,appointments.accept_time as acceptedtime');
        $this->db->from('appointments');
        $this->db->join('doctor', 'doctor.id=appointments.doctorid');
        $this->db->where('appointments.isrejected', 0);
        $this->db->where('appointments.patientid', $id);
        $this->db->where('appointments.scheduleddate <=', $date);
        // $this->db->where('appointments.scheduleddate <=', $lastdate);
        $this->db->order_by('appointments.scheduleddate', 'asc');
        $this->db->where('appointments.isactive', 1);   
        $query = $this->db->get();
        return $query->result_array();   
    }

    ///stripe Create payment info
    function createcharge($c)
    {
        $this->db->trans_start();
        $result= $this->db->insert('paymentinfo', $c);
            
            

            $this->db->trans_complete();
            
            return $result  ;
    }
    ////// if error they also insert in database
    function errorcharge($c)
    {
        $this->db->trans_start();
        $result= $this->db->insert('paymentinfo', $c);
            
            

            $this->db->trans_complete();
            
            return $result  ;
    }
    ////////---------------------
    /////for chat server

    public function callEjabberdAPI($method, $url, $data){
        $curl = curl_init();
        switch ($method){
           case "POST":
              curl_setopt($curl, CURLOPT_POST, 1);
              if ($data)
                 curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
              break;
           case "PUT":
              curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
              if ($data)
                 curl_setopt($curl, CURLOPT_POSTFIELDS, $data);                         
              break;
           default:
              if ($data)
                 $url = sprintf("%s?%s", $url, http_build_query($data));
        }
        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
           'APIKEY: 111111111111111111111',
           'Content-Type: application/json',
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // EXECUTE:
        $result = curl_exec($curl);
        if(!$result){die("Connection Failure");}
        curl_close($curl);
        return $result;
     
    }

    //////----------get bank

    function getbank()
    {
        $this->db->select('*');
        $this->db->from('banks');
        $query = $this->db->get();
        return $query->result_array();   

    }

     //////----------get account type

     function getaccounttype()
     {
         $this->db->select('*');
         $this->db->from('account_type');
         $query = $this->db->get();
         return $query->result_array();   
 
     }

    //////----------get region

    function getregion()
    {
        $this->db->select('*');
        $this->db->from('regiones');
        $query = $this->db->get();
        return $query->result_array();   

    }

      //////----------get region

      function getcomunas()
      {
          $this->db->select('*');
          $this->db->from('comunas');
          $query = $this->db->get();
          return $query->result_array();   
  
      }

      /////// get update doctor description

      function updatedoctorexp($id,$data)
      {
        $this->db->set('experiencedetails', $data);
        $this->db->where('id', $id);
        $query = $this->db->update('doctor');
        // print_r($query);die;
        return $query;
      }
      /////get payment details for patient
      function getpaymentdetails($id)
      {
          $this->db->select('*');
          $this->db->from('paymentinfo');
          $this->db->where('appointmentsid', $id);
          $this->db->where('is_type', 0);
          $query = $this->db->get();
          return $query->row_array();   
      }
      /////get payment details for doctor
      function getpaymentdetailsdoc($id)
      {
          $this->db->select('*');
          $this->db->from('paymentinfo');
          $this->db->where('appointmentsid', $id);
          $this->db->where('is_type', 1);
          $query = $this->db->get();
          return $query->row_array();   
      }
      function updatePayment($status,$id,$amount)
      {
          $this->db->set('isrefund', $status);
          $this->db->set('patient_refund', $amount);
          $this->db->where('appointmentsid', $id);
            $this->db->update('paymentinfo');
          $this->db->set('isrefund', $status);
          $this->db->set('iscancelledbypatient', 1);
          $this->db->set('isactive', 1);
          $this->db->where('id', $id);
          $query = $this->db->update('appointments');
          return $query;
      }
      ///doctor refund
      function updatePaymentdoc($status,$id,$refund)
      {
        //   $this->db->set('isrefund_doc', $status);
        $this->db->set('isrefund', $status);
        $this->db->set('patient_refund', $refund);
        $this->db->where('appointmentsid', $id);
        $this->db->update('paymentinfo');
          $this->db->set('isaccepted', 0);
          $this->db->set('isrejected', 1);
          $this->db->set('isrefund', $status);
        //   $this->db->set('isrefund_doc', $status);
          $this->db->where('id', $id);
          $query = $this->db->update('appointments');
          return $query;
      }
      function updatecard($id,$cid,$account)
      {
          $this->db->set('stripe_id', $cid);
          $this->db->set('account', $account);
          $this->db->where('id', $id);
          $query = $this->db->update('doctor');
          
       
          return $query;
      }
      function getappointmentdoc($insertid)
    {
        $this->db->select('appointments.id,appointments.patientid,appointments.isaccepted,appointments.doctorid,doctor.email,doctor.stripe_id,doctor.account,appointments.timeofarrivel,appointments.scheduleddate,appointments.created_date,appointments.accept_time,appointments.iscancelledbypatient');
        $this->db->from('appointments');
        $this->db->join('doctor','doctor.id=appointments.doctorid');
        $this->db->where('appointments.id', $insertid);
        $query = $this->db->get();

        return $query->row_array();
    }
    function getpreferencedoctorsonly($patientid,$latitude,$longitude)
    {
        $blockedList = $this->getBlockedDoctors($patientid);
        $this->db->select('doctor.id,doctor.idnumber,doctor.profilepic,doctor.firstname, doctor.middlename, doctor.lastname,(doctor.rating) / (doctor.ratebypatient) as rating,  doctor.email, doctor.experience, doctor.experiencedetails, doctor.university,doctor.eunacom, doctor.phone, doctor.address, doctor.gender, doctor.consultationrate, doctor.consultationtime, doctor.latitude, doctor.longitude,doctor.isavailable,status.isfav,doctor.radius as radius,
        (SELECT (3956 * 2 * ASIN(SQRT( POWER(SIN(('.$latitude.' - latitude) * pi()/180 / 2), 2) + 
        COS('.$latitude.' * pi()/180) * COS(latitude * pi()/180) * POWER(SIN(('.$longitude.' - longitude) * pi()/180 / 2), 2) )))) as distance');
        $this->db->from('doctor');
        $this->db->join('status', 'doctor.id=status.doctorid and status.patientid='.$patientid , 'LEFT');
        $this->db->where('doctor.isactive', 1);
        $this->db->where('doctor.isverified', 1);
        $this->db->where('doctor.isavailable', 1);
        $this->db->where_not_in('doctor.id', $blockedList);
        $this->db->having('distance < radius');
        $this->db->order_by('distance','asc');
        $this->db->order_by('doctor.consultationrate', 'asc');
        
        $query = $this->db->get();
        // print_r($query);die;
        return $query->row_array();
        
    }
    // function getdoctorswithnorate($patientid,$latitude, $longitude)
    // {
    //     $blockedList = $this->getBlockedDoctors($patientid);
       
    //     $this->db->select('doctor.id,doctor.firstname,doctor.middlename,doctor.lastname,(rating) / (ratebypatient) as rating,  doctor.email,doctor.password,doctor.phone,doctor.address,doctor.gender,
    //     doctor.dateofbirth,doctor.profilepic,doctor.colleaguenumber,doctor.title,doctor.experience,doctor.experiencedetails,doctor.consultationrate,
    //     doctor.consultationrateunit,doctor.consultationtime,doctor.latitude,doctor.longitude,doctor.isavailable,status.isfav, (SELECT (3956 * 2 * ASIN(SQRT( POWER(SIN(('.$latitude.' - latitude) * pi()/180 / 2), 2) + 
    //     COS('.$latitude.' * pi()/180) * COS(latitude * pi()/180) * POWER(SIN(('.$longitude.' - longitude) * pi()/180 / 2), 2) )))) as distance');
    //     $this->db->from('doctor');
    //     $this->db->join('status', 'doctor.id=status.doctorid and status.patientid='.$patientid , 'LEFT'); 
    //     $this->db->where('doctor.isactive', 1);
    //     $this->db->where('doctor.isverified', 1);
    //     $this->db->where_not_in('doctor.id', $blockedList);
    //     $this->db->having('distance < 50');

    //     // $this->db->where_in('doctor.id', $isfav);
    //     $query = $this->db->get();
    //     return $query->result_array();
    // }
  
}




  